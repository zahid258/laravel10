<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CustomerReviewResource;
class UserListResource extends JsonResource
{

    /**
     * Transform the resource into an array.     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'UserList'       => UserResource::collection($this->resource->getCollection()),
            'pagination' => [
                'total'       => $this->total(),
                'count'       => $this->count(),
                'perPage'     => $this->perPage(),
                'currentPage' => $this->currentPage(),
                'totalPages'  => $this->lastPage()
            ],
        ];
    }
}
