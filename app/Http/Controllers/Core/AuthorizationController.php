<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Http\Requests\Core\LoginRequest;
use App\Http\Resources\Core\ErrorResource;
use App\Http\Resources\Core\LoginResource;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Token;

class AuthorizationController extends Controller
{
    public function login(LoginRequest $request)
    {
        $login = explode("@", $request->get("login"));
        $org = Organization::whereNamespace($login[1])->with("users", function ($query) use ($login) {
            $query->where("username", $login[0]);
        })->first();
        $user = $org->users[0];
        if (Hash::check($request->get("password"),$user->password)) {
            $token = $user->createToken($request->get("agent"))->accessToken;
            $user = collect($user)->except(["created_at", "updated_at"])->toArray();
            return new LoginResource([
                "user" => $user,
                "token" => $token
            ]);
        } else {
            return response()->json(new ErrorResource("core.auth.wrong"),418);
        }


    }

    public function logout(Request $request)
    {
        $token = $request->bearerToken();
        User::revokeAccessAndRefreshTokens($token);

        return response()->json([
            "response" => [],
            "status" => "ok"
        ]);
    }

}
