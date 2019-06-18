<?php

namespace buibr\Mizu\Response;


class XmlResponse  extends \buibr\Mizu\mizuResponse {

    public function __construct(\GuzzleHttp\Psr7\Response &$res, \GuzzleHttp\TransferStats &$stats,  $mode = \buibr\Mizu\mizuApi::MODE_MINIMAL){

        $this->response = (string)$res->getBody();

        #   prevents xml printing errors with not controll.
        libxml_use_internal_errors(true);

        $xml = \simplexml_load_string($this->response);

        if(!$xml){
            $errs = \libxml_get_errors();
            throw new \buibr\Mizu\Exceptions\InvalidResponseException($errs[0]->message);
        }

        if($xml->code == 200){
            $this->status = true;
        }

        $this->response = ((array)$xml->text)[0];
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

        return $this;
    }

}