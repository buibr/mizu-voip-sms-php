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
    public function balance( )
    {

        $this->setApientry('balance');

        $this->validate();

        $response = $this->makeRequest();

        return $this->makeResponse($response[0], $response[1]);
    }

}