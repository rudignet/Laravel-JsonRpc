<?php namespace Networkkings\Jsonrpc;

use Illuminate\Support\ServiceProvider;


class JsonrpcServiceProvider extends ServiceProvider {

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
		$this->package('networkkings/jsonrpc'); //Registra los nombre de espacio del paquete

		if(\Config::get('jsonrpc::config.server.enabled')) //Añadimos el servicio si está enabled
			\Route::controller(\Config::get('jsonrpc::config.server.prefix','jsonrpc'),'\Networkkings\Jsonrpc\JsonServerController');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
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
		return array();
	}

}
