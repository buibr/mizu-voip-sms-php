<?php 

namespace buibr\Mizu\Entries;

use GuzzleHttp\Client;
use buibr\Mizu\Exceptions\InvalidRequestException;
use buibr\Mizu\mizuResponse;

class CdrEntry extends \buibr\Mizu\mizuEntry {

    protected $filterfieldtype = 'string';
    protected $retfieldname = 'id';
    protected $filterfieldname = 'sipcallid';
    protected $table = 'tb_cdrs';

    /**
     *  This is the cdr Long string
     */
    public $filterfieldvalue;

    /**
     * 
     */
    public function setFilterFieldValue( $value ){
        $this->filterfieldvalue = $value;
        return $this;
    }

    /**
     * 
     */
    public function getFilterFieldValue(){
        return $this->filterfieldvalue;
    }

    /**
     * 
     */
    public function validate( ){
        parent::validate();

        if( empty($this->filterfieldvalue) ) {
            throw new InvalidConfigurationException("Cdr long id is not found.", 1005);
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
    public function run( $cdr = null )
    {
        if(!empty($cdr)) {
            $this->setFilterFieldValue( $cdr );
        }

        $this->setApientry('getfield');
        $this->validate();
        
        $request    = $this->makeRequest();
        $response   = $this->makeResponse($request[0], $request[1]);

        $this->extract($response);

        return $response;
    }

    /**
     * Filter the response from the text of message
     */
    public function extract(\buibr\Mizu\mizuResponse &$response)
    {
        \preg_match('/^OK/',trim($response->response), $success);
        if(!empty($success)){
            $mess = trim(str_replace(['OK,','OK:'],'', $response->response));
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