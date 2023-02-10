<?php

namespace App\Services;

use App\Models\Log;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class RssService implements Interfaces\RssServiceInterface
{

    public function getNews()
    {
        $requestStart = now();
        $response = Http::get(config('app.rss_link'));

        $responseBody = $response->body();
        $responseBody = simplexml_load_string($responseBody, "SimpleXMLElement", LIBXML_NOCDATA);
        $responseBody = json_encode($responseBody);

        $bodyAsArray = json_decode($responseBody, true);

        $this->logRequest($requestStart, $response, $bodyAsArray);

        return $bodyAsArray['channel']['item'];
    }

    private function logRequest($requestStart, Response $response, array $responseBody)
    {
        $responseTiming = $response->transferStats->getTransferTime();
        $responseUrl = (string)$response->transferStats->getEffectiveUri();
        $responseCode = $response->status();
        $responseMethod = 'GET';

        Log::query()->create([
            'request_datetime' => $requestStart,
            'method' => $responseMethod,
            'url' => $responseUrl,
            'response_code' => $responseCode,
            'response_timing_ms' => $responseTiming * 1000,
            'response_body' => $responseBody,
        ]);
    }
}
