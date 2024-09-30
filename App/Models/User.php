<?php

namespace App\Models;

use MF\Model\Model;

class User extends Model {
    private $id;
    private $name;
    private $email;
    private $password;
    private $error;

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function save() {
        $query = "insert into users (name, email, password) values (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $this->__get('name'));
        $stmt->bindValue(2, $this->__get('email'));
        $stmt->bindValue(3, $this->__get('password'));
        $stmt->execute();

        return $this;
    }

    public function findUserByEmail() {
        $query = 'select name, email from users where email = ?';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $this->__get('email'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findUser() {
        $query = 'select id, name, email from users where email = ? and password = ?';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $this->__get('email'));
        $stmt->bindValue(2, $this->__get('password'));
        $stmt->execute();

        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user) {
            if ($user['id'] != '' && $user['name']) {
                $this->__set('id', $user['id']);
                $this->__set('name', $user['name']);
            }
        }

        return $user;
    }

    public function isValid() {
        if (strlen($this->__get('name')) < 3 || 
        strlen($this->__get('email')) < 3 || 
        strlen($this->__get('password')) < 3) {
            $this->__set('error', 'str_length');
            return false;
        }

        if ($this->findUserByEmail()) {
            $this->__set('error', 'email_found');
            return false;
        }

        return true;
    }

    public function getAll() {
        $query = 
        "select 
            u.id, 
            u.name, 
            u.email, 
            (
                select count(*) 
            from 
                users_followers as uf 
            where 
                uf.id_usuario = :id_user and uf.id_usuario_following = u.id
            ) as following_sn 
            from 
                users as u 
            where 
                u.name like :name and u.id != :id_user";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':name', '%'.$this->__get('name').'%');
        $stmt->bindValue(':id_user', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $query = "select name from users where id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function followUser($id) {
        $query = "insert into users_followers (id_usuario, id_usuario_following) values (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $this->__get('id'));
        $stmt->bindValue(2, $id);
        $stmt->execute();
    }

    public function unfollowUser($id) {
        $query = 
        "delete from users_followers where id_usuario = ? and id_usuario_following = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $this->__get('id'));
        $stmt->bindValue(2, $id);
        $stmt->execute();
    }

    public function getTotalTweets() {
        $query = "select count(*) as total_tweets from tweets where id_usuario = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getTotalFollowing() {
        $query = "select count(*) as total_following from users_followers where id_usuario = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getTotalFollowers() {
        $query = "select count(*) as total_followers from users_followers where id_usuario_following = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}

?>