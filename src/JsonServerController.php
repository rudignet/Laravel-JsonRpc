<?php

namespace Lucid\Jsonrpc;

use Illuminate\Routing\Controller;

class JsonServerController extends Controller{

    protected $input;
    protected $key;
    protected $exceptionPrefix = 'JsonRpc Server error';

    /**
     * Respond any request
     * @return \Illuminate\Http\JsonResponse
     */
    public function anyIndex(){

        if(!in_array(\Input::getMethod(),\Config::get('jsonrpc.server.methods'))) //Comprobamos que el método recibido es válido
            return Models\JsonResponse::response(array(),null,false,400,$this->exceptionPrefix.' - Method '.\Input::getMethod().' is not allowed');

        if(!$this->allowedIp()) //Comprobamos que la ip esta autorizada
            return Models\JsonResponse::response(array(),null,false,403,$this->exceptionPrefix.' - Remote IP '.\Request::getClientIp().' not allowed');

        if(!is_object($message = Models\JsonMessage::getFromInput(\Input::all(),$this->key))){ //Si la decodificación del mensaje no devuelve un objeto hay un error
            if($message == 400)
                return Models\JsonResponse::response(array(),null,false,$message,$this->exceptionPrefix.' - Bad request structure');
            if($message == 403)
                return Models\JsonResponse::response(array(),null,false,$message,$this->exceptionPrefix.' - Bad message sign');
            else
                return Models\JsonResponse::response(array(),null,false,$message,$this->exceptionPrefix.' - Undefined Error');
        }

        if(!strpos($message->method,'.')) //Method debe estar compuesto por className.Methodo
            return Models\JsonResponse::response(array(),null,false,400,$this->exceptionPrefix.' - Bad method name');

        $resolver = \Config::get("jsonrpc.server.resolvers.{$message->resolver}");

        if(empty($resolver)) //Comprobamos que el resolver existe
            return Models\JsonResponse::response(array(),null,false,400,$this->exceptionPrefix." - Resolver {$message->resolver} is not assigned");

        list($class,$method) = explode('.',$message->method,2); //Obtenemos la clase y el método
        $class = str_replace('{class}',$class,$resolver); //Obtenemos la clase a la que debemos llamar según el resolver configurado

        if(!class_exists($class)) //Comprobamos que la clase existe
            return Models\JsonResponse::response(array(),null,false,501,$this->exceptionPrefix." - Class $class doesn't exist");

        try{ //Intentamos llamar al método estático solicitado
            $funcResponse = call_user_func_array ("$class::$method", $message->params);
            return Models\JsonResponse::response($funcResponse,$this->key,true,200);
        }catch(\Exception $e){
            return Models\JsonResponse::response(array(),null,false,500,$this->exceptionPrefix.' - '.$e->getMessage());
        }

    }

    /**
     * Check if user ip is allowed in server configuration
     * @return bool
     */
    private function allowedIp(){
        $clientIp = \Request::getClientIp();
        $allowedArr = \Config::get('jsonrpc.server.allowed');
        $valid = false;

        if($clientIp == '::1' && isset($allowedArr['localhost'])){ //Si estamos ejecutando desde el mismo servidor comprobamos si está habilitado localhost
            $this->key = $allowedArr['localhost'];
            $valid = true;

        }else{ //En caso contrario comprobamos t0do el array de allowed
            foreach($allowedArr as $ipRange => $key)
                if($this->ip_in_range($clientIp,$ipRange)){
                    $this->key = $key;
                    $valid = true;
                    break;
                }
        }

        return $valid;
    }

    /**
     * Check if a given ip is in a network
     * @param  string $ip    IP to check in IPV4 format eg. 127.0.0.1
     * @param  string $range IP/CIDR netmask eg. 127.0.0.0/24, also 127.0.0.1 is accepted and /32 assumed
     * @return boolean true if the ip is in this range / false if not.
     */
    private function ip_in_range($ip, $range) {
        if(strpos( $range, '/' ) == false)
            $range .= '/32';

        // $range is in IP/CIDR format eg 127.0.0.1/24
        list( $range, $netmask ) = explode( '/', $range, 2 );
        $range_decimal = ip2long( $range );
        $ip_decimal = ip2long( $ip );
        $wildcard_decimal = pow( 2, ( 32 - $netmask ) ) - 1;
        $netmask_decimal = ~ $wildcard_decimal;
        return ( ( $ip_decimal & $netmask_decimal ) == ( $range_decimal & $netmask_decimal ) );
    }

}