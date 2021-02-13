<?php

namespace App\Http\Resources\Core;

use Illuminate\Http\Resources\Json\JsonResource;

class GetUserResource extends JsonResource
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
            "data"=>[
                "user"=>$this->resource["user"]

            ],
        ];
    }
}
