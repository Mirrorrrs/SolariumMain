<?php

namespace App\Http\Resources\Core;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "fullname"=>$this->resource["lastname"]." ".$this->resource["firstname"]." ".$this->resource["middlename"],
            "group" => $this->resource["group"],
            "group_number" => $this->resource["subgroup"],
            "username" => $this->resource["username"],
        ];
    }
}
