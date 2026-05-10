---
title: "Cleaner Queries with Laravel Eloquent Local Scopes"
excerpt: Local scopes let you name and reuse common query constraints directly on your Eloquent models, keeping your controllers thin and your queries easy to read.
cover: /media/blog/cleaner-queries-with-laravel-eloquent-local-scopes/cover.webp
published_at: "2026-05-01 08:00:00 -0400"
modified_at: "2026-05-01 08:00:00 -0400"
tags: ["Laravel", "PHP", "Development"]
---

If you've worked on a Laravel project for any length of time, you've probably written the same query constraint in a handful of different places. Something like `->where('status', 'published')` scattered across multiple controllers, or `->where('is_active', true)` repeated every time you need to filter active users. It works, but it's fragile — if that column name changes or the condition gets more complex, you're hunting down every occurrence.

Local scopes are the solution. They let you define a named, reusable query constraint directly on your Eloquent model, then call it anywhere you build a query. The result is cleaner controllers, self-documenting code, and a single place to change things when requirements shift.

## Defining a Local Scope

A local scope is just a method on your Eloquent model that starts with the word `scope` followed by a descriptive name. It receives a query builder instance and returns it with your constraints applied.

Here's a simple example on a `Post` model:

<pre><code>
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    public function scopeRecent(Builder $query): Builder
    {
        return $query->orderByDesc('published_at');
    }
}
</code></pre>

To use a scope, drop the `scope` prefix and call it like a regular method on your model:

<pre><code>
// Without scopes
$posts = Post::where('status', 'published')
    ->orderByDesc('published_at')
    ->get();

// With scopes
$posts = Post::published()->recent()->get();
</code></pre>

The second version reads almost like a sentence. Anyone on your team can understand what's being queried without knowing the underlying column names or values.

## Scopes With Parameters

Scopes aren't limited to static conditions. You can pass arguments after the query builder parameter to make them flexible:

<pre><code>
public function scopeOfStatus(Builder $query, string $status): Builder
{
    return $query->where('status', $status);
}

public function scopePublishedBefore(Builder $query, string $date): Builder
{
    return $query->where('published_at', '<=', $date);
}
</code></pre>

Then call them with the argument directly:

<pre><code>
$posts = Post::ofStatus('archived')->get();

$oldPosts = Post::published()->publishedBefore('2025-01-01')->get();
</code></pre>

This gives you the flexibility of a dynamic query while keeping the intent obvious at the call site.

## A Real-World Example

Here's a more complete model you might see in a production app — a `User` model with a handful of scopes that get used across controllers, jobs, and notifications:

<pre><code>
class User extends Authenticatable
{
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeVerified(Builder $query): Builder
    {
        return $query->whereNotNull('email_verified_at');
    }

    public function scopeAdmins(Builder $query): Builder
    {
        return $query->where('role', 'admin');
    }

    public function scopeWithSubscription(Builder $query, string $plan): Builder
    {
        return $query->whereHas('subscription', function (Builder $q) use ($plan) {
            $q->where('plan', $plan)->where('status', 'active');
        });
    }
}
</code></pre>

Now in a controller or command, the query is expressive and compact:

<pre><code>
// Get all verified active users on the pro plan
$users = User::active()->verified()->withSubscription('pro')->get();

// Get all active admins
$admins = User::active()->admins()->get();
</code></pre>

Each scope handles one concern. They chain cleanly and combine in ways you didn't have to plan for up front.

## Why This Beats Repeating Raw Constraints

The practical benefits stack up quickly when you adopt scopes consistently across your models:

<ul>
    <li><strong>Single source of truth</strong> — if the condition needs to change, you change it in one place on the model, not everywhere it was copied.</li>
    <li><strong>Readable queries</strong> — method names like <code>active()</code>, <code>verified()</code>, and <code>recent()</code> communicate intent better than raw <code>where()</code> chains.</li>
    <li><strong>Composable</strong> — scopes chain together, so you build complex queries from simple, testable pieces.</li>
    <li><strong>Testable in isolation</strong> — you can write a feature test that exercises a specific scope directly without building a full controller test around it.</li>
</ul>

## One Thing to Watch Out For

Local scopes only apply when you explicitly call them. If you need a constraint applied to <strong>every</strong> query on a model automatically — like a soft-delete check or a tenant filter — that's a job for a global scope, which is a separate concept worth its own post. Local scopes are opt-in, which is actually what makes them so useful: you get the constraint exactly when you want it, and nothing you didn't ask for.

## Final Thoughts

Local scopes are one of those small Eloquent features that have an outsized impact on code quality over time. They don't require a refactor or a new package — just a method on your model and a convention. Once you start writing them, you'll notice your controllers getting shorter and your queries telling a clearer story.

If you have a model with a `where` clause you've written more than twice, that's the one to convert first. It takes about thirty seconds, and you'll feel the difference immediately.