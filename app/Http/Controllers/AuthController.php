<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function discordLogin()
    {
        return Socialite::driver('discord')->setScopes(['identify'])->redirect();
    }

    public function discordRedirect()
    {
        $discordUser = Socialite::driver('discord')->stateless()->user();

        $authUser = User::find(Auth::user()->id);
        $authUser->discord_nickname = $discordUser->nickname;
        $authUser->save();

        return '<script>window.close()</script>';
    }
    public function twitchLogin()
    {
        return Socialite::driver('twitch')->redirect();
    }

    public function twitchRedirect(Request $request)
    {
        $userAuth = Socialite::driver('twitch')->stateless()->user();
        /**
         * @var User $newUser
         */
        $newUser = User::firstOrCreate([
            'email' => $userAuth->email,
        ], [
            'email' => $userAuth->email,
            'twitch_nickname' => $userAuth->name,
            'avatar' => $userAuth->avatar,
        ]);

        Auth::login($newUser);

        return '<script>window.close()</script>';
    }

    public function user(): Authenticatable|JsonResponse|null
    {
        if (Auth::check()) {
            return Auth::user();
        } else {
            return response()->json([
                'No user logged in.'
            ]);
        }
    }
}
