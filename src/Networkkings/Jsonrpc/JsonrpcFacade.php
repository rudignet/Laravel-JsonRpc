<?php
/**
 * Created by PhpStorm.
 * User: carlos
 * Date: 20/11/14
 * Time: 14:25
 */

namespace Networkkings\Jsonrpc;

use Illuminate\Support\Facades\Facade;

class JsonrpcFacade extends Facade{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'jsonrpc'; }
}