<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Response;

class MessageService
{
    /**
     * Create a new sms transaction message (send new one)
     *
     * @param string $transactionPublicId
     * @return array
     */
    public function send(string $transactionPublicId): array
    {
        $client = new \GuzzleHttp\Client([
            'verify' => config('app.env') !== 'local'
        ]);

        $endpoint = $this->getEndpoint(['transaction_public_id' => $transactionPublicId]);

        $response = $client->get($endpoint);

        $body = json_decode($response->getBody()->getContents());

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return ['success' => false, 'error' => $body, 'statusCode' => $response->getStatusCode()];
        };

        return ['success' => true, 'response' => $body];
    }

    /**
     * Get message api endpoint to request
     *
     * @return string
     */
    private function getEndpoint(array $params): string
    {
        $secret = config('keys.message');

        $endpoint = "https://{$secret}.mocklab.io/notify";
        //$endpoint .= "?" . http_build_query($params);

        return $endpoint;
    }
}
