<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\ChatResource;
class ChatCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => ChatResource::collection($this->collection),
            'current_page'=> $this->currentPage(),
            "from"=> $this->firstItem(),
            "last_page"=> $this->lastPage(),
            "links"=> $this->links(),
            "next_page_url"=> $this->nextPageUrl(),
            "per_page"=> $this->perPage(),
            "prev_page_url"=> $this->previousPageUrl(),
            "to"=> $this->lastItem(),
            "total"=> $this->count()
        ];
    
    }
}
