<?php

use App\Models\User;
use Filament\Facades\Filament;

test('only the configured owner can access the admin panel', function () {
    config()->set('admin.allowed_emails', ['owner@example.com']);
    $panel = Filament::getPanel('admin');

    expect(User::factory()->make(['email' => 'owner@example.com'])->canAccessPanel($panel))->toBeTrue()
        ->and(User::factory()->make(['email' => 'someone@example.com'])->canAccessPanel($panel))->toBeFalse();
});

test('the admin panel requires authenticator app multi factor authentication', function () {
    $panel = Filament::getPanel('admin');

    expect($panel->hasMultiFactorAuthentication())->toBeTrue()
        ->and($panel->isMultiFactorAuthenticationRequired())->toBeTrue()
        ->and($panel->hasProfile())->toBeTrue()
        ->and($panel->arePasswordsRevealable())->toBeFalse();
});
