<?php

/**
 * @brief  : 보드 컨트롤러
 * @author : 김민영
 *
 * @file  : BoardController.php
 * @first : 2018/06/24 김민영
 * @last  : 2018/06/24 김민영
**/

namespace App\Controllers;

use PDO;

class BoardController extends Controller
{

    // 글쓰기
    public function write ($request, $response, $args)
    {

        // 로그인 체크
        if($_SESSION['isLogin'] !== 'true') {

            $data = array(
                'result' => 'false',
                'msg'    => '로그인 후 글쓰기가 가능 합니다.'
            );

            return $response->withJson($data);
        }

        $subject = $request->getParam('subject');
        $content = $request->getParam('content');

        $write_data = array(
            'id'       => $_SESSION['id'],
            'subject'  => $subject,
            'content'  => $content
        );

        $write = $this->c->Board->write($write_data);

        if (!$write) {

            $data = array(
                'result' => 'false',
                'msg'    => '글쓰기에 실패 했습니다. 관리자에게 문의 주세요.'
            );
        } else {

            $data = array(
                'result' => 'true',
                'msg'    => '글 등록이 되었습니다.'
            );
        }
        return $response->withJson($data);
        
    }

    // 게시물 상세
    public function view ($request, $response, $args)
    {

        // 데이터 값 체크
        if (!$args) {
            $data = array(
                'result' => 'false',
                'msg'    => '올바른 데이터 값이 아닙니다.'
            );
        }

        // 게시물 상세
        $view = $this->c->Board->view($args['bid']);

        if (!$view) {

            $data = array(
                'result' => 'false',
                'msg'    => '없는 글 번호 입니다.'
            );
        } else {

            $data = array(
                'result' => 'true',
                'data'   => $view,
            );
        }

        return $response->withJson($data);
        
    }

    // 게시물 수정
    public function modify ($request, $response, $args)
    {
        // 로그인 체크
        if($_SESSION['isLogin'] !== 'true') {

            $data = array(
                'result' => 'false',
                'msg'    => '로그인 후 수정이 가능 합니다.'
            );

            return $response->withJson($data);
        }

        $check = $this->c->Board->view($args['bid']);

        if ($check->id !== $_SESSION['id'] ) {

            $data = array(
                'result' => 'false',
                'msg'    => '권한이 없습니다..'
            );

            return $response->withJson($data);
        }

        $subject = $request->getParam('subject');
        $content = $request->getParam('content');

        $modify_data = array(
            'bid'     => $args['bid'],
            'subject' => $subject,
            'content' => $content
        );

        // 게시물 정보 변경
        $modify = $this->c->Board->modify($modify_data);

        if ($modify) {

            $data = array(
                'result' => 'true',
                'msg'    => '글이 수정 되었습니다.'
            ); 
        }
        
        return $response->withJson($data);
    }

    // 게시물 삭제
    public function delete ($request, $response, $args)
    {

        // 로그인 체크
        if($_SESSION['isLogin'] !== 'true') {

            $data = array(
                'result' => 'false',
                'msg'    => '로그인 후 글쓰기가 가능 합니다.'
            );

            return $response->withJson($data);
        }

        $check = $this->c->Board->view($args['bid']);

        if ($check->id !== $_SESSION['id'] ) {

            $data = array(
                'result' => 'false',
                'msg'    => '권한이 없습니다..'
            );

            return $response->withJson($data);
        }

        $delete_data = array(
            'bid' => $args['bid'],
            'id'  => $_SESSION['id']
        );

        // 게시물 삭제
        $delete = $this->c->Board->delete($delete_data);

        if (!$delete) {

            $data = array(
                'result' => 'false',
                'msg'    => '글 삭제 실패 하였습니다. 관리자에게 문의 주세요.'
            );
        } else {

            $data = array(
                'result' => 'true',
                'data'   => '글 삭제 하였습니다.',
            );
        }

        return $response->withJson($data);
        
    }

    // 페이지 리스트
    public function page ($request, $response, $args)
    {
        $page = $args['page'];

        // 전체 게시물 수
        $totalCount = $this->c->Board->totalCount();

        $totalRows = $totalCount;

        // 페이지당 게시물 수
        $perPage = 10;

        // 전체 페이지 수
        $totalPage = ceil($totalRows / $perPage);

        if (empty($totalRows)) {

            $data = array(
                'result' => 'false',
                'data'   => '글이 존재 하지 않습니다.',
            );

            return $response->withJson($data);
        }

      if($page < 1 || ($totalPage && $page > $totalPage)) {

            $data = array(
                'result' => 'false',
                'data'   => '존재하지 않는 페이지 입니다.',
            );

            return $response->withJson($data);
        }

        // 시작
        if ($page > 1) {

			$start = $page * $perPage - $perPage;

		} else {

			$start = 0;
        }

        // 리미트
        $limit = $perPage;

        $blist = $this->c->Board->list($start, $limit);

        return $response->withJson($blist);
    }
}