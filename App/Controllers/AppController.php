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

    public function whoFollow() {
        $this->checkUserLogged();

        $searchFor = isset($_GET['searchFor']) ? $_GET['searchFor'] : '';

        if ($searchFor != '') {
            $user = Container::getModel('User');

            $user->__set('name', $searchFor);
            $user->__set('id', $_SESSION['id']);

            $users = $user->getAll();

            $this->view->searchFor = $users;
        } else {
            $this->view->searchFor = [];
        }
        
        $this->render('whoFollow');
    }

    public function action() {
        $this->checkUserLogged();

        $action = isset($_GET['action']) ? $_GET['action'] : '';
        $id = isset($_GET['id']) ? $_GET['id'] : '';

        $user = Container::getModel('User');
        $user->__set('id', $_SESSION['id']);

        $name = base64_encode($user->getUserById($id)[0]['name']);

        if ($action == 'follow') {
            if ($user->getAll()[0]['following_sn'] == 0) {
                $user->followUser($id);
                header('Location: /quem_seguir?following='.$name);
            }
        } else if ($action == 'unfollow') {
            if ($user->getAll()[0]['following_sn'] == 1) {
            $user->unfollowUser($id);
            header('Location: /quem_seguir');
            }
        }
    }

    public function remove() {
        $this->checkUserLogged();

        $id = isset($_GET['tweet_id']) ? $_GET['tweet_id'] : '';

        $tweet = Container::getModel('Tweet');
        $tweet->__set('id', $id);

        $tweet->remove();

        header('Location: /timeline?remove=success');
    }
}

?>