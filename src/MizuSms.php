<?php

namespace buibr\Mizu;

use buibr\Mizu\mizuResponse;
use buibr\Mizu\Exceptions\InvalidConfigurationException;


/** 
 * 
 * Class:       MizuSms
 * Author:      Burhan Ibraimi <burhan@wflux.pro>
 * Company      wFlux
 * 
 * Created:     Tue Apr 02 2019 12:27:02 PM
 *
 * @docs:       https://www.mizu-voip.com/Portals/0/Files/VoIP_Server_API.pdf
 * 
**/
class mizuSMS {


    /**
     * Server endpoint url for the 
     */
    private $holder;

    private $anum;
    private $bnum;
    private $message;

    public function __construct( array $attr = [] )
    {
        $this->holder = new \buibr\Mizu\mizuApi($attr);
    }

    /**
     *  Validate the SMS Sending by Mizu
     */
    public function validate()
    {
        $this->holder->validate();
    }

    /**
     *  Sender of the message.
     * @param string $sender - maximum 11 characters.
     */
    public function setSender( $sender )
    {
        $this->anum = substr($sender,0,11);
        return $this;
    }

    /**
     * Set the body of the sms message.
     * @param string $message - The message to send.
     * @param boolean $raw - true = \rawurlencode, false \urlencode
     */
    public function setMessage( $message, $raw = null )
    {
        if( is_null($raw) ) {
            return $this->message = $message;
        }

        if( $raw ) {
            return $this->message = \rawurlencode($message);
        }

        return $this->message = \urlencode($message);
    }

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
     * Convert all parameters to array
     */
    public function toArray()
    {
        return $this->holder->toArray();
    }

    /**
     * Convert all parameters to array
     */
    public function toArrayAuth()
    {
        return $this->holder->toArray();
    }

    /**
     * Send sms to a number.
     * 
     * @param string|int $receiver - Number that will receive the sms.
     * @param string $message - The message to send.
     * @param boolean $raw - encode to raw or url or no encode (true = \rawurlencode, false = \urlencode, null = no changes)
     * 
     * @return mizuResponse
     * @throws ErrorException
     */
    public function send( $receiver = null, $message = null, $raw = null )
    {
        

        if(!empty($receiver)) {
            $this->setRecipient( $receiver );
        }

        if(!empty($message)) {
            $this->setMessage( $message , $raw);
        }

        $sms = new \buibr\Mizu\Entries\SmsEntry($this->holder);
        $sms->setRecipient($this->bnum);
        $sms->setSender($this->anum);
        $sms->setMessage($this->message);
        $sms->validate();

        try
        {

            return $sms->run();

        }
        catch( \ErrorException $e)
        {
            return false;
        }
    }

    /**
     * Send sms to a number.
     * 
     * @return mizuResponse
     * @throws ErrorException
     */
    public function balance( )
    {
        $sms = new \buibr\Mizu\Entries\BalanceEntry($this->holder);
        $sms->validate();

        try
        {
            return $sms->run();
        }
        catch( \ErrorException $e)
        {
            return false;
        }
    }

    /**
     *  Get rating to country
     */
    public function rating( $phone_number = null ){

        if(!empty($phone_number)) {
            $this->setRecipient( $phone_number );
        }

        $sms = new \buibr\Mizu\Entries\RatingEntry($this->holder);
        $sms->setRecipient($this->bnum);
        $sms->validate();

        try
        {
            return $sms->run();
        }
        catch( \ErrorException $e)
        {
            return false;
        }
        
    }

    /**
     *  Get rating to country
     */
    public function apitest( $phone_number = null ){

        $sms = new \buibr\Mizu\Entries\ApiTest($this->holder);
        $sms->validate();
        
        try
        {   
            return $req->run();
        }
        catch( \ErrorException $e)
        {
            return false;
        }
        
    }
}