<?php

namespace Networkkings\Jsonrpc;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;

class Jsonrpc {

    protected $server;
    protected $http_method;
    protected $key;
    protected $curl;

    public function __construct($server,$http_method,$key){
        $this->server = $server;
        $this->http_method = $http_method;
        $this->key = $key;
        $this->curl = curl_init();
    }


    /**
     * Sends a jsonrpc request
     * @param $resolver
     * @param $method string MethodName
     * @param $params array Parameters
     * @return JsonResponse
     * @throws \Exception
     */
    public function send($resolver = 'default' ,$method ,array $params = array()){

        $message = new JsonMessage($params,$method,$resolver); //Creamos un mensaje saliente

        if($this->http_method == 'POST'){
            curl_setopt($this->curl, CURLOPT_HTTPGET, false); //Get query false
            curl_setopt($this->curl, CURLOPT_POST, true); //Post query
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, array()); //If not POST doesn't work
        }else if($this->http_method == 'GET'){
            curl_setopt($this->curl, CURLOPT_POST, false); //Post query false
            curl_setopt($this->curl, CURLOPT_HTTPGET, true); //Get query
        }else
            throw new \Exception('Http method not setted');

        curl_setopt($this->curl, CURLOPT_URL, "{$this->server}?".$message->getQueryString($this->key)); //Set complete url
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);  // Return transfer as string
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        // Run query
        $response = curl_exec($this->curl);

        if(false === $response) //Si el resultado no ha sido válido devolvemos una respuesta incorrecta
            return JsonResponse::getInstance(null, false, 500, "Curl error $response", null);

        //Trying to decode json string from curl
        $responseArr = json_decode($response, true);

        if(null === $responseArr) //Si el json no se ha podido descodificar devolvemos una respuesta incorrecta
            return JsonResponse::getInstance(null, false, 417, "Unknown response: $response", null);

        return JsonResponse::getFromInput($responseArr,$this->key); //Reconstruimos el mensaje recibido
    }
}