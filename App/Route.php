<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap {

	protected function initRoutes() {

		$routes['home'] = array(
			'route' => '/',
			'controller' => 'indexController',
			'action' => 'index'
		);

		$routes['subscribe'] = array(
			'route' => '/inscreverse',
			'controller' => 'indexController',
			'action' => 'subscribe'
		);

		$routes['register'] = array(
			'route' => '/register',
			'controller' => 'indexController',
			'action' => 'register'
		);

		$routes['authenticate'] = array(
			'route' => '/authenticate',
			'controller' => 'AuthController',
			'action' => 'authenticate'
		);

		$routes['timeline'] = array(
			'route' => '/timeline',
			'controller' => 'AppController',
			'action' => 'timeline'
		);

		$routes['logout'] = array(
			'route' => '/sair',
			'controller' => 'AuthController',
			'action' => 'logout'
		);

		$routes['tweet'] = array(
			'route' => '/tweet',
			'controller' => 'AppController',
			'action' => 'tweet'
		);

		$routes['who_follow'] = array(
			'route' => '/quem_seguir',
			'controller' => 'AppController',
			'action' => 'whoFollow'
		);

		$routes['action'] = array(
			'route' => '/action',
			'controller' => 'AppController',
			'action' => 'action'
		);

		$routes['remove'] = array(
			'route' => '/remove',
			'controller' => 'AppController',
			'action' => 'remove'
		);

		$this->setRoutes($routes);
	}

}

?>