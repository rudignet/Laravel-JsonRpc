<?php

namespace Lucid\Jsonrpc;

use Illuminate\Support\Facades\Facade;

class JsonrpcFacade extends Facade{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'jsonrpc'; }
}