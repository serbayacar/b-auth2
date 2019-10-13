<?php

namespace client;

class HTTPClient {

     //Client Variables
     private $url;
     private $baseURL;
     private $method;
     
     //CURL Variables
     private $curl;
     public $headers;
     private $options;
     private $timeout = 15;
     private $maxRedirects = 5;
     private $agent;
 
     //Response Variables
     private $response;
     private $responseBody;
     private $responseCode;
     private $responseHeaders;
 
     public function __construct($url = null , $headers = null, $options = null){
         $this->url = isset($url) ? $url : null;
         $this->baseURL = isset($url) ? $url : null;
         $this->headers = isset($headers) ? $headers : null;
         $this->options = isset($options) ? $options : null;
 
     }
 
     public function setAgent($agent){
         $this->agent = $agent;
     }
 
     public function setHeader($name, $value){
         $this->headers[$name] = $value;
     }
 
     public function setOptions($name, $value){
         $this->options[$name] = $value;
     }
 
     public function getAgent(){
         return $this->agent;
     }
 
     public function getHeaders(){
         return $this->headers;
     }
 
     public function getOptions(){
         return $this->options;
     }
 
     public function getResponse(){
         return $this->response;
     }
 
     public function getResponseHeaders(){
         return $this->responseHeaders;
     }
 
     public function getResponseBody(){
         return $this->responseBody;
     }
 
     public function getResponseCode(){
         return $this->responseCode;
     }
 
     public function getResponseBodyAsJson(){
         return json_encode($this->responseBody);
     }
 
     public function getResponseBodyAsArray($assoc = true){
         $body = $this->getResponseBody();
         $temp = [];
         json_decode($body);
         if((json_last_error() == JSON_ERROR_NONE)){
             return json_decode($this->responseBody, $assoc);
         }   else if (is_string($body)) {
       
             $temp[] = $body;
             return $temp;
         }
     }
 
     public function getResponseHeadersAsArray($assoc = true){
         $headers = $this->getResponseHeaders();
         if (!preg_match_all('/([A-Za-z\-]{1,})\:(.*)\\r/', $headers, $matches) || !isset($matches[1], $matches[2])){
             return false;
         }
 
         $explodedHeaders = [];
 
         foreach ($matches[1] as $index => $key){
             $explodedHeaders[$key] = $matches[2][$index];
         }
 
         return $explodedHeaders;
     }
 
     //HTTP Request Functions
     public function get($path, $params = []){
         $this->method = 'GET';
         $request = $this->request($path, $params);
         return $request;
     }
     public function post($path, $params = []){
         $this->method = 'POST';
         $request = $this->request($path, $params);
         return $request;
     }
     public function put($path, $params = []){
         $this->method = 'PUT';
         $request = $this->request($path, $params);
         return $request;
     }
     public function delete($path, $params = []){
         $this->method = 'DELETE';
         $request = $this->request($path, $params);
         return $request;
     }
     public function patch($path, $params = []){
         $this->method = 'PATCH';
         $request = $this->request($path, $params);
         return $request;
     }
 
     private function request($path, $params){
         
         //TODO :: Build extended url
         if (!filter_var($path, FILTER_VALIDATE_URL)) {
             $this->url = $this->url . $path;
         }else if(filter_var($path, FILTER_VALIDATE_URL)){
             $this->url = $path;
         }
 
         if (is_array($params) && count($params) > 0) {
             $queryParams = http_build_query($params);
             $this->url .= '?' . $queryParams;
         }
 
         //TODO :: body types
         if(isset($params['body'])){
             $bodyParams = $params['body'];
         }
         if (isset($params['json'])) {
             $bodyParams = json_encode($params['json']);
         }
         if (isset($params['form'])) {
             $bodyParams = http_build_query($params['form']);
         }

         $this->curl = curl_init($this->url);
 
         //TODO :: Create Curl Body
         $options = $this->createCurlOptions($this->method, $params);
 
         //TODO :: Create Curl Options
         curl_setopt_array($this->curl, $options);
 
         //TODO :: Create Curl Body
         if (in_array($this->method,['POST','PUT','PATCH'])) {
             curl_setopt($this->curl, CURLOPT_POSTFIELDS, $bodyParams);
         }
 
         $this->response = curl_exec($this->curl);
         //TODO :: Parsing Response
         $headerSize = curl_getinfo($this->curl, CURLINFO_HEADER_SIZE);
         $this->responseHeaders = substr($this->response, 0, $headerSize);
         $this->responseBody = substr($this->response, $headerSize);
         $this->responseCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
 
 
         curl_close($this->curl);
 
         $this->url = $this->baseURL;
         
         return $this;
     }
 
     private function createCurlOptions()
     {
         $options = [
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_HEADER => true,
                 CURLOPT_CUSTOMREQUEST => $this->method,
                 CURLOPT_CONNECTTIMEOUT => $this->timeout,
                 CURLOPT_TIMEOUT => $this->timeout,
                 CURLOPT_MAXREDIRS => $this->maxRedirects,
                 CURLOPT_HTTPHEADER => $this->createCurlHeaders(),
             ];
 
             if(isset($this->agent)){
                 $options += [CURLOPT_USERAGENT => $this->agent];
             }
         return $options;
     }
 
     private function createCurlHeaders()
     {
         $headers = [];
         if(is_array($this->headers) ){
             foreach($this->headers as $headerName => $headerValue) {
                 $headers[] = ucfirst($headerName) . ': ' . $headerValue;
             }
         }
         return $headers;
     }


}
