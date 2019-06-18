<?php 

namespace buibr\Mizu\Entries;

use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;
use buibr\Mizu\Exceptions\InvalidRequestException;
use buibr\Mizu\mizuResponse;

class ApiTest extends \buibr\Mizu\mizuEntry {

    /**
     * Send an sms to the number.
     */
    public function test(){

        $this->apientry = 'apitest';

        $this->validate();

        $response = $this->makeRequest();

        return $this->makeResponse($response[0], $response[1]);
    }
}