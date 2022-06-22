<?php

namespace App\Http\Resources;

use App\Models\Tweet;
use Illuminate\Http\Resources\Json\JsonResource;

class TweetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->uuid,
            'tweet_date' => $this->tweet_date,
            'tweet_id'   => $this->tweet_id,
            'tweet'      => $this->tweet,
            'status'     => $this->status,
            'user'       => TweetUserDetailResource::make($this->whenLoaded('user')),
            'media'      => MediaResource::collection($this->getMedia(Tweet::MEDIA_COLLECTION))
        ];
    }
}
