<?php

/*
 * Copyright (C) 2018 city_matters. All rights reserved.
 */

namespace Tests\Unit;

use App\User;
use Tests\TestCase;

class CheckAdminTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAdminDashboard()
    {
        $user = factory(User::class)->create();
        $admin = factory(User::class)->create([
            'admin' => true,
        ]);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertSuccessful();

        $user->delete();
        $admin->delete();
    }
}
