<?php

namespace buibr\Mizu\Response;


class TextResponse  extends \buibr\Mizu\mizuResponse {

    public function __construct(\GuzzleHttp\Psr7\Response &$res, \GuzzleHttp\TransferStats &$stats, $mode = \buibr\Mizu\mizuApi::MODE_MINIMAL ){

        $this->response = (string)(string)$res->getBody();
        $this->code     = $res->getStatusCode();
        $this->type     = $res->getHeader('content-type')[0];
        $this->time     = $stats->getTransferTime();
        
        if($mode === \buibr\Mizu\mizuApi::MODE_FULL){
            $this->length   = $res->getHeader('Content-Length')[0];
            $this->request  = $res;
            $this->stats    = $stats;
        }
        elseif($mode === \buibr\Mizu\mizuApi::MODE_STATS) {
            $this->stats    = $stats;
        }

        preg_match('/^OK:/', trim($this->response), $ok);
        preg_match('/Destination:/', trim($this->response), $dest);
        preg_match('/^Your credit is/', trim($this->response), $credit);
        preg_match('/^Postpaid/', trim($this->response), $balance);

        if(empty($ok) && empty($dest) && empty($credit) && empty($balance)){
            $this->status = false;
            throw new \buibr\Mizu\Exceptions\InvalidResponseException($this->response);
        }

        $this->status = true;
        return $this;
    }

}