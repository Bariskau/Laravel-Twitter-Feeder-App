<?php

namespace App\Models;

use App\Enums\TweetStatus;
use App\Traits\Filterable;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Tweet extends Model implements HasMedia
{
    use HasFactory, HasUuid, InteractsWithMedia, Filterable;

    const MEDIA_COLLECTION = 'tweet-media';


    const USER_ID = "user_id";
    const UUID = "uuid";
    const TWEET_DATE = "tweet_date";
    const TWEET_ID = "tweet_id";
    const TWEET = "tweet";
    const STATUS = "status";

    /**
     * @var string[]
     */
    protected $fillable = [
        self::USER_ID,
        self::TWEET_DATE,
        self::TWEET_ID,
        self::TWEET,
        self::STATUS
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        self::TWEET_DATE => 'datetime'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::MEDIA_COLLECTION);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopePassive($query)
    {
        return $query->where(self::STATUS, TweetStatus::passive());
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where(self::STATUS, TweetStatus::active());
    }
}
