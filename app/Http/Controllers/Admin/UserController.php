<?php

/*
 * Copyright (C) 2018 city_matters. All rights reserved.
 */

namespace App\Http\Controllers\Admin;

use App\Invite;
use App\Organization;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function users()
    {
        $users = User::paginate(25);
        return view('admin.users', [
            'users' => $users,
            ]);
    }

    public function user($id)
    {
        $user = User::findOrFail($id);

        return view('admin.user', [
            'user' => $user,
        ]);
    }

    public function modifyUser(Request $request)
    {
        if ($request->has('organizationName')) {
            if (Organization::where('name', $request->get('organizationName'))->first()
                || Organization::where('slug', $request->get('organizationSlug'))->first()) {
                return redirect(route('admin.organizations.add'));
            }

            $org = new Organization();
            $org->name = $request->get('organizationName');
            $org->slug = $request->get('organizationSlug');
            $org->save();

            return redirect(route('admin.organizations'));
        }

        return redirect()->back();
    }

    public function organizations()
    {
        $organizations = Organization::paginate(25);

        return view('admin.organizations', [
            'organizations' => $organizations,
        ]);
    }

    public function organization($id)
    {
        $organization = Organization::findOrFail($id);

        return view('admin.organization', [
            'organization' => $organization,
        ]);
    }

    public function addOrganizations(Request $request)
    {
        if ($request->has('organizationName')) {
            if (Organization::where('name', $request->get('organizationName'))->first()
                || Organization::where('slug', $request->get('organizationSlug'))->first()) {
                return redirect(route('admin.organizations.add'));
            }

            $org = new Organization();
            $org->name = $request->get('organizationName');
            $org->slug = $request->get('organizationSlug');
            $org->save();

            return redirect(route('admin.organizations'));
        }

        return view('admin.organizationsadd');
    }

    public function invites()
    {
        $invites = Invite::paginate(50);

        return view('admin.invites', [
            'invites' => $invites,
        ]);
    }

    public function addInvites(Request $request)
    {
        if ($request->has('code')) {
            if (Invite::where('code', $request->get('code'))->first()) {
                return redirect(route('admin.invites.add'));
            }

            $invite = new Invite();
            $invite->code = $request->get('code');

            if ($request->get('forever')) {
                $invite->valid_until = now()->addCenturies(10)->toDateTimeString();
            }
            $invite->save();

            return redirect(route('admin.invites'));
        }

        return view('admin.invitesadd');
    }

    public function deleteInvite($id)
    {
        $invite = Invite::find($id)->firstOrFail();
        if ($invite) {
            $invite->delete();
        }

        return redirect()->back();
    }
}
