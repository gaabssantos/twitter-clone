<?php

namespace App\Models;

use MF\Model\Model;

class Tweet extends Model {
    private $id;
    private $id_usuario;
    private $tweet;
    private $date;

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function save() {
        $query = "insert into tweets (tweet, id_usuario) values (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $this->__get('tweet'));
        $stmt->bindValue(2, $this->__get('id_usuario'));
        $stmt->execute();

        return $this;
    }

    public function getAll() {
        $query = 
            "select 
                t.id, 
                t.id_usuario, 
                t.tweet, 
                u.name, 
                DATE_FORMAT(t.date, '%d/%m/%Y %H:%i') as date
            from 
                tweets as t
            left join
                users as u 
            on 
                (t.id_usuario = u.id)
            where 
                t.id_usuario = ?
            or
                t.id_usuario in (select id_usuario_following from users_followers where id_usuario = ?)
            order by t.date desc";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $this->__get('id_usuario'));
        $stmt->bindValue(2, $this->__get('id_usuario'));
        $stmt->execute();

        $tweets = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $tweets;
    }

    public function remove() {
        $query = 'delete from tweets where id = ?';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $this->__get('id'));
        $stmt->execute();
    }
}

?>