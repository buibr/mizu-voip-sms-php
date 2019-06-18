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
    public function run(){

        $this->apientry = 'apitest';

        $this->validate();

        $request    = $this->makeRequest();
        $response   = $this->makeResponse($request[0], $request[1]);

        $this->extract($response);

        return $response;
    }
    
    /**
     * filter the response from the text of message
     */
    public function extract(\buibr\Mizu\mizuResponse &$response)
    {
        \preg_match("/^OK: (.*) \((.*)\)/", $response->response, $matches);

        $response->response = (object)[
            'message'   => $matches[1],
            'ip'        => $matches[2],
        ];
    }
}