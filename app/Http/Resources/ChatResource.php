<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;

class ChatResource extends JsonResource
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
            'id' => $this->id,
            "convenience_id" => $this->convenience_id,
            "chat_user_id" => $this->chat_user_id,
            "from_id" => $this->from_id,
            "to_id" => $this->to_id,
            "replay_id" => $this->replay_id,
            "message" => $this->message,
            "file" => $this->file,
            "file_type" => $this->file_type,
            "is_read" => $this->is_read,
            "date" => $this->date,
            "time" => $this->time,
            "to_time" => $this->to_current_time,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "sender"  => new UserResource($this->sender),
            "receiver" =>  new UserResource($this->receiver),

        ];
        //return parent::toArray($request);
    }
}
