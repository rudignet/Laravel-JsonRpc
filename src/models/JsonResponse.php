<?php
/**
 * Created by PhpStorm.
 * User: carlos
 * Date: 04/12/14
 * Time: 13:06
 */

namespace Networkkings\Jsonrpc;


class JsonResponse {

    public $data;
    public $message;
    public $statuscode;
    public $success;
    public $sign;

    public function __construct($data, $success, $statuscode, $message, $key){
        $this->data = $data;
        $this->message = $message;
        $this->statuscode = $statuscode;
        $this->success = $success;
        $this->sign = !empty($key) ? md5(json_encode($this->data).$this->success.$this->statuscode.$this->message.$key) : null; //Calculamos la firma
    }


    /**
     * Rebuild a response message from a input array and check the sign, if incorrect sign returns an error message
     * @param array $input
     * @param $key
     * @return JsonResponse
     */
    public static function getFromInput(array $input,$key){

        if(!array_key_exists('data',$input) || !array_key_exists('message',$input)|| !array_key_exists('statuscode',$input) || !array_key_exists('success',$input) || !array_key_exists('sign',$input))
            return self::getInstance(null, false, 400, 'Response error: Bad structure', null); //Devolvemos un mensaje de error de esctructura

        $data = $input['data'];
        $message = $input['message'];
        $statuscode = $input['statuscode'];
        $success = $input['success'];
        $sign = $input['sign'];

        //Si success es true calculamos la firma de la respuesta para verificar que el servidor es correcto (Con success false los mensajes no van firmados)
        $validsign = (!empty($key) && $success) ? md5(json_encode($data).$success.$statuscode.$message.$key) : null;

        if(!$success || $validsign == $sign) //Si ha ocurrido un error o la respuesta es ok y la firma de respuesta válida devvlemos el objeto
            return self::getInstance($data,$success,$statuscode,$message,null); //No hace falta firmar el mensaje para uso interno
        else
            return self::getInstance(null, false, 403, 'Response error: Incorrect sign', null); //Devolvemos un mensaje de error de firmado

    }


    /**
     * Get an instance of Response
     * @param $data
     * @param $success
     * @param $statuscode
     * @param $message
     * @return JsonResponse
     */
    public static function getInstance($data, $success, $statuscode, $message, $key){
        return new JsonResponse($data, $success, $statuscode, $message, $key);
    }

    /**
     * Generate a Laravel response
     * @param mixed $data
     * @param string $key
     * @param bool $success
     * @param int $statuscode
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function response($data, $key, $success = true, $statuscode = 200, $message = ''){
        $headers = array('Content-Type' => 'application/json; charset=UTF-8;');
        return \Response::json(self::getInstance($data, $success, $statuscode, $message, $key),$statuscode,$headers,JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get message data response
     * @return mixed
     */
    public function get(){
        return $this->data;
    }

}