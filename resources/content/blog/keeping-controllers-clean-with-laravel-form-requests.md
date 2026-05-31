---
title: "Keeping Controllers Clean with Laravel Form Requests"
excerpt: Form Requests let you move validation and authorization logic out of your controllers and into dedicated classes — keeping your controllers slim and your rules easy to find.
cover: /media/blog/keeping-controllers-clean-with-laravel-form-requests/cover.webp
published_at: "2026-05-31 08:00:00 -0400"
modified_at: "2026-05-31 08:00:00 -0400"
tags: ["Laravel", "PHP", "Development"]
---

If you've been writing Laravel for a while, you've probably seen a controller method that starts with twenty lines of `$request->validate(...)` before doing any actual work. It's not the end of the world, but it's noise. The controller's job is to coordinate a response — not to be the place where all your validation rules live.

Laravel Form Requests solve this cleanly. They're dedicated classes that hold your validation rules (and optionally, your authorization logic), and they integrate automatically with the request lifecycle. The controller receives a pre-validated, typed request object, and it never has to think about validation at all.

## Creating a Form Request

Artisan can generate a Form Request for you:

<pre><code>
php artisan make:request StorePostRequest
</code></pre>

That creates a file at `app/Http/Requests/StorePostRequest.php` with two methods you need to fill in:

<pre><code>
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }
}
</code></pre>

The `authorize` method controls whether the current user is allowed to make this request at all. Return `true` if you're not doing any authorization checks here (or if any authenticated user should be allowed through). Return `false` to reject the request with a 403. The `rules` method returns your validation rules exactly as you'd pass them to `$request->validate()`.

## A Real Example

Here's what a Form Request looks like for creating a blog post:

<pre><code>
class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Post::class);
    }

    public function rules(): array
    {
        return [
            'title'      => ['required', 'string', 'max:255'],
            'slug'       => ['required', 'string', 'max:255', 'unique:posts,slug'],
            'body'       => ['required', 'string'],
            'status'     => ['required', 'in:draft,published'],
            'tags'       => ['nullable', 'array'],
            'tags.*'     => ['integer', 'exists:tags,id'],
        ];
    }
}
</code></pre>

Now type-hint the Form Request in your controller method instead of `Request`:

<pre><code>
use App\Http\Requests\StorePostRequest;

class PostController extends Controller
{
    public function store(StorePostRequest $request): RedirectResponse
    {
        $post = Post::create($request->validated());

        return redirect()->route('posts.show', $post);
    }
}
</code></pre>

Laravel resolves the Form Request through the service container, runs `authorize()`, and runs `rules()` — all before your method is called. If authorization fails, a 403 is returned automatically. If validation fails, the user is redirected back with errors. By the time your method body runs, the data is already validated and you can call `$request->validated()` with confidence.

## Customizing Validation Messages

The default error messages are usually fine, but sometimes you want something more specific. Add a `messages()` method:

<pre><code>
public function messages(): array
{
    return [
        'slug.unique'   => 'That slug is already taken. Choose a different one.',
        'status.in'     => 'Status must be either "draft" or "published".',
        'tags.*.exists' => 'One or more selected tags do not exist.',
    ];
}
</code></pre>

You can also override the field labels shown in default messages using `attributes()`:

<pre><code>
public function attributes(): array
{
    return [
        'body' => 'post body',
    ];
}
</code></pre>

With that in place, a default required message would read "The post body field is required" instead of "The body field is required" — small touch, but it matters when users see it.

## Preparing Data Before Validation

Sometimes you need to massage incoming data before the rules run. The `prepareForValidation()` method is the right hook for that:

<pre><code>
protected function prepareForValidation(): void
{
    $this->merge([
        'slug' => str($this->title)->slug()->value(),
    ]);
}
</code></pre>

This runs before `rules()`, so the auto-generated slug will be present when the `unique` check runs. It keeps that transformation logic inside the request class where it belongs, rather than buried in the controller.

## Using validated() vs. safe()

`$request->validated()` returns only the fields that passed validation — nothing that wasn't in your `rules()` array. This is a great habit to lean on because it makes mass assignment safe by default.

If you need just a subset of the validated data, `$request->safe()->only([...])` and `$request->safe()->except([...])` let you be selective:

<pre><code>
$postData = $request->safe()->only(['title', 'slug', 'body', 'status']);
$tagIds   = $request->safe()->only(['tags'])['tags'] ?? [];
</code></pre>

## Why Bother?

Once you start using Form Requests consistently, the benefits compound:

<ul>
    <li><strong>Thin controllers</strong> — every `store` or `update` method becomes a handful of lines focused on orchestration, not housekeeping.</li>
    <li><strong>Testable rules</strong> — you can write unit tests directly against a Form Request class without spinning up a full controller test.</li>
    <li><strong>One place to look</strong> — when a validation rule needs to change, you know exactly where it lives.</li>
    <li><strong>Authorization in context</strong> — the `authorize()` method has access to the request, the authenticated user, and the route model binding, so you can write precise access checks right alongside the rules they protect.</li>
</ul>

## Final Thoughts

Form Requests are one of those Laravel features that feel a little ceremonial the first time you create one — it's a whole new file for what used to be an inline array. But the payoff shows up quickly. Controllers stay readable, rules are easy to find and test, and the framework handles all the boilerplate around returning validation errors and authorization failures.

If your controllers have validation logic scattered through them, pick the busiest one and extract its rules into a Form Request. It'll take a few minutes and the controller will immediately look the way a controller is supposed to look.