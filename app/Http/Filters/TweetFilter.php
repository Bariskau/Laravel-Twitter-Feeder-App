<?php

namespace App\Http\Filters;

use App\Enums\TweetStatus;
use App\Models\Tweet;
use Illuminate\Database\Eloquent\Builder;

class TweetFilter extends Filter
{

    /**
     * @param string|null $value
     * @return Builder
     */
    public function search(string $value = null): Builder
    {
        return $this->builder->where(Tweet::TWEET, 'ilike', '%' . $value . '%');
    }

    /**
     * @param string|null $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function type(string $value = null): Builder
    {
        switch ($value) {
            case TweetStatus::active:
                $query = $this->builder->{TweetStatus::active}();
                break;
            case TweetStatus::passive:
                $query = $this->builder->{TweetStatus::passive}();
                break;
            default:
                $query = $this->builder;
        }

        return $query;
    }

}
