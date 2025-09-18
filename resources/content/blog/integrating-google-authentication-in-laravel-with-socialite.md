---
title: Integrating Google Authentication in Laravel with Socialite and Google Client PHP Library
excerpt: Learn how to integrate Google authentication into your Laravel application using Laravel Socialite and the Google Client PHP Library.
cover: /media/blog/integrating-google-authentication-in-laravel-with-socialite/cover.webp
published_at: "2025-03-29 08:00:00 -0400"
updated_at: "2025-03-29 08:00:00 -0400"
tags: ["Laravel", "Development"]
---

Implementing Google authentication in your Laravel application can significantly enhance user experience by allowing users to log in using their existing Google accounts. This guide will walk you through integrating Google OAuth authentication using Laravel Socialite and extending its functionality with the Google Client PHP Library.

## Why Integrate Google Authentication?

Integrating Google authentication offers several benefits:

<ul>
    <li><strong>Convenience:</strong> Users can log in without creating a new account, reducing friction.</li>
    <li><strong>Security:</strong> Leveraging Google's robust authentication system enhances your application's security.</li>
    <li><strong>Access to Google Services:</strong> With user consent, your application can interact with Google services like Calendar and Drive.</li>
</ul>

## Setting Up Laravel Socialite

Laravel Socialite simplifies OAuth authentication with various providers, including Google. Here's how to set it up:

### 1. Install Laravel Socialite

Begin by installing Socialite via Composer:

<pre><code>composer require laravel/socialite</code></pre>

### 2. Configure Google Credentials

Add your Google OAuth credentials to the <code>config/services.php</code> file:

<pre><code>'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
],</code></pre>

Ensure these environment variables are set in your <code>.env</code> file:

<pre><code>GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT_URI=http://your-app-url.com/auth/callback</code></pre>

## Creating Google OAuth Credentials

To obtain the necessary credentials:

<ol>
    <li>Visit the <a href="https://console.cloud.google.com/">Google Cloud Console</a>.</li>
    <li>Navigate to "APIs & Services" > "Credentials".</li>
    <li>Set up your OAuth consent screen.</li>
    <li>Create new credentials and choose "OAuth client ID".</li>
    <li>Select "Web application" and configure the redirect URI to match <code>GOOGLE_REDIRECT_URI</code>.</li>
    <li>After creation, you'll receive a Client ID and Client Secret. Download the credentials as a JSON file and place it at <code>storage/app/private/google/oauth-credentials.json</code>.</li>
</ol>

## Implementing Authentication Routes

Define routes for redirecting users to Google's OAuth page and handling the callback:

<pre><code>use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Route;

Route::get('/auth/redirect', function () {
    return Socialite::driver('google')
        ->scopes(['https://www.googleapis.com/auth/calendar'])
        ->with(['prompt' => 'consent'])
        ->redirect();
});

Route::get('/auth/callback', function () {
    $googleUser = Socialite::driver('google')->user();

    // Store tokens securely
    Storage::disk('local')->put('google/oauth-token.json', $googleUser->token);
    if ($googleUser->refreshToken) {
        Storage::disk('local')->put('google/oauth-refresh-token.json', $googleUser->refreshToken);
    }

    // Authenticate user in your application
    // ...

    return redirect('/dashboard');
});</code></pre>

## Refreshing Tokens

To handle token expiration and refresh:

<pre><code>Route::get('/auth/refresh', function () {
    $refreshToken = Storage::disk('local')->get('google/oauth-refresh-token.json');
    $newTokens = Socialite::driver('google')->refreshToken($refreshToken);

    if ($newTokens->token) {
        Storage::disk('local')->put('google/oauth-token.json', $newTokens->token);
    }
    if ($newTokens->refreshToken) {
        Storage::disk('local')->put('google/oauth-refresh-token.json', $newTokens->refreshToken);
    }

    return redirect('/dashboard');
});</code></pre>

## Integrating Google Client PHP Library

To interact with Google services like Calendar:

### 1. Install the Google Client Library

<pre><code>composer require google/apiclient</code></pre>

### 2. Set Up the Google Client

<pre><code>use Google\Client;
use Google\Service\Calendar;

$client = new Client();
$client->setAuthConfig(storage_path('app/private/google/oauth-credentials.json'));
$client->addScope(Calendar::CALENDAR_READONLY);
$client->setAccessToken(Storage::disk('local')->get('google/oauth-token.json'));

if ($client->isAccessTokenExpired()) {
    $refreshToken = Storage::disk('local')->get('google/oauth-refresh-token.json');
    $client->fetchAccessTokenWithRefreshToken($refreshToken);
    Storage::disk('local')->put('google/oauth-token.json', $client->getAccessToken());
}</code></pre>

### 3. Access Google Services

<pre><code>$service = new Calendar($client);
$calendarList = $service->calendarList->listCalendarList();

foreach ($calendarList->getItems() as $calendar) {
    echo $calendar->getSummary();
}</code></pre>

## Conclusion

By integrating Laravel Socialite with the Google Client PHP Library, you can provide seamless Google authentication and access various Google services within your Laravel application. This approach enhances user experience and opens up possibilities for deeper integration with Google's ecosystem.
