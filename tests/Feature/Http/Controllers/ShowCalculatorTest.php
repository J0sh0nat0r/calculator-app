<?php

declare(strict_types=1);

use Inertia\Testing\AssertableInertia as Assert;

it('renders calculator view', function (): void {
    $response = $this->get('/');

    $response
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page->component('Calculator'));
});
