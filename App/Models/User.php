<?php

namespace App\Models;

use MF\Model\Model;

class User extends Model {
    private $id;
    private $name;
    private $email;
    private $password;

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
}

?>