<?php 

namespace buibr\Mizu;

use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;


class mizuEntry extends \buibr\Mizu\mizuApi {

    /**
     *  
     */
    public $apientry;

    /**
     * 
     */
    public $mode = parent::MODE_MINIMAL;

    /**
     * 
     */
    public function setApientry($data){
        $this->apientry = $data;
    }
    public function getApientry(){
        return $this->apientry;
    }

    /**
     *  Do not add full request and response stats class to response.
     */
    public function minimal(){
        $this->mode = parent::MODE_MINIMAL;
        return $this;
    }

    /**
     *  Do not add full request and response stats class to response.
     */
    public function full(){
        $this->mode = parent::MODE_FULL;
        return $this;
    }


    /**
     * 
     */
    public function stats(){
        $this->mode = parent::MODE_STATS;
        return $this;
    }

    /**
     * 
     */
    public function json(){
        $this->format = parent::FORMAT_JSON;
        return $this;
    }

    /**
     * 
     */
    public function xml(){
        $this->format = parent::FORMAT_XML;
        return $this;
    }
    /**
     * 
     */
    public function text(){
        $this->format = parent::FORMAT_TEXT;
        return $this;
    }

    /**
     * Make the request and check for erros to throw InvalidConfigException
     * @return array 
     * @throws \buibr\Mizu\Exceptions\InvalidRequestException
     */
    public function makeRequest(){
        try
        {
            $client = new Client();

            $stat   = null; 
            $res    = $client->request( \strtoupper($this->method), $this->getEndpoint(),  [
                'query'=>$this->getParams(),
                'on_stats' => function (TransferStats $stats) use (&$stat){
                    $stat = $stats;
                }
            ]);

            return [$res, $stat];

        }
        catch(\Exception $e){
            throw new \buibr\Mizu\Exceptions\InvalidRequestException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Generate response based on the format of the request and response.
     * @param object \GuzzleHttp\Psr7\Response $response
     * @param object \GuzzleHttp\TransferStats $stats
     * @return mizuResponse
     * @throws \buibr\Mizu\Exceptions\InvalidResponseException
     */
    public function makeResponse( \GuzzleHttp\Psr7\Response $response, \GuzzleHttp\TransferStats $stats ){

        switch ($this->getFormat()) {
            case parent::FORMAT_JSON:
                return new \buibr\Mizu\Response\JsonResponse($response, $stats, $this->mode);
                break;
            case parent::FORMAT_XML:
                return new \buibr\Mizu\Response\XmlResponse($response, $stats, $this->mode);
                break;
            default:
                return new \buibr\Mizu\Response\TextResponse($response, $stats, $this->mode);
                break;
        }
            
    }
    /**
     * When requesting to download file we need to se if the file is found or get errors based on the format we set
     * @param object \GuzzleHttp\Psr7\Response $response
     * @param object \GuzzleHttp\TransferStats $stats
     * @return mizuResponse
     * @throws \buibr\Mizu\Exceptions\InvalidResponseException
     */
    public function makeAudioResponse( \GuzzleHttp\Psr7\Response $response, \GuzzleHttp\TransferStats $stats ){

        $content = $response->getHeaderLine('Content-Type');

        \preg_match('/^audio\//', $content, $matches);


        if(empty($matches))
        {

            switch ($this->getFormat()) {
                case parent::FORMAT_JSON:
                    return new \buibr\Mizu\Response\JsonResponse($response, $stats, $this->mode);
                    break;
                case parent::FORMAT_XML:
                    return new \buibr\Mizu\Response\XmlResponse($response, $stats, $this->mode);
                    break;
                default:
                    return new \buibr\Mizu\Response\TextResponse($response, $stats, $this->mode);
                    break;
            }
        }
        else {
            return new \buibr\Mizu\Response\AudioResponse($response, $stats, $this->mode);
        }
            
    }

}