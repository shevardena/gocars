<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsService
{
    private string $brandName;
    private string $key;
    private string $url;

    public function __construct()
    {
        $this->brandName = env('SMS_OFFICE_BRAND', '');
        $this->key = env("SMS_OFFICE_KEY", '');
        $this->url = env("SMS_OFFICE_URL", '');
    }

    public function send($phones, $text): bool
    {
        if (!$phones || !$text) {
            return false;
        }

        $response = Http::get($this->url, [
            'key' => $this->key,
            'destination' => $phones,
            'sender' => $this->brandName,
            'content' => $text,
            'urgent' => true,
        ]);

        return $response->successful();
    }
}
