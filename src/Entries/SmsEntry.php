<?php 

namespace buibr\Mizu\Entries;

use GuzzleHttp\Client;
use buibr\Mizu\Exceptions\InvalidRequestException;
use buibr\Mizu\mizuResponse;

class SmsEntry extends \buibr\Mizu\mizuEntry {

    /**
     * The name on from message
     */
    public $anum;

    /**
     * Number to send the message to
     */
    public $bnum;

    /**
     * Text message to send.
     */
    public $message;

    /**
     *  Sender of the message.
     * @param string $sender - maximum 11 characters.
     */
    public function setSender( $sender )
    {
        return $this->anum = substr($sender,0,11);
    }
    public function getSender(){
        return $this->anum;
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
    public function getMessage(){
        return $this->message;
    }

    /**
     *  Filters for number should be put here.
     * @param string $number - the receiver number.
     */
    public function setRecipient( $number )
    {
        $this->bnum = trim($number);

        if(empty($this->bnum)) {
            throw new InvalidConfigurationException("Receiver number not set.", 1002);
        }
    }
    public function getRecipient(){
        return $this->bnum;
    }

    /**
     * 
     */
    public function validate( ){
        parent::validate();

        if( empty($this->anum) ) {
            throw new InvalidConfigurationException("Please fill the name will apear as from.", 1005);
        }

        if( empty($this->bnum) ) {
            throw new InvalidConfigurationException("Destination number is missing.", 1006);
        }

        if( empty($this->message) ) {
            throw new InvalidConfigurationException("Message empty not allowed.", 1007);
        }
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
    public function run( $receiver = null, $message = null, $raw = null )
    {
        if(!empty($receiver)) {
            $this->setRecipient( $receiver );
        }

        if(!empty($message)) {
            $this->setMessage( $message , $raw);
        }

        $this->setApientry('sendsms');
        $this->validate();
        
        $request    = $this->makeRequest();
        $response   = $this->makeResponse($request[0], $request[1]);

        return $this->extract($response);
    }

    /**
     * Filter the response from the text of message
     */
    public function extract(\buibr\Mizu\mizuResponse &$response)
    {

        \preg_match('/^OK/',trim($response->response), $success);

        if(!empty($success)){
            $mess = trim(str_replace('OK,','', $response->response));
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