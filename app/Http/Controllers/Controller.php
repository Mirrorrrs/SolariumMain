<?php

namespace App\Http\Controllers;

use App\Http\Resources\Core\GetUserResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getUser(Request $request)
    {
        $user = $request->user();
        $class = $user->guarded_class;
        $user = collect($user);
        $user->put("guarded_class",$class);
        return response()->json(new GetUserResource(["user"=>$request->user()]));
    }
}
