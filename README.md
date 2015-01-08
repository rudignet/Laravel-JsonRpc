

To install this app:

    1 - Require packages on composer.json "networkkings/jsonrpc": "*" (Dont't forget to add local repositiories key)
    2 - Run 'php artisan config:publish networkkings/jsonrpc'
    3 - Configure options in app/config/packages/networkkings/jsonrpc
    4 - Add 'Networkkings\Jsonrpc\JsonRpcServiceProvider' to your providers array
    5 - Optionally add 'Jsonrpc' => 'Networkkings\Jsonrpc\JsonrpcFacade'


    To add local repositories on composer.json insert this key on you project json file, / path is where composer.json is located

    	"repositories": [
    	  {
    		"type": "artifact",
    		"url": "../artifact-packages/"
    	  }
    	]

How to use

        $json = App::make('jsonrpc',array('server' => $server, 'http_method' => 'GET','key' => $key)); //To get the jsonrpc object
        $json->send([Resolver],[Method],[Params])  //Resolver must be an existent configured resolver, Params must be an array

        Method must be ClassName.MethodName ,remote method must be static, you can change the default resolver in the config file
        With default resolver \{class}Controller if you call Test.foo you are calling \TestController::foo($param1,$param2...)
        You can create your custom resolvers on the config file

[Namespaces]

    Config
        jsonrpc => Config files


[WebService]

    Configuration
        server
            enabled => (bool) Enable or disable server webservice
            prefix => (string) Where webservice is located, allways inside shopPrefix, default is /shop/api
            methods => (array) Avaliable method on server
            resolvers => (array) Array with resolverName => resolverTemplate, it replace {class} for the called class
            allowed => (array) Array with Ip/Mask => KEY for allowed clients, use localhost for local request,  example '192.168.1.1/32' => 'SECRETKEY'
