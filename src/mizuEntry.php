<?php 

namespace buibr\Mizu;

class mizuEntry extends mizuApi {


    const FORMAT_JSON   = 'json';
    const FORMAT_XML    = 'xml';
    const FORMAT_TEXT   = 'text';

    /**
     *  
     */
    public $apientry;

    /**
     * 
     */
    public $format = self::FORMAT_JSON;

    /**
     * 
     */
    public function __construct($data){
        parent::__construct($data);

        return $this;
    }


    /**
     * 
     */
    public function setapientry($data){
        $this->apientry = $data;
    }
    public function getapientry(){
        return $this->apientry;
    }

    /**
     * 
     */
    public function setformat($format){

        if(!\in_array($format, [self::FORMAT_JSON, self::FORMAT_TEXT, self::FORMAT_XML])){
            throw new InvalidConfigException("Unsupported format.");
        }

        $this->format = $format;
    }
    public function getformat(){
        return $this->format;
    }

}