<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;
class UserChatResource extends JsonResource
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
                "convenience_id" => $this->convenience_id,
                "user_id" =>  $this->user_id,
                "get_user" => new UserResource($this->getUser),
        ];
        //return parent::toArray($request);
    }
}
