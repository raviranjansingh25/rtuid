<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "email" => $this->email,
            //"full_name" =>  $this->full_name,
            // "user_name" =>  $this->user_name,
            "name" =>  $this->name,
            "mobile_no" =>  $this->mobile_no,
            "country_code" =>  $this->country_code,
            "is_online" =>  $this->is_online,
            "profile_image" => $this->profile,
        ];
        //return parent::toArray($request);
    }
}
