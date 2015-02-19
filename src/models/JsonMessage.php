<?php

namespace Networkkings\Jsonrpc;

class JsonMessage {

    public $params;
    public $method;
    public $resolver;

    public function __construct($params,$method,$resolver){
        $this->params = $params;
        $this->method = $method;
        $this->resolver = $resolver;
    }

    /**
     * Rebuild a message from a input array and check the sign
     * @param array $input
     * @param $key
     * @return JsonMessage|400 Incorrect request|403 Incorrect sign
     */
    public static function getFromInput(array $input,$key){

        if(empty($input['method']) || empty($input['params']) || empty($input['resolver']) || !array_key_exists('sign',$input))
            return 400; //Solicitud incorrecta

        $params = $input['params'];
        $method = $input['method'];
        $resolver = $input['resolver'];
        $sign = $input['sign'];

        $validsign = !empty($key) ? md5($params.$method.$resolver.$key) : null; //Calculamos la firma

        if($validsign == $sign)
            return new JsonMessage(json_decode($params,true), $method, $resolver);
        else{
            if(\Config::get('app.debug')) error_log("Jsonrpc invalid sign, must be $validsign");
            return 403; //No autorizado
        }

    }

    /**
     * Returns a query string to curl
     * @param $key
     * @param $post = true, post or get query string
     * @return string
     */
    public function getQueryString($key,$post){
        $params = json_encode($this->params);
        $sign = !empty($key) ? md5($params.$this->method.$this->resolver.$key) : null;

        if($post)
            return json_encode(['params' => $params, 'method' => $this->method, 'resolver' => $this->resolver, 'sign' => $sign]);
        else
            return "params=$params&method={$this->method}&resolver={$this->resolver}&sign=$sign";
    }


}