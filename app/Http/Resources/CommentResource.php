<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            'id'      => $this->id,
            'post_id' => $this->post_id,
            'author'  => $this->user->name,
            'content' => $this->content,
        ];
    }
}
