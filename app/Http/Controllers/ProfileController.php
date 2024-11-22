<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function verify()
    {

        $user = Auth::user();
        return view('profile.verify', compact('user'));

    }

    public function verifyPost(Request $request)
    {

        $user = auth()->user();

        if(isset($_GET['action']) && $_GET['action'] == 'code'){

            $this->validate($request, [
                'code' => 'required|numeric|digits:6|exists:users,verify_code,id,'.$user->id,
            ],[
                'code.exists' => "Invalid code, please try again!"
            ]);

            $user->verify_status = 1;
            $user->save();
            return redirect()->route('dashboard');

        } else {

            $this->validate($request, [
                'phone' => 'required|numeric|digits_between:10,11',
            ]);

            $user->phone = $request['phone'];
            $random_int = rand(0, 999999);
            $random_str = str_pad($random_int, 6, '0', STR_PAD_LEFT);
            $user->verify_code = $random_str;
            $user->save();

            //send whatsapp here
            $data = [
                'phone_number' => '6'.$user->phone,
                'message' => 'Your verification code is '.$user->verify_code.' thank you!',
            ];

            $response = \Illuminate\Support\Facades\Http::accept('application/json')
                ->withToken('1065839fd4b7422efa589ab61913d11bded1195a6ea657b7bfdfa68ce81d93db')
                ->post('https://onsend.io/api/v1/send', $data);

            return redirect()->route('verify_first', ['action' => 'code']);

        }

        

    }
}
