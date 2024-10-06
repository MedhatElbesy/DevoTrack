<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            'id'       => $this->id,
            'title'    => $this->title,
            'content'  => $this->content,
            'category' => $this->category->name,
            'author'   => $this->author->name,
        ];
    }
}
