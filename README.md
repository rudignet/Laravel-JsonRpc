
To install this app:

    1 - Include "lucidnetworks/jsonrpc": "dev-master" on your require composer.json

    3 - If you want activate the server
        3.1 - Run 'php artisan vendor:publish lucidnetworks/jsonrpc'
        3.2 - Configure options in config/jsonrpc
        3.3 - Add Lucid\Jsonrpc\JsonrpcServiceProvider::class to your providers array
    4 - Optionally add 'Jsonrpc' => Lucid\Jsonrpc\JsonrpcFacade::class to your app.php aliases for use Jsonrpc as a shortcut


How to use
         A - if you have added 'Jsonrpc' => Lucid\Jsonrpc\JsonrpcFacade::class on your app->aliases
            $json = App::make('jsonrpc',array('server' => [Server_Url], 'http_method' => 'GET','key' => [Your_Secret_Key]));
         B - If you haven't added Jsonrpc to your aliases
            $json = new Lucid\Jsonrpc\Jsonrpc([Server_Url],[GET or POST],[Your_Secret_Key]);

        $json->send([Resolver],[Method],[Params])  //Resolver must be an existent configured resolver, Params must be an array
        $json->send returns a JsonResponse object

        Method must be called as ClassName.MethodName ,remote method must be static, you can change the default resolver in the config file
        Default resolver is \{class}Controller if you call Test.foo you are calling \TestController::foo($param1,$param2...) because {class} is replaced by ClassName
        You can create your custom resolvers on the config file to point where you want


[Configuration file (For server)]

    enabled => (bool) Enable or disable server webservice
    prefix => (string) Route where webservice is located
    methods => (array) Avaliable http methods on server (GET,POST,PUT,DELETE)
    resolvers => (array) Array with resolverName => resolverTemplate, the resolver is a template, it replace {class} for the called class, if you don't use {class} tag controller name will be fix
    allowed => (array) Array with Ip/Mask => KEY for allowed clients, use localhost for local request,  example '192.168.1.1/32' => 'SECRETKEY'
