<?php

/*
 * Copyright (C) 2018 city_matters. All rights reserved.
 */

namespace Tests\Unit;

use App\User;
use App\Invite;
use Tests\TestCase;
use App\Organization;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class UserControllerTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * Testing the addOrganizations() method of the Admin\UserController.
     *
     * @return void
     */
    public function testAddOrganizations()
    {
        $admin = factory(User::class)->create([
            'admin' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.organizations.add'))
            ->assertStatus(200);

        $organizationName = 'Test Organization';
        $organizationSlug = 'tst';

        // adding organization
        $this->actingAs($admin)
            ->post(route('admin.organizations.add'), [
                'organizationName' => $organizationName,
                'organizationSlug' => $organizationSlug,
            ])
            ->assertRedirect(route('admin.organizations'));

        $org = Organization::where('name', $organizationName)
            ->where('slug', $organizationSlug)
            ->first();

        $this->assertEquals($organizationName, $org->name);
        $this->assertEquals($organizationSlug, $org->slug);

        // try to add it again, it should fail and redirect back
        $this->actingAs($admin)
            ->post(route('admin.organizations.add'), [
                'organizationName' => $organizationName,
                'organizationSlug' => $organizationSlug,
            ])
            ->assertRedirect(route('admin.organizations.add'));

        $org->delete();
        $admin->delete();
    }

    /**
     * Testing the addInvites() method of the Admin\UserController.
     *
     * @return void
     */
    public function testAddInvites()
    {
        $admin = factory(User::class)->create([
            'admin' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.invites.add'))
            ->assertStatus(200);

        $code = 'foobar123';

        // adding organization
        $this->actingAs($admin)
            ->post(route('admin.invites.add'), [
                'code' => $code,
                'forever' => true,
            ])
            ->assertRedirect(route('admin.invites'));

        $invite = Invite::where('code', $code)
            ->first();

        $this->assertEquals($code, $invite->code);

        // try to add it again, it should fail and redirect back
        $this->actingAs($admin)
            ->post(route('admin.invites.add'), [
                'code' => $code,
            ])
            ->assertRedirect(route('admin.invites.add'));

        $invite->delete();
        $admin->delete();
    }
}
