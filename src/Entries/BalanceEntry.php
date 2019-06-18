<?php 

namespace buibr\Mizu\Entries;

use GuzzleHttp\Client;
use buibr\Mizu\Exceptions\InvalidRequestException;
use buibr\Mizu\mizuResponse;

class BalanceEntry extends \buibr\Mizu\mizuEntry {

    /**
     * Send sms to a number.
     * 
     * @return mizuResponse
     * @throws ErrorException
     */
    public function run( )
    {
        $this->setApientry('balance');
        $this->validate();

        $request    = $this->makeRequest();
        $response   = $this->makeResponse($request[0], $request[1]);

        $this->extract($response);

        return $response;
    }

    /**
     * Filter the response from balance 
     */
    public function extract(\buibr\Mizu\mizuResponse &$response)
    {
        $response->response = \trim( \ltrim($response->response, 'Your credit is'));
    }
}