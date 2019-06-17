<?php 

namespace buibr\Mizu\Entries;

use GuzzleHttp\Client;

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
    public $txt;

    
    public function __construct($data){
        parent::__construct($data);

        $this->apientry = 'sendsms';

    }

    /**
     * 
     */
    public function validate(){
        parent::validate();

        if(empty($this->anum)) {
            throw new InvalidConfigurationException("Please fill the name will apear as from.", 1005);
        }

        if(empty($this->bnum)) {
            throw new InvalidConfigurationException("Destination number is missing.", 1006);
        }

        if(empty($this->txt)) {
            throw new InvalidConfigurationException("Message empty not allowed.", 1007);
        }
    }

    /**
     * Send an sms to the number.
     */
    public function send(){
        $this->validate();

        $client = new Client();

        $res = $client->request( \strtoupper($this->method), $this->getEndpoint(),  [
            'query'=>$this->getParams()
        ]);

        print('<pre>');
        print_r($this->getParams());
        print('</pre>');
        print('<pre>');
        print_r([$res, (string)$res->getBody()]);
        print('</pre>');
        die;

    }

}