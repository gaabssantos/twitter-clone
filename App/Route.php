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

		$this->setRoutes($routes);
	}

}

?>