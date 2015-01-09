
To install this app:

    1 - Add this repository on your composer.json
    	"repositories": [
            {
                "type": "vcs",
                "url": "https://github.com/rudignet/Laravel-JsonRpc"
            }
    	]
    2 - Require packages on composer.json "networkkings/jsonrpc": "*@dev"
    3 - If you want activate the server
        3.1 - Run 'php artisan config:publish networkkings/jsonrpc'
        3.2 - Configure options in app/config/packages/networkkings/jsonrpc
        3.3 - Add 'Networkkings\Jsonrpc\JsonRpcServiceProvider' to your providers array
    4 - Optionally add 'Jsonrpc' => 'Networkkings\Jsonrpc\JsonrpcFacade' for use Jsonrpc as a shortcut


How to use

        $json = App::make('jsonrpc',array('server' => $server, 'http_method' => 'GET','key' => $key)); //To get the jsonrpc object
        $json->send([Resolver],[Method],[Params])  //Resolver must be an existent configured resolver, Params must be an array

        Method must be called as ClassName.MethodName ,remote method must be static, you can change the default resolver in the config file
        Default resolver is \{class}Controller if you call Test.foo you are calling \TestController::foo($param1,$param2...) because {class} is replaced by ClassName
        You can create your custom resolvers on the config file to point where you want

[Namespaces]

    Config
        jsonrpc => Config files


[Configuration file (For server)]

    enabled => (bool) Enable or disable server webservice
    prefix => (string) Route where webservice is located
    methods => (array) Avaliable http methods on server (GET,POST,PUT,DELETE)
    resolvers => (array) Array with resolverName => resolverTemplate, the resolver is a template, it replace {class} for the called class
    allowed => (array) Array with Ip/Mask => KEY for allowed clients, use localhost for local request,  example '192.168.1.1/32' => 'SECRETKEY'
