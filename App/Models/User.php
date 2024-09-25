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
}

?>