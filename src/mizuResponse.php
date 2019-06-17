<?php

namespace buibr\Mizu;

use buibr\Mizu\Exceptions\InvalidResponseException;


/** 
 * 
 * Author:      Burhan Ibraimi <burhan@wflux.pro>
 * Company      wFlux
 * 
 * Class:       mizuResponse
 * Created:     Tue Apr 02 2019 3:44:46 PM
 * 
**/
class mizuResponse {

    /**
     * Request reponse code.
     */
    public $code;

    /**
     * Response type format
     */
    public $type;

    /**
     * Total time taken for the request to response
     */
    public $time;

    /**
     * 
     */
    public $status;

    /**
     * 
     */
    public $response;

    public function __construct($curl, $data)
    {
        $header_size    = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $header         = substr($data, 0, $header_size);
        $body           = trim(substr($data, $header_size));
        
        $this->code = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        $this->type = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);
        $this->time = curl_getinfo($curl, CURLINFO_TOTAL_TIME);
        $this->data = $body;
        
        return $this->extract();
    }

    /**
     * 
     * 
     */
    public function extract( )
    {

        if( strpos($this->data, 'Destination: ') > -1){

            $str = explode(',', trim($this->data));

            $this->response = [
                'price'=>trim($str[0])
            ];

            return $this->status = true;
        }
        elseif(strpos($this->data, 'Your credit is') > -1){
            
            $this->response = trim(ltrim($this->data, 'Your credit is'));

            $this->response = [
                'price'=>trim($str[0])
            ];

            return $this->status = true;
        }
        elseif(strpos($this->data, ':') > -1)
        {
            $str = explode(':', $this->data);

            //  first caracters devine the status.
            $status     = array_shift($str);

            //  
            
            if(strtoupper(trim($status)) === 'OK' ) {
                $this->response = trim(implode(':', $str));
                return $this->status = true;
            }

            if(strtoupper(trim($status)) === 'ERROR' ) {
                $this->response = trim(ltrim(ltrim('ERROR',$this->data),':'));
                return $this->status = false;
            }

            throw new InvalidResponseException("Unknow response.", 3001);
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


}   