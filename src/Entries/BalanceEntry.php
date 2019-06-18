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
        \preg_match('/^Your credit is/',trim($response->response), $success);
        if(!empty($success)){
            $mess = trim(str_replace(['Your credit is','OK:'],'', $response->response));
            $response->response = \trim($mess);
            return $response;
        }

        \preg_match('/^ERROR:/', trim($response->response), $errors);
        if(!empty($errors)){
            $errors = trim(str_replace(['ERROR:', 'DISPLAY'],'', $response->response));
            $errors = explode(':', $errors);
            $response->response = [
                'type'=> $errors[0],
                'message' => $errors[1]
            ];
            return $response;
        }

        return $response;
    }
}