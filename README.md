
To install this app:

    1 - Add this repository on your composer.json
    	"repositories": [
            {
                "type": "vcs",
                "url": "https://github.com/rudignet/Laravel-JsonRpc"
            }
    	]
    2 - Require packages on composer.json "networkkings/jsonrpc": "*@dev"
    3 - Run 'php artisan config:publish networkkings/jsonrpc'
    4 - Configure options in app/config/packages/networkkings/jsonrpc
    5 - Add 'Networkkings\Jsonrpc\JsonRpcServiceProvider' to your providers array
    6 - Optionally add 'Jsonrpc' => 'Networkkings\Jsonrpc\JsonrpcFacade'


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
            prefix => (string) Route where webservice is located
            methods => (array) Avaliable http methods on server (GET,POST,PUT,DELETE)
            resolvers => (array) Array with resolverName => resolverTemplate, the resolver is a template, it replace {class} for the called class
            allowed => (array) Array with Ip/Mask => KEY for allowed clients, use localhost for local request,  example '192.168.1.1/32' => 'SECRETKEY'
