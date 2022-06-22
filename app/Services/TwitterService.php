<?php

namespace App\Services;

use Atymic\Twitter\Exception\Request\NotFoundException;
use Atymic\Twitter\Facade\Twitter;
use Illuminate\Support\Facades\Log;

class TwitterService
{
    /**
     * @var $api
     */
    protected $api;

    public function __construct()
    {
        $this->api = Twitter::forApiV1();
    }

    /**
     * @param string $screenName
     * @param int $count
     * @param string $responseFormat
     * @return mixed
     */
    public function getUserTimeline(string $screenName, int $count, string $responseFormat)
    {
        $payload = [
            'screen_name'     => $screenName,
            'count'           => $count,
            'response_format' => $responseFormat
        ];

        return json_decode($this->api->getUserTimeline($payload));
    }

    /**
     * @param string $screenName
     * @return bool
     */
    public function checkUser(string $screenName): bool
    {
        try {
            $this->api->getUsersLookup(['screen_name' => $screenName]);
            return true;
        } catch (NotFoundException $e) {
            Log::error(json_encode($e));
        }
        return false;
    }
}
