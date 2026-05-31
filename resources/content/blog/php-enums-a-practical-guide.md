---
title: "PHP Enums: A Practical Guide"
excerpt: PHP 8.1 introduced native enums, giving you a proper type for a fixed set of values — replacing scattered string constants with something PHP can actually enforce.
cover: /media/blog/php-enums-a-practical-guide/cover.webp
published_at: "2026-05-18 08:00:00 -0400"
modified_at: "2026-05-18 08:00:00 -0400"
tags: ["PHP", "Laravel", "Development"]
---

Before PHP 8.1, if you wanted to represent a fixed set of values — statuses, roles, categories, payment states — you'd typically reach for a class full of constants or a string you just had to know. It worked, but it was fragile. Pass the wrong string, forget what `2` maps to, or make a typo, and you won't hear about it until runtime.

PHP 8.1 changed that with native enum support. Enums give you a proper type for a fixed set of values, and PHP will enforce it at the language level. They're one of those features that, once you start using them, you'll wonder how you managed without them.

## Pure Enums

The simplest enum is a pure enum — no backing value, just a set of named cases:

<pre><code>
enum Status
{
    case Active;
    case Inactive;
    case Suspended;
}
</code></pre>

You reference a case like a static property and type-hint it just like any other class:

<pre><code>
function updateUser(User $user, Status $status): void
{
    $user->status = $status;
    $user->save();
}
</code></pre>

Now PHP guarantees that `$status` is one of the three defined cases. Pass anything else and you get a type error — caught before it becomes a runtime bug in production.

## Backed Enums: Storing a Value

Pure enums are great, but you often need to persist an enum as a string or integer in a database. That's where backed enums come in. Add <code>: string</code> or <code>: int</code> to the declaration and assign each case a value:

<pre><code>
enum Status: string
{
    case Active    = 'active';
    case Inactive  = 'inactive';
    case Suspended = 'suspended';
}
</code></pre>

Each case now has a <code>value</code> property, and you can convert in both directions:

<pre><code>
$status = Status::Active;
echo $status->value; // 'active'

$status = Status::from('active');   // Returns Status::Active
$status = Status::tryFrom('ghost'); // Returns null instead of throwing
</code></pre>

<code>tryFrom()</code> is the safer choice when the value comes from an external source — a database column, API response, or form input — where you can't guarantee it's valid.

## Enums in Laravel

Laravel has had first-class enum support since version 9. You can cast a model attribute directly to an enum:

<pre><code>
class User extends Model
{
    protected $casts = [
        'status' => Status::class,
    ];
}
</code></pre>

After that, <code>$user->status</code> is always a <code>Status</code> instance, not a raw string. No more loose comparisons scattered across your codebase:

<pre><code>
// Before
if ($user->status === 'active') { ... }

// After
if ($user->status === Status::Active) { ... }
</code></pre>

Laravel also supports enum cases in validation rules. Paired with <code>Rule::in()</code>, you get a clean way to validate incoming values against your enum without duplicating the list:

<pre><code>
'status' => ['required', Rule::in(Status::cases())],
</code></pre>

Laravel knows how to handle enum cases in validation — no extra mapping needed.

## Adding Methods to Enums

Enums can have methods, which is where they really start to earn their keep. Display logic, business rules, anything that belongs to the concept can live directly on the enum:

<pre><code>
enum Status: string
{
    case Active    = 'active';
    case Inactive  = 'inactive';
    case Suspended = 'suspended';

    public function label(): string
    {
        return match($this) {
            Status::Active    => 'Active',
            Status::Inactive  => 'Inactive',
            Status::Suspended => 'Suspended',
        };
    }

    public function canLogin(): bool
    {
        return $this === Status::Active;
    }
}
</code></pre>

Now <code>$user->status->canLogin()</code> lives exactly where it should — on the type that owns it — instead of in a helper, a service, or a comment somewhere that might get out of date.

## The cases() Method

Every enum ships with a static <code>cases()</code> method that returns all defined cases as an array. This is useful for dropdowns, seed data, or anywhere you need to enumerate every possible value:

<pre><code>
Status::cases();
// [Status::Active, Status::Inactive, Status::Suspended]

// For backed enums, extract the raw values:
$values = array_map(fn($case) => $case->value, Status::cases());
// ['active', 'inactive', 'suspended']
</code></pre>

## Why This Beats Class Constants

Class constants were the old way, and they're still common in legacy codebases:

<pre><code>
class Status
{
    const ACTIVE    = 'active';
    const INACTIVE  = 'inactive';
    const SUSPENDED = 'suspended';
}
</code></pre>

The problem is that <code>Status::ACTIVE</code> is just a string. You can pass it anywhere a string is accepted, mix it up with a different set of constants, and PHP won't complain. Enums are proper types — the language enforces them, IDEs understand them, and your code communicates intent clearly.

<ul>
    <li><strong>Type safety</strong> — PHP enforces valid values at the language level, not at runtime.</li>
    <li><strong>IDE support</strong> — Autocomplete, go-to-definition, and refactoring all work correctly with enum cases.</li>
    <li><strong>Methods</strong> — Logic that belongs to the concept lives on the type itself, not scattered elsewhere.</li>
    <li><strong>Static analysis</strong> — Tools like PHPStan and Psalm can verify that <code>match</code> expressions on enums are exhaustive, catching missed cases before they ship.</li>
</ul>

## Where to Start

If you're working on a Laravel project with string statuses, roles, or types stored in your database, those are the perfect starting point. Define a backed enum, add a cast to your model, and update the places that compare or set those values. The refactor is usually small and the payoff is immediate: fewer places where a typo can sneak through unnoticed, and a codebase that's much easier to follow.

Enums are available from PHP 8.1 onward. If your project is already there, there's no reason not to start today.