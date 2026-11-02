<?php

test('blog content passes the repository validation command', function () {
    $this->artisan('blog:validate')
        ->expectsOutputToContain('blog posts validated successfully')
        ->assertSuccessful();
});
