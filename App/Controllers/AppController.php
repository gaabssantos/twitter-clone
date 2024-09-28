<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {
    public function timeline() {
        $this->checkUserLogged();

        $tweet = Container::getModel('Tweet');
        $tweet->__set('id_usuario', $_SESSION['id']);
        $tweets = $tweet->getAll();

        $this->view->tweets = $tweets;
        $this->render('timeline');
    }

    public function tweet() {
        $this->checkUserLogged();

        $tweet = Container::getModel('Tweet');

        $tweet->__set('tweet', $_POST['tweet']);
        $tweet->__set('id_usuario', $_SESSION['id']);

        if (!empty($tweet->__get('tweet'))) {
            $tweet->save();

            header('Location: /timeline?success');
        } else {
            header('Location: /timeline?error=tweet_empty');
        }
    }

    function checkUserLogged() {
        session_start();

        if((!isset($_SESSION['id']) || !$_SESSION['id'] != '') && 
            (!isset($_SESSION['id']) || $_SESSION['name'] != '')) {
            header('Location: /?error=account_not_found');
        }
    }
}

?>