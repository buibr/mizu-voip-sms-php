<?php

namespace buibr\Mizu;

use buibr\Mizu\Exceptions\InvalidConfigurationException;


class mizuApi {

    
    const FORMAT_JSON   = 'json';
    const FORMAT_XML    = 'xml';
    const FORMAT_TEXT   = 'text';

    const MODE_MINIMAL  = 'mini';
    const MODE_FULL     = 'full';
    const MODE_STATS    = 'stats';

    
    /**
     * private 
     */
    protected $authkey;

    /**
     * 
     */
    protected $authid;

    /**
     * 
     */
    protected $authsalt;

    /**
     * 
     */
    protected $authmd5;

    /**
     * 
     */
    protected $authpwd;

    /**
     *  Sip Server Url
     */
    protected $server;

    /**
     * Not the time in timestamp.
     */
    protected $now;

     /**
     * 
     */
    public $format = self::FORMAT_XML;

    /**
     * request method.
     */
    public $method = 'get';

    /**
     * path after server
     */
    public $path = 'mvapireq';


    /**
     *  
     */
    public function __construct( $data ){
        $this->now = time();

        if(\is_object($data) && $data instanceof mizuApi){
            $data = $data->toArray();
        }

        if(empty($data)){
            return $this;
        }

        foreach($data as $key=>$val){
            $key = \strtolower($key);
            if(\property_exists($this, \strtolower($key)))
            {
                $this->$key = $val;
            }
        }

        return $this;
    }
    
    /**
     * 
     */
    public function __toString(){
        $arr = $this->toArray();

        unset($arr['method']);
        unset($arr['server']);
        unset($arr['path']);

        return $this->getEndpoint() . \http_build_query($arr);
    }

    /**
     * validate the request/
     */
    public function validate()
    {
        if(empty($this->server)) {
            throw new InvalidConfigurationException("Server not defined", 1001);
        }

        if(empty($this->authkey)) {
            throw new InvalidConfigurationException("Auth key missing.", 1002);
        }

        if(empty($this->authid)) {
            throw new InvalidConfigurationException("Auth ID missing.", 1003);
        }

        if(empty($this->authpwd) && (empty($this->authmd5) || empty($this->authsalt) ) ) {
            throw new InvalidConfigurationException("Auth password not set.", 1004);
        }

    }

    /**
     * Returns only basic endpoint of the request.
     */
    public function getEndpoint(){
        return "https://{$this->server}/{$this->path}/?";
    }

    /**
     * List of all attributes except endpoint ones.
     */
    public function getParams() {
        $arr = $this->toArray();

        unset($arr['method']);
        unset($arr['server']);
        unset($arr['path']);

        return $arr;
    }

    /**
     * 
     */
    public function toArray(){

        $array = [];
        foreach($this as $key=>$val){
            $array[$key] = $val;
        }
        return $array;
    }

    /**
     * private 
     */
    public function setAuthKey($key){
        $this->authkey = $key;
    }
    public function getAuthKey() {
        return $this->auth;
    }

    /**
     * 
     */
    public function setAuthId($id){
        $this->authid = $id;
    }
    public function getAuthId() {
        return $this->authid;
    }

    /**
     * 
     */
    public function setAuthSalt($salt){
        $this->authsalt = $salt;
    }
    public function getAuthSalt() {
        return $this->authsalt;
    }

    /**
     * 
     */
    public function setAuthMd5($md5){
        $this->authmd5 = $md5;
    }
    public function getAuthMd5() {
        return $this->authmd5;
    }

    /**
     *  Sip Server Url
     */
    public function setServer($server){
        $this->server = $server;
    }
    public function getServer() {
        return $this->server;
    }

    /**
     * response format from mizu
     */
    public function setFormat($format){

        if(!\in_array($format, [self::FORMAT_JSON, self::FORMAT_TEXT, self::FORMAT_XML])){
            throw new InvalidConfigException("Unsupported format.");
        }

        $this->format = $format;
        
    }
    public function getFormat(){
        return $this->format;
    }

}