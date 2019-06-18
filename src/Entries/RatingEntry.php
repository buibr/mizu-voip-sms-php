<?php 

namespace buibr\Mizu\Entries;

use GuzzleHttp\Client;
use buibr\Mizu\Exceptions\InvalidRequestException;
use buibr\Mizu\mizuResponse;

class RatingEntry extends \buibr\Mizu\mizuEntry {

    /**
     * Number to send the message to
     */
    public $bnum;

    /**
     *  Filters for number should be put here.
     * @param string $number - the receiver number.
     */
    public function setRecipient( $number )
    {
        $this->bnum = $number;

        if(empty($this->bnum)) {
            throw new InvalidConfigurationException("Receiver number not set.", 1002);
        }
    }

    /**
     * 
     */
    public function getRecipient(){
        return $this->bnum;
    }

    /**
     * 
     */
    public function validate( ){
        parent::validate();

        if(empty($this->bnum)) {
            throw new \buibr\Mizu\Exceptions\InvalidConfigurationException("Destination number is missing.", 1006);
        }
    }

    /**
     *  Get rating to country
     */
    public function run( $bnum = null ){

        if(!empty($bnum)) {
            $this->setRecipient( $bnum );
        }

        $this->setApientry('rating');

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
        $expl = explode(',', $response->response);
        $curr = explode(' ', trim($expl[0]));
        $dest = explode(' ', trim($expl[1]));

        $response->response = (object)[
            'price'=> $curr[0],
            'currency'=> $curr[1],
            'destination' => $dest[1]
        ];
    }
}