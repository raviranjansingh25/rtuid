<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserChatResource;
class ThreadResource extends JsonResource
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
                //"ticket_id" => $this->ticket_id,
                //"ticket_type" =>  $this->ticket_type,
                "group_name" =>  $this->group_name,
                "group_image" =>  $this->group_image,
                
                "type" =>  $this->type,
                "last_message" =>  $this->last_message,
                "total_unread"=>$this->total_unread,
              //  "created_at" =>  $this->created_at,
                "updated_at" =>  $this->updated_at,
              //  "deleted_at" => $this->deleted_at,
                "chatuser"  => ($this->type=='SINGLE') ? UserChatResource::collection($this->chatuser) : null       
        ];
        //return parent::toArray($request);
    }
}
