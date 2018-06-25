<?php

/**
 * @brief  : 유저 모델
 * @author : 김민영
 *
 * @file  : User.php
 * @first : 2018/06/24 김민영
 * @last  : 2018/06/24 김민영
**/

namespace App\Models;

use PDO;

class User {

    public function __construct($c)
    {
        $this->c = $c;
    }

    // 유저 체크
    public function userCheck($id)
    {
        $sql = '
            SELECT
                *
            FROM
                user
            WHERE
                id = :id
        ';

        $user = $this->c->db->prepare($sql);
        $user->bindValue(':id', $id);
        $user->execute();
        $result = $user->fetch(PDO::FETCH_OBJ);
        return $result;
    }

    // 유저 등록
    public function userAdd($args)
    {
        $sql = '
            INSERT INTO user
                (id, password, name, rdate)
            VALUE
                (:id, :password, :name, now())
        ';

        $userAdd = $this->c->db->prepare($sql);
        $userAdd->bindValue(':id', $args['id']);
        $userAdd->bindValue(':name', $args['name']);
        $userAdd->bindValue(':password', $args['password']);
        $result = $userAdd->execute();
        return $result;
    }

    // 유저 정보 변경
    public function userModify($args)
    {
        $sql = '
            UPDATE user SET
                password = :password
            WHERE id = :id
        ';

        $userAdd = $this->c->db->prepare($sql);
        $userAdd->bindValue(':id', $args['id']);
        $userAdd->bindValue(':password', $args['password']);
        $result = $userAdd->execute();

        if ($userAdd->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
        
    }
}
