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
    private $server;

    /**
     * 
     */
    private $apiPath = 'mvapireq'; // mvapireq, mvstwebsock

    /**
     * 
     */
    private $authKey;

    /**
     * 
     */
    private $authId;

    /**
     * 
     */
    private $authpwd;

    /**
     *  Sender name.
     */
    private $anum;

    /**
     *  Message receipient number
     */
    private $bnum;

    /**
     * the bodu of the message.
     */
    private $message;

    /**
     * 
     */
    public function __construct( array $attr = [] )
    {
        if( empty($attr) ) {
            return $attr;
        }

        foreach($attr as $key=>$val) {
            if(\property_exists($this, $key)) {
                $this->$key = $val;
            }
        }
    }

    /**
     * 
     */
    public function __set($attr, $val)
    {
        if(\property_exists($this, $attr)) {
            
            if($attr === 'anum')
                $this->setSender($val);
            
            elseif($attr === 'message')
                $this->setMessage($val);
            
            else
                $this->$attr = $val;
        }
    }

    /**
     * 
     */
    public function __get( $key )
    {
        return $this->$key;
    }

    /**
     * 
     */
    public function validate()
    {
        if(empty($this->server)) {
            throw new InvalidConfigurationException("Server not defined", 1001);
        }

        if(empty($this->authKey)) {
            throw new InvalidConfigurationException("Auth key missing.", 1002);
        }

        if(empty($this->authId)) {
            throw new InvalidConfigurationException("Auth ID missing.", 1002);
        }

        if(empty($this->authpwd)) {
            throw new InvalidConfigurationException("Auth password not set.", 1002);
        }

    }

    /**
     *  Sender of the message.
     * @param string $sender - maximum 11 characters.
     */
    public function setSender( $sender )
    {
        return $this->anum = substr($sender,0,11);
    }

    /**
     * Set the body of the sms message.
     * @param string $message - The message to send.
     * @param boolean $raw - true = \rawurlencode, false \urlencode
     */
    public function setMessage( $message, $raw = null )
    {
        if(is_null($raw)) {
            return $this->message = $message;
        }

        if ($raw) {
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
        $a = [];

        foreach($this as $key=>$val)
        {
            $a[$key] = $val;
        }

        return $a;
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

        $this->validate();

        try
        {
            
            $req = new mizuCurl;
            $req->setUrl( "https://{$this->server}/{$this->apiPath}/" );
            $req->setParams( array_merge(['apientry'=>'sms', 'txt'=>$message], $this->toArray()) ) ;
            
            return $req->request();

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

        $this->validate();

        try
        {
            
            $req = new mizuCurl;
            $req->setUrl( "https://{$this->server}/{$this->apiPath}/" );
            $req->setParams( array_merge(['apientry'=>'balance'], $this->toArray()) ) ;
            
            return $req->request();

        }
        catch( \ErrorException $e)
        {
            return false;
        }
    }


}