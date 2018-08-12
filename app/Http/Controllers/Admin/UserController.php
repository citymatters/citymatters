<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Invite;
use App\Organization;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\In;

class UserController extends Controller
{
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

    public function addOrganizations(Request $request) {
        if($request->has('organizationName'))
        {
            if(Organization::where('name', $request->get('organizationName'))->first()
                || Organization::where('slug', $request->get('organizationSlug'))->first())
            {
                return redirect()->back();
            }

            $org = new Organization();
            $org->name = $request->get('organizationName');
            $org->slug = $request->get('organizationSlug');
            $org->save();
            return redirect(route('admin.organizations'));
        }
        return view('admin.organizationsadd');
    }

    public function invites() {
        $invites = Invite::paginate(50);
        return view('admin.invites', [
            'invites' => $invites,
        ]);
    }

    public function addInvites(Request $request) {
        if($request->has('code'))
        {
            if(Invite::where('code', $request->get('code'))->first())
            {
                return redirect()->back();
            }

            $invite = new Invite();
            $invite->code = $request->get('code');

            if($request->get('forever'))
            {
                $invite->valid_until = now()->addCenturies(10)->toDateTimeString();
            }
            $invite->save();
            return redirect(route('admin.invites'));
        }
        return view('admin.invitesadd');
    }

    public function deleteInvite($id) {
        $invite = Invite::find($id)->firstOrFail();
        if($invite)
        {
            $invite->delete();

        }
        return redirect()->back();
    }
}
