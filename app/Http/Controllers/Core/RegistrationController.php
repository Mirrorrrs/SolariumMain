<?php

namespace App\Http\Controllers\Core;

use App\Exports\Core\ExcelUserDataExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Core\RegistrationExcelRequest;
use App\Http\Requests\Core\RegistrationRequest;
use App\Http\Resources\Core\ErrorResource;
use App\Http\Resources\Core\UserResource;
use App\Imports\Core\ExcelUserDataImport;
use App\Models\User;
use App\Translit\Translit;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;


class RegistrationController extends Controller
{


    public function createUsername($row)
    {
        $counter = 0;
        do {
            switch ($counter) {
                case 0:
                    $login = strtoupper(Translit::translit($row->get("firstname"))[0]) . strtoupper(Translit::translit($row->get("middlename"))[0]) . ucfirst(Translit::translit($row->get("lastname")));
                    break;
                case 1:
                    $login = ucfirst(Translit::translit($row->get("firstname"))) . strtoupper(Translit::translit($row->get("middlename"))[0]) . ucfirst(Translit::translit($row->get("lastname")));
                    break;
                default:
                    $login = strtoupper(Translit::translit($row->get("firstname"))[0]) . strtoupper(Translit::translit($row->get("middlename"))[0]) . ucfirst(Translit::translit($row->get("lastname"))) . rand(1, 999);
                    break;
            }
            $counter++;
        } while (User::whereUsername($login)->exists());

        return $login;
    }

    public function storeExcel(RegistrationExcelRequest $request)
    {
        $data = Excel::toCollection(new ExcelUserDataImport(), $request->file('table'));
        $responseTable = collect();
        foreach ($data as $table) {
            $row_number = 1;
            foreach ($table->splice(1) as $row) {
                $row_number++;
                $policy = $row->get(12);
                if(!isset($policy)){
                     continue;
                }
                $group_group_num = collect(explode(".",$row->get(2)));
                $row = collect([
                    "firstname" => $row->get(4),
                    "middlename" => $row->get(5),
                    "lastname" => $row->get(3),
                    "group" => $group_group_num->get(0),
                    "group_number" => $group_group_num->get(1),
                    "policy"=>$row->get(12)
                ])->map(function ($el) {
                    return trim($el);
                });

                $validator = Validator::make($row->all(), [
                    "firstname" => ["required", "regex:/^[А-ЯЁ][а-яё]+$/ium"],
                    "middlename" => ["required", "regex:/^[А-ЯЁ][а-яё]+$/ium"],
                    "lastname" => ["required", "regex:/^[А-ЯЁ][а-яё]+$/ium"],
                    "group" => ["required", "regex:/^([1-9А-Я]|[1-9А-Я][0-9А-Я]){1,}$/ium"],
                    "group_number" => ["required", "regex:/^([1-9А-Я]|[1-9А-Я][0-9А-Я]){1,}$/ium"],
                ]);
                if ($validator->fails()) {
                    throw new HttpResponseException(response()->json(new ErrorResource(["bad.information.given:" . $row_number])));
                } else {
                    $unhashedPassword = Str::random(8);
                    $password = Hash::make($unhashedPassword);
                    $login = $this->createUsername($row);
                }

                User::createUserWithRelations($login,$password,$request->get("organization_id"),$row,$request->get("group_id"));

                $responseTable->add([
                    "userNames" => $row->get("lastname") . " " . $row->get("firstname") . " " . $row->get("middlename"),
                    "group" => (int)$row->get("group"),
                    "group_number" => (int)$row->get("group_number"),
                    "login" => $login, "password" => $unhashedPassword
                ]);
            };

            $sortedResponseTable = collect();
            $groups = $responseTable->sortBy("group")->pluck("group")->unique();
            foreach ($groups as $group) {
                $group = $responseTable->where("group", $group);
                $group_numbers = $group->pluck("group_number")->unique();
                $responseTable->where("groups", $group)->sortBy("group_number");
                foreach ($group_numbers as $group_number) {
                    $group_number_users = $group->where("group_number", $group_number)->sortBy("userNames");
                    $sortedResponseTable->add($group_number_users);
                }
            }

            $unique_name = Str::random();
            Excel::store(new ExcelUserDataExport($sortedResponseTable), "public/excel/$unique_name.xlsx", "local");
            return response()->json([
                "file" => route("apidl.excels", ["id" => $unique_name])
            ]);
        }
        return true;
    }

    public function store(RegistrationRequest $registrationRequest)
    {

        if(!empty($registrationRequest->get("custom_login")) && User::whereUsername($registrationRequest->get("custom_login"))->doesntExist()){
            $login = $registrationRequest->get("custom_login");
        }else{
            $login = $this->createUsername($registrationRequest->except("organization_id"));

        }
        $unhashedPassword = Str::random(8);
        $password = Hash::make($unhashedPassword);

        $user = User::createUserWithRelations($login,$password,$registrationRequest->get("organization_id"),collect($registrationRequest));
        return response()->json(new UserResource(collect($user)->union($user->info)->union($user->payUser)));
    }



}
