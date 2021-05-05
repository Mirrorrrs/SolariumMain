<?php

namespace App\Http\Controllers\OrgManagement;

use App\Http\Controllers\Controller;
use App\Http\Resources\Core\ErrorResource;
use App\Http\Resources\Core\SchoolClassResource;
use App\Models\Group;
use App\Models\SchoolClass;
use http\Env\Response;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function class_get(Request $request)
    {
        if(collect($request)->has("school_class_id")){
            $id=$request->school_class_id;
        }
        $user = $request->user();
        $perms = Group::checkPermissions($user->groups()->first()->permissions);
        if ($perms->contains("teacher")) {

                if((isset($id) && $user->school_class_id == $id)||(!isset($id))){
                    $class = $user->guarded_class;
                    $dt = collect();
                    $dt->put("students",$class->students);
                    $dt->put("class_teacher", $class->class_teacher);
                    return response()->json(new SchoolClassResource($dt));
                }else{
                    return response()->json(new ErrorResource("no.access"),413);
                }


        }
        if ($perms->intersect(collect(["admin", "med_stuff"]))->count() >= 1) {
            if(isset($id)){
                $class = SchoolClass::find($id);
                $dt = collect();
                $dt->students = $class->students;
                $dt->class_teacher=$class->class_teacher;
                return response()->json(new SchoolClassResource($dt));
            }else{
                return response()->json(new ErrorResource("no.information.given"),413);
            }
        }


    }
}
