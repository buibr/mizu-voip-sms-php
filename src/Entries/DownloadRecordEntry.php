<?php 

namespace buibr\Mizu\Entries;

use GuzzleHttp\Client;
use buibr\Mizu\Exceptions\InvalidRequestException;
use buibr\Mizu\mizuResponse;

class DownloadRecordEntry extends \buibr\Mizu\mizuEntry {
    /**
     *  This is the cdr Long string
     */
    public $cdrid;

    /**
     * 
     */
    public function setCdrId( $value ){
        $this->cdrid = $value;
        return $this;
    }

    /**
     * 
     */
    public function getCdrId(){
        return $this->cdrid;
    }

    /**
     * 
     */
    public function validate( ){
        parent::validate();

        if( empty($this->cdrid) ) {
            throw new InvalidConfigurationException("Cdr Id is not set.", 1015);
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

        $this->setApientry('voicerecdownload');
        $this->validate();
        
        $request    = $this->makeRequest();
        $response   = $this->makeAudioResponse($request[0], $request[1]);

        return $response;
    }
}