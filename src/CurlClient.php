<?php

namespace buibr\MizuSms;

use buibr\MizuSms\Exceptions\EnvironmentException;
use buibr\MizuSms\Exceptions\InvalidRequestException;

/** 
 * 
 * Class:       {classname}
 * Author:      Burhan Ibraimi <burhan@wflux.pro>
 * Company      wFlux
 * 
 * Created:     Tue Apr 02 2019 12:27:02 PM
 *
**/
class CurlClient {

    /**
     * For how long the request will be waited.
     */
    const DEFAULT_TIMEOUT = 60;


    /**
     * private 
     */
    private $curl;

    /**
     * 
     */
    private $curlOpt = [];

    /**
     * 
     */
    private $headers;

    /**
     * 
     */
    private $params;

    /**
     * 
     */
    private $method;

    /**
     * Endpoing url
     */
    private $url;

    /**
     * 
     */
    private $timeout;




    /**
     * @param array $url - endpoint of the request. 
     * @param string $$method -  post, get, put, head
     * @param array $headers - request headers.
     * @param array $params - query params.
     * @param mixed $body   - post body.
     * @param array $body   - post body.
     */
    public function __construct( string $url, string $method, array $headers, array $params, $body, int $timeout = self::DEFAULT_TIMEOUT )
    {
        $this->url      = $url;
        $this->setHeaders($headers);
        $this->setParams($params);  // query params
        $this->setMethod($method); 
        $this->setBody( $body );

        return $this;
    }

    /**
     * Validate request
     * @throws InvalidRequestException
     */
    protected function validate()
    {
        if( \filter_var( $this->url, FILTER_VALIDATE_URL) ){
            throw new InvalidRequestException("Not valid url");
        }

        if(empty($this->method)) {
            throw new InvalidRequestException("Method is not set.");
        }

        // if(empty($this->params)) {
        //     throw new InvalidRequestException("Method is not set.");
        // }

    }

    /**
     *  Make the request
     */
    public function request( string $url )
    {

        $this->validate();
        $this->setOptions();

        try 
        {

            if (!$this->$curl = curl_init()) {
                throw new EnvironmentException('Unable to initialize cURL');
            }

            if (!curl_setopt_array($this->$curl, $this->curlOpt)) {
                throw new EnvironmentException(curl_error($this->$curl));
            }

            if (!$response = curl_exec($this->$curl)) {
                throw new EnvironmentException(curl_error($this->$curl));
            }

            print('<pre>');
            print_r($response);
            print('</pre>');
            die;

        } 
        catch (\ErrorException $e) 
        {
            
            if (isset($curl) && is_resource($curl)) {
                curl_close($curl);
            }

            if (isset($buffer) && is_resource($buffer)) {
                fclose($buffer);
            }

            throw $e;
        }

    }

    /**
     * 
     */
    public function setOptions( array $options ) {

        // static binding 
        $this->curlOpt[CURLOPT_HEADER] = true;
        $this->curlOpt[CURLOPT_RETURNTRANSFER] = true;
        $this->curlOpt[CURLOPT_INFILESIZE] = Null;
        // $this->curlOpt[CURLOPT_HTTPHEADER] = array();
        $this->curlOpt[CURLOPT_TIMEOUT]     = $this->timeout;

        if ($body) {
            $this->curlOpt[CURLOPT_URL] .= '?' . $body;
        }

        switch (strtolower(trim($method))) {
            case 'get':
                $this->curlOpt[CURLOPT_HTTPGET] = true;
                break;
            case 'post':
                $this->curlOpt[CURLOPT_POST] = true;
                $this->curlOpt[CURLOPT_POSTFIELDS] = $this->buildQuery($data);
                break;
            case 'put':
                $this->curlOpt[CURLOPT_PUT] = true;
                if ($data) {
                    if ($buffer = fopen('php://memory', 'w+')) {
                        $dataString = $this->buildQuery($data);
                        fwrite($buffer, $dataString);
                        fseek($buffer, 0);
                        $this->curlOpt[CURLOPT_INFILE] = $buffer;
                        $this->curlOpt[CURLOPT_INFILESIZE] = strlen($dataString);
                    } else {
                        throw new EnvironmentException('Unable to open a temporary file');
                    }
                }
                break;
            case 'head':
                $this->curlOpt[CURLOPT_NOBODY] = true;
                break;
            default:
                $this->curlOpt[CURLOPT_CUSTOMREQUEST] = strtoupper($method);
        }

        return $this->curlOpt;
    }

    /**
     *  Url query params.
     * @param array $p 
     *  - url query parameters as key=>val
     */
    public function setParams( array $p )
    {
        $this->params = array();

        $p = $p ?: array();

        foreach ($p as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $item) {
                    $this->params[] = urlencode((string)$key) . '=' . urlencode((string)$item);
                }
            } else {
                $this->params[] = urlencode((string)$key) . '=' . urlencode((string)$value);
            }
        }

        if(!$this->params)
        {
            $this->url .= "?";
        }

        return  $this->params = implode('&', $this->params);
    }

    /**
     * 
     */
    public function setHeaders( array $headers = [] )
    {
        if(!isset($this->curlOpt[CURLOPT_HTTPHEADER]))
            $this->curlOpt[CURLOPT_HTTPHEADER] = array();

        foreach ($headers as $key => $value) {
            $this->curlOpt[CURLOPT_HTTPHEADER][] = "$key: $value";
        }
    }

    /**
     * 
     */
    public function setBody( $data )
    {
        $this->body = array();

        $p = $p ?: array();

        foreach ($p as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $item) {
                    $this->body[] = urlencode((string)$key) . '=' . urlencode((string)$item);
                }
            } else {
                $this->body[] = urlencode((string)$key) . '=' . urlencode((string)$value);
            }
        }

        return  $this->body = implode('&', $this->body);
    }
}