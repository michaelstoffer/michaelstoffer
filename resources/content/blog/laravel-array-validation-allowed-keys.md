---
title: "Laravel Array Validation: Reject Keys You Never Meant to Accept"
excerpt: A passing array rule can preserve unexpected input. Define allowed keys, validate their values, and test the boundary before saving nested settings.
cover: /media/blog/laravel-array-validation-allowed-keys/cover.webp
published_at: "2026-11-03 08:00:00 -0500"
modified_at: "2026-11-03 08:00:00 -0500"
status: published
tags: ["Laravel", "PHP", "Security", "Testing"]
---

A settings form offers a timezone and an email digest preference. The backend checks that `settings` is an array, calls `validated()`, and saves the result. What happens if the request also contains `settings.can_manage_billing`?

The answer depends on the rules, not on which controls the form displays. Nested input deserves an explicit contract: which keys may exist, which values they may contain, and who may change them.

This is a useful review during a dependency upgrade, when validation assumptions deserve another look. The examples below were checked against Laravel 12.70.2, the version used by this site. They describe existing behavior, not a newly disclosed vulnerability.

## An array rule is not a schema

Start with this deliberately incomplete validator. You can run these examples inside a bootstrapped Laravel application:

```php
use Illuminate\Support\Facades\Validator;

$input = [
    'settings' => [
        'timezone' => 'America/New_York',
        'email_digest' => true,
        'can_manage_billing' => true,
    ],
];

$loose = Validator::make($input, [
    'settings' => ['required', 'array'],
]);

$validated = $loose->validated();
// $validated['settings']['can_manage_billing'] is true.
```

That result is reasonable once you read the rule literally: `settings` must be an array. It says nothing about its contents. Calling the result “validated” does not make every nested key suitable for storage.

Laravel's [array validation documentation](https://laravel.com/docs/12.x/validation#validating-arrays) specifically recommends declaring allowed keys. A plain array rule is useful for genuinely open-ended data, but preferences with a fixed set of fields are a different contract.

## Filtering and rejection answer different questions

There is a subtle detail here. With the default validation factory in the installed Laravel version, adding rules for `settings.timezone` and `settings.email_digest` alongside the bare parent `array` rule filters the unvalidated billing key from `validated()`. It does **not** fail validation merely because that key was submitted.

You can inspect that distinction in Laravel's [validation factory](https://github.com/laravel/framework/blob/v12.70.2/src/Illuminate/Validation/Factory.php) and [`validated()` implementation](https://github.com/laravel/framework/blob/v12.70.2/src/Illuminate/Validation/Validator.php). Applications can change the factory's behavior, so test the configuration you actually ship.

Filtering can be a deliberate compatibility choice. Rejection is useful when unexpected keys indicate a broken client or an attempt to submit fields outside the form's contract. For this preferences endpoint, choose rejection:

```php
$rules = [
    'settings' => ['required', 'array:timezone,email_digest'],
    'settings.timezone' => ['required', 'timezone'],
    'settings.email_digest' => ['required', 'boolean'],
];

$strict = Validator::make($input, $rules);

$strict->fails(); // true
$strict->errors()->has('settings'); // true
```

The parent rule limits the key set. The child rules check values and presence. Listing a key in `array:timezone,email_digest` does not itself require that key to exist; the child `required` rules do that here. Laravel also has a separate [`required_array_keys` rule](https://laravel.com/docs/12.x/validation#rule-required-array-keys) for checking presence, which is not an unexpected-key filter.

Put these rules in a Form Request's `rules()` method when wiring the endpoint. [Form Requests keep that contract close to request authorization](/blog/keeping-controllers-clean-with-laravel-form-requests), but `validated()` is not a substitute for either a complete shape or an access check.

## Keep the write boundary explicit

After successful validation, map the accepted values into the operation you intend to perform:

```php
$accepted = Validator::make([
    'settings' => [
        'timezone' => 'America/New_York',
        'email_digest' => true,
    ],
], $rules)->validated();

$preferences = [
    'timezone' => $accepted['settings']['timezone'],
    'email_digest' => (bool) $accepted['settings']['email_digest'],
];
```

This makes the writable fields visible to a reviewer. The cast also makes the digest value a PHP boolean; a validation rule checks accepted input without promising to normalize its type.

If these fields live inside a JSON column that also contains server-managed settings, decide how to update just the preferences. Replacing the entire column with this small array could erase unrelated settings. Keep authorization-sensitive flags outside this form's write operation.

[OWASP's mass assignment guidance](https://cheatsheetseries.owasp.org/cheatsheets/Mass_Assignment_Cheat_Sheet.html) supports explicit writable-field lists or dedicated input objects. These controls still need authorization. Eloquent's [mass assignment protection](https://laravel.com/docs/12.x/eloquent#mass-assignment) is another boundary to configure, rather than a reason to accept an undefined request shape. Direct property assignment also needs a deliberate choice of values.

## Test the extra key, not just the happy path

Place this Pest test in a Laravel application's `tests/Feature` directory. It exercises the validator without requiring a database:

```php
use Illuminate\Support\Facades\Validator;

it('rejects unexpected preference keys and accepts the intended shape', function () {
    $rules = [
        'settings' => ['required', 'array:timezone,email_digest'],
        'settings.timezone' => ['required', 'timezone'],
        'settings.email_digest' => ['required', 'boolean'],
    ];

    $settings = [
        'timezone' => 'America/New_York',
        'email_digest' => true,
    ];

    $valid = Validator::make(['settings' => $settings], $rules);
    expect($valid->validated())->toBe(['settings' => $settings]);

    $settings['can_manage_billing'] = true;
    $invalid = Validator::make(['settings' => $settings], $rules);

    expect($invalid->fails())->toBeTrue();
    expect($invalid->errors()->has('settings'))->toBeTrue();
});
```

At the HTTP layer, add a separate test proving that a rejected update leaves stored settings unchanged, plus authorization tests for the target account. For session-backed forms, assert the validation redirect and session errors; for JSON requests, assert the validation error response your endpoint promises.

For a practical review, find one writable nested array and follow it from request rules to persistence. A useful contract should let you point to the accepted keys, their value rules, the authorization decision, and a test that submits one extra key. That is a much stronger boundary than assuming a form will only send what it renders.
