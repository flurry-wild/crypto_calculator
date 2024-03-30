<?php

namespace App\Courses;

use App\Http\Clients\OkxClient;

class FiatCourse implements Course
{
    public function getCourse($coin): float
    {
        /** @var OkxClient $client */
        $client = app(OkxClient::class);

        return $client->getUsdtRubCourse();
    }
}
