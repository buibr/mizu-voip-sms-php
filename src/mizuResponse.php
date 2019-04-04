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

    public $code;
    public $type;
    public $time;

    public $status;
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
        $str = explode(':', $this->data);

        //  first caracters devine the status.
        $status     = array_shift($str);

        //  
        $this->response = trim(implode(':', $str));

        if(strtoupper(trim($status)) === 'OK' ) {
            return $this->status = true;
        }

        if(strtoupper(trim($status)) === 'ERROR' ) {
            return $this->status = false;
        }

        throw new InvalidResponseException("Unknow response.", 3001);

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