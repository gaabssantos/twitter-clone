<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class AuthController extends Action {
    public function authenticate() {
        $user = Container::getModel('User');

        $user->__set('email', $_POST['email']);
        $user->__set('password', md5($_POST['password']));

        if ($user->findUser()) {
            session_start();

            $_SESSION['id'] = $user->__get('id');
            $_SESSION['name'] = $user->__get('name');

            header('Location: /timeline');
        } else {
            header('Location: /?error=account_not_found');
        }
    }

    public function logout() {
        session_start();
        session_destroy();

        header('Location: /?logout');
    }
}

?>