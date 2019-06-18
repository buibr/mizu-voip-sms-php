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
     * Optimization on usin in big amount of sms send.
     */
    public function minimal(){
        $this->request = null;
        $this->stats = null;
        return $this;
    }


}   