<?php

namespace buibr\Mizu\Response;

use GuzzleHttp\Psr7\Response;
use buibr\Mizu\mizuResponse;

class XmlResponse  extends mizuResponse {

    public function __construct(\GuzzleHttp\Psr7\Response $res, \GuzzleHttp\TransferStats $stats){

        $body = (string)$res->getBody();

        $body = \json_decode($body);

        if(!empty(\json_last_error())){
            throw new \buibr\Mizu\Exceptions\InvalidResponseException( \json_last_error_msg(), \json_last_error());
        }

        $this->response = (string)$body;
        $this->code     = $res->getStatusCode();
        $this->type     = $res->getHeader('content-type')[0];
        $this->time     = $stats->getTransferTime();

        if($mode === \buibr\Mizu\mizuApi::MODE_FULL){
            $this->request  = $res;
            $this->stats    = $stats;
        }
        elseif($mode === \buibr\Mizu\mizuApi::MODE_STATS) {
            $this->stats    = $stats;
        }

        return $this;
    }
    
}