<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-02
 * Time: 05:36
 */

namespace Zhyu;

use Illuminate\Support\ServiceProvider;
use Zhyu\Decorates\Buttons\NormalButton;
use Zhyu\Decorates\Buttons\SimpleButton;

class ZhyuServiceProvider extends ServiceProvider
{
	public function register(){
        $this->app->bind('button.create', function ($app, $params) {
            return new NormalButton($params['data'], @$params['url'], 'btn btn-info m-r-15', 'fa fa-plus fa-fw', null, $params['text'], @$params['title']);
        });
        $this->app->bind('button.edit', function ($app, $params) {
            return new SimpleButton($params['data'], @$params['url'], 'btn btn-info btn-circle btn-sm m-l-5', 'ti-pencil-alt', null, $params['text'], @$params['title']);
        });
        $this->app->bind('button.show', function ($app, $params) {
            return new SimpleButton($params['data'], @$params['url'], 'btn btn-warning btn-circle btn-sm m-l-5', 'ti-file', null, $params['text'], @$params['title']);
        });
        $this->app->bind('button.destroy', function ($app, $params) {
            return new SimpleButton($params['data'], @$params['url'], 'btn btn-danger btn-circle btn-sm m-l-5', 'ti-trash', null, $params['text'], @$params['title']);
        });
	}
	
	public function boot(){
		$this->loadRoutesFrom(__DIR__.'/routes/web.php');
		$this->loadTranslationsFrom(__DIR__.'/lang', 'zhyu');

		$this->loadViewsFrom(__DIR__.'/blades', 'zhyu');

		$this->publishes([
		    __DIR__.'/blades' => resource_path('views/vendor/zhyu', 'zhyu'),
            __DIR__.'/assets/js' => resource_path('js', 'zhyu'),
        ]);
	}
	
	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [
			Zhyu\ZhyuServiceProvider::class,
		];
	}
	
}
