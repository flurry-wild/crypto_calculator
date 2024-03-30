<?php

namespace App\Http\Clients;

use Illuminate\Support\Facades\Http;

class OkxClient
{
    public function getCourse($coin)
    {
        $url = "https://www.okx.com/priapi/v5/market/candles?instId=$coin-USDT";

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Accept-Encoding' => 'gzip, deflate, br, zstd',
            'Accept-Language' => 'ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
            'App-Type' => 'web',
            'Sec-Ch-Ua' => '"Chromium";v="123", "Not:A-Brand";v="8"',
            'Sec-Ch-Ua-Mobile' => '?0',
            'Sec-Ch-Ua-Platform' => '\"Linux\"',
            'Sec-Fetch-Dest' => 'empty',
            'Sec-Fetch-Mode' => 'cors',
            'Sec-Fetch-Site' => 'same-origin',
            'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36',
            'X-Cdn' => 'https://www.okx.com',
            'X-Locale' => 'ru_RU',
            'X-Zkdex-Env' => '0'
        ])->get($url)->json();

        $course = $response['data'][0][1];

        return floatval($course);
    }

    public function getUsdtRubCourse()
    {
        $url = 'https://www.okx.com/priapi/v5/market/currency-trend?baseCcy=USDT&quoteCcy=RUB&isPremium=false&bar=1m&limit=1';

        $response = Http::get($url)->json();

        $course = $response['data'][0][1];

        return floatval($course);
    }
}
