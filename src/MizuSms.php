<?php

namespace buibr\MizuSms;

use buibr\MizuSms\mizuResponse;
use buibr\MizuSms\Exceptions\InvalidConfigurationException;


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
     * 
     */
    public function setMessage( $message )
    {
        $this->message = \rawurlencode($message);
    }

    /**
     *  posile filters for number set it here.
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
     * @param string|int $bnum - number that will receive the sms.\
     * 
     * @return mizuResponse
     * 
     * @throws ErrorException
     */
    public function send( $bnum, $message )
    {

        $this->setRecipient( $bnum );
        $this->setMessage( $message );
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
     * @param string|int $bnum - number that will receive the sms.\
     * 
     * @return mizuResponse
     * 
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