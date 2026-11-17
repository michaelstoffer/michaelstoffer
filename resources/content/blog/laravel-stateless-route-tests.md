---
title: "Test Your Stateless Routes After a Laravel Upgrade"
excerpt: A public page can return 200 while quietly starting a session. Test middleware, cookies, and the stateful routes you still need after a Laravel upgrade.
cover: /media/blog/laravel-stateless-route-tests/cover.webp
published_at: "2026-11-18 08:00:00 -0500"
modified_at: "2026-11-18 08:00:00 -0500"
status: published
tags: ["Laravel", "PHP", "Testing", "Maintainability"]
---

A public page returns `200 OK`. Its content looks right. The test suite passes. Yet a framework upgrade can still change an important part of the response: whether the request starts a session or sends cookies.

If a page is deliberately anonymous, those side effects deserve their own tests. A status-code assertion cannot tell you whether the middleware contract survived an upgrade.

This site's recent move to Laravel 13 makes that a useful boundary to examine. The examples below were checked against Laravel 13.42.0 on PHP 8.4 and 8.5. They demonstrate a small, public GET endpoint alongside a contact page that intentionally keeps its session. They do not establish a performance improvement or a framework vulnerability.

## Define what stateless means for this route

For this example, `/public-status` has three requirements: it returns the same JSON to every visitor, it does not use Laravel's session, and it does not issue cookies. A contact form has a different contract: it needs session-backed validation feedback and request-forgery protection.

Keep that distinction local to the routes. Removing session middleware from the entire application would also change forms, authentication, and other flows that rely on it.

In Laravel's [default middleware groups](https://laravel.com/docs/13.x/middleware#laravels-default-middleware-groups), `web` includes session startup, shared validation errors, and request-forgery protection. A GET route in `routes/web.php` inherits that group; being read-only does not opt it out.

The installed classes matter during an upgrade. In Laravel 13.42.0, that protection entry is `Illuminate\Foundation\Http\Middleware\PreventRequestForgery`. Check the actual group before copying an exclusion list from an older application.

## Remove only the dependencies this endpoint does not need

Put this example in `routes/web.php`:

```php
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;

Route::get('/public-status', function () {
    return response()->json(['status' => 'ok']);
})->withoutMiddleware([
    StartSession::class,
    ShareErrorsFromSession::class,
    PreventRequestForgery::class,
]);
```

These exclusions work together. `StartSession` attaches and saves a session. `ShareErrorsFromSession` reads that session to share validation errors with views. The [pinned request-forgery middleware](https://github.com/laravel/framework/blob/v13.42.0/src/Illuminate/Foundation/Http/Middleware/PreventRequestForgery.php) can add an `XSRF-TOKEN` cookie even when it allows the request through because it is a GET. Its response path still expects session data.

That is why “GET requests do not need a CSRF token” and “this request has no session side effects” are different statements.

This exclusion belongs only on an endpoint that needs none of those facilities. Keep protection on browser forms and authenticated writes. If a public page later gains personalized content or session-backed feedback, revisit the contract rather than preserving the exclusion by habit. Laravel's [CSRF documentation](https://laravel.com/docs/13.x/csrf) explains the protection used by stateful routes.

Route exclusions also have a limit: [`withoutMiddleware()` removes route middleware, not global middleware](https://laravel.com/docs/13.x/middleware#excluding-middleware). Cookie encryption and queued-cookie handling remain in this example's web stack. Application code or packages could still queue cookies, so configuration inspection alone is not enough.

## Test the HTTP result with middleware enabled

Add this Pest test to `tests/Feature/StatelessRoutesTest.php` after registering the route above:

```php
it('serves public status without issuing cookies', function () {
    $response = $this->get('/public-status');

    $response->assertOk()->assertExactJson(['status' => 'ok']);
    $response->assertCookieMissing(config('session.cookie'));
    $response->assertCookieMissing('XSRF-TOKEN');

    expect($response->headers->getCookies())->toBeEmpty();
});
```

The named assertions explain likely regressions. The last assertion makes the broader no-cookie promise explicit, including cookies added by unrelated middleware. If your endpoint intentionally sets another cookie, narrow the contract and test it by name instead.

Do not call the test helper `$this->withoutMiddleware()` here. It would bypass the behavior under examination. The route's exclusion list is production configuration; disabling middleware in the test is a different operation.

Laravel documents [cookie assertions in HTTP tests](https://laravel.com/docs/13.x/http-tests#assert-cookie-missing). These requests run through the application without an external web server. They are useful for regression coverage, but they do not show what a reverse proxy or CDN adds to a deployed response.

## Check the resolved stack, too

No cookies is an observable result, but it does not prove that no session work occurred. Add a separate test of the configured route stack:

```php
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession;

it('keeps session startup out of the public status route', function () {
    $router = app('router');
    $route = $router->getRoutes()->match(
        Request::create('/public-status', 'GET')
    );

    expect($router->gatherRouteMiddleware($route))
        ->not->toContain(StartSession::class);
});
```

Resolving the stack applies the route's exclusions; reading the raw list of assigned groups would still show `web`. This assertion catches a configuration change even if the testing session driver differs from production. It covers route middleware, so also inspect any global middleware or application code that explicitly starts sessions.

For a deployed check, make an anonymous request without incoming cookies and inspect the response headers. Check both the origin and public edge when they can differ. Treat this as verification of that response, not proof about every authentication state or request path.

## Protect the route that should remain stateful

A useful regression test also guards the other side of the boundary. In this application, `/contact` is intentionally stateful:

```php
it('keeps the contact page session cookie', function () {
    $this->get('/contact')
        ->assertOk()
        ->assertCookie(config('session.cookie'));
});
```

Adapt that path to a real form in your application. This is a positive control: a broad change that strips sessions everywhere should fail it. It does not prove that forged submissions are rejected. Laravel's normal HTTP testing environment bypasses request-forgery verification, as the [middleware source](https://github.com/laravel/framework/blob/v13.42.0/src/Illuminate/Foundation/Http/Middleware/PreventRequestForgery.php) shows; test that security behavior separately under conditions that exercise it.

Cookie-free responses are also only one part of a cache policy. Review personalized content, cache directives, and representation differences before enabling shared caching. An Inertia navigation response and an initial HTML response need deliberate handling even when both are anonymous.

For the next framework upgrade, choose one deliberately stateless route and one deliberately stateful route. Assert their response behavior and inspect the resolved middleware where the contract depends on it. That gives the upgrade review something more precise than a green `200 OK` test.
