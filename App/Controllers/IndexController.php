<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action {

	public function index() {
		$this->render('index');
	}

	public function subscribe() {
		$this->render('subscribe');
	}

	public function register() {
		$user = Container::getModel('User');
		$user->__set('name', $_POST['name']);
		$user->__set('email', $_POST['email']);
		$user->__set('password', md5($_POST['password']));

		if ($user->isValid()) {
			$user->save();

			$this->render('register');
		} else {
			header('Location: /inscreverse?error='.$user->__get('error'));
		}
	}

}


?>