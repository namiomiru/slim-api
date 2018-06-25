<?php

/**
 * @brief  : 보드 모델
 * @author : 김민영
 *
 * @file  : Board.php
 * @first : 2018/06/24 김민영
 * @last  : 2018/06/24 김민영
**/

namespace App\Models;

use PDO;

class Board {

    public function __construct ($c)
    {
        $this->c = $c;
    }

    // 글쓰기
    public function write ($args)
    {
        $sql = '
            INSERT INTO board
                (id, subject, content, rdate)
            VALUE
                (:id, :subject, :content, now())
        ';

        $write = $this->c->db->prepare($sql);
        $write->bindValue(':id', $args['id']);
        $write->bindValue(':subject', $args['subject']);
        $write->bindValue(':content', $args['content']);
        $result = $write->execute();

        if ($write->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // 게시물 총 카운트
    public function totalCount ()
    {
        $sql = '
            SELECT
                count(bid) as total
            FROM
                board
        ';
        $totalCount = $this->c->db->prepare($sql);
        $totalCount->execute();
        $result = $totalCount->fetchColumn();

        return $result;
    }

    // 게시물 리스트
    public function list ($start, $limit)
    {
        $sql = '
            SELECT
                *
            FROM board
            ORDER BY bid DESC
            limit '.$start.', '.$limit;
        $list = $this->c->db->prepare($sql);
        $list->execute();
        $result = $list->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }

    // 글 정보
    public function view ($bid)
    {
        $sql = '
            SELECT
                *
            FROM
                board
            WHERE
                bid = :bid
        ';

        $view = $this->c->db->prepare($sql);
        $view->bindValue(':bid', $bid);
        $view->execute();
        $result = $view->fetch(PDO::FETCH_OBJ);
        return $result;
    }

    // 글 수정
    public function modify ($args)
    {
        $sql = '
            UPDATE board SET 
                subject = :subject, content = :content
            where
                bid = :bid
        ';

        $modify = $this->c->db->prepare($sql);
        $modify->bindValue(':bid', $args['bid']);
        $modify->bindValue(':subject', $args['subject']);
        $modify->bindValue(':content', $args['content']);
        $result = $modify->execute();

        return $result;
    }

    // 게시물 삭제
    public function delete ($args)
    {
        $sql = '
            DELETE 
                FROM board
            WHERE
                bid = :bid
            AND
                id = :id
        ';

        $delete = $this->c->db->prepare($sql);
        $delete->bindValue(':bid', $args['bid']);
        $delete->bindValue(':id', $args['id']);
        $result = $delete->execute();

        return $result;
    }
}
