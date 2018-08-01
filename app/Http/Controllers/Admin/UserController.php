<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Invite;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\In;

class UserController extends Controller
{
    public function invites() {
        $invites = Invite::paginate(50);
        return view('admin.invites', [
            'invites' => $invites,
        ]);
    }

    public function addInvites(Request $request) {
        if($request->has('code'))
        {
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
