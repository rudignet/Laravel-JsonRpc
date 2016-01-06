<?php namespace Lucid\Jsonrpc;


use Illuminate\Support\ServiceProvider;

class JsonrpcServiceProvider extends ServiceProvider{

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;


    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([ __DIR__.'/config/jsonrpc.php' => config_path('jsonrpc.php') ]); //Si se pide la publicación de la configuración del paquete copiamos el fichero en el directori ode configuracion de la app

        if(\Config::get('jsonrpc.server.enabled')) //Añadimos el servicio si está enabled
            \Route::controller(\Config::get('jsonrpc.server.prefix','jsonrpc'),'\Networkkings\Jsonrpc\JsonServerController');
    }

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->mergeConfigFrom(__DIR__.'/config/jsonrpc.php', 'jsonrpc'); //Combinamos los ficheros de configuracion local con el del usuario

        $this->app->singleton('jsonrpc',function($app, $parameters){ //Registra el servicio como un singleton
            return new Jsonrpc($parameters['server'],$parameters['http_method'],$parameters['key']);
        });

	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [];
	}

}
