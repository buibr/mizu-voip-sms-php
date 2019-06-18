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
    public function withMinimal(){
        $this->mode = parent::MODE_MINIMAL;
        return $this;
    }

    /**
     *  Do not add full request and response stats class to response.
     */
    public function withFull(){
        $this->mode = parent::MODE_FULL;
        return $this;
    }


    /**
     * 
     */
    public function withStats(){
        $this->mode = parent::MODE_STATS;
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
            throw new InvalidRequestException($e->getMessage(), $e->getCode());
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

}