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
     * 
     */
    public function validate( ){
        parent::validate();

        if(empty($this->bnum)) {
            throw new InvalidConfigurationException("Destination number is missing.", 1006);
        }
    }

    /**
     *  Get rating to country
     */
    public function rating( $bnum = null ){

        if(!empty($bnum)) {
            $this->setRecipient( $bnum );
        }

        $this->setApientry('rating');

        $this->validate();

        $response = $this->makeRequest();

        return $this->makeResponse($response[0], $response[1]);
        
    }
}