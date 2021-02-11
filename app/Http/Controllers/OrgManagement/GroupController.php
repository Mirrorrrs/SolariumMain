<?php

namespace App\Http\Controllers\OrgManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Core\AttachGroupRequest;
use App\Http\Requests\Core\CreateGroupRequest;
use App\Http\Resources\Core\ErrorResource;
use App\Http\Resources\Core\GroupResource;
use App\Models\Group;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;

class GroupController extends Controller
{
    public function store(CreateGroupRequest $request)
    {

        if (in_array($request->user()->role, ["org_holder,staff"])) {
            $binary_mask = 0;
            $permissios = collect(json_decode($request->get("permissions")));

            foreach ($permissios->keys() as $permission_context) {
                $perms = collect(collect(config("permissions"))->get($request->get($permission_context)));
                if ($perms->isNotEmpty()) {
                    foreach ($permissios->get($permission_context) as $permission) {
                        $binary_mask += 1 << $perms->get($permission);
                    }
                } else {
                    throw new HttpResponseException(response()->json(new ErrorResource(["permission.context.error"])));

                }

            }

            $group = Group::create([
                "name" => $request->get("name"),
                "organization_id" => $request->get("organization_id"),
                "human_name" => $request->get("human_name"),
                "description" => $request->get("description"),
                "permissions" => $binary_mask
            ]);

            return response()->json(new GroupResource($group), 200);
        } else {
            throw new HttpResponseException(response()->json(new ErrorResource(["no.permissions"])));
        }

    }

    public function attach(AttachGroupRequest $request)
    {
        $groups = [...json_decode($request->get("groups", "[]")), ...$request->get("group", [])];
        $users = [...json_decode($request->get("users", "[]")), ...$request->get("user", [])];
        $users = User::whereIn("id", $users)->get();
        $groups = Group::whereIn("id", $groups)->get("id");

        if ($groups->isNotEmpty() && $users->isNotEmpty())
            $users->each(fn($user) => $user->groups()->syncWithoutDetaching($groups));
        else throw new HttpResponseException(response()->json(new ErrorResource(["no_matching_groups_or_users"])));

        return response()->json("ok", 200);
    }
}
