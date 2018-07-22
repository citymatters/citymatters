<?php

namespace App\Http\Controllers;

use App\Invite;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\In;

class UserController extends Controller
{
    public function adminInvites() {
        $invites = Invite::paginate(50);
        return view('admin.invites', [
            'invites' => $invites,
        ]);
    }

    public function adminAddInvites(Request $request) {
        if($request->has('code'))
        {
            $invite = new Invite();
            $invite->code = $request->get('code');
            $invite->save();
            return redirect(route('admin.invites'));
        }
        return view('admin.invitesadd');
    }
}
