<?php

namespace buibr\Mizu\Response;


class AudioResponse  extends \buibr\Mizu\mizuResponse {

    public function __construct(\GuzzleHttp\Psr7\Response &$res, \GuzzleHttp\TransferStats &$stats,  $mode = \buibr\Mizu\mizuApi::MODE_MINIMAL){

        $this->response = (string)$res->getBody();
        $this->code     = $res->getStatusCode();
        $this->type     = $res->getHeader('content-type')[0];
        $this->time     = $stats->getTransferTime();
        $this->status   = true;

        if($mode === \buibr\Mizu\mizuApi::MODE_FULL){
            $this->length   = $res->getHeader('Content-Length')[0];
            $this->request  = $res;
            $this->stats    = $stats;
        }
        elseif($mode === \buibr\Mizu\mizuApi::MODE_STATS) {
            $this->stats    = $stats;
        }

        return $this;
    }

}