<?php

/**
 * @brief  : 유저 컨트롤러
 * @author : 김민영
 *
 * @file  : UserController.php
 * @first : 2018/06/24 김민영
 * @last  : 2018/06/24 김민영
**/

namespace App\Controllers;

use PDO;

class UserController extends Controller
{

    // $_SESSION['isLogin'] = 'true';

    // 회원가입
    public function join ($request, $response, $args)
    {
        $id       = $request->getParam('id');
        $name = $request->getParam('name');
        $password = $request->getParam('password');
    
        if(!($id && $password)) {
            $data = array(
                'result' => 'false',
                'msg'    => '올바른 데이터 값이 아닙니다.'
            );

            return $response->withJson($data);
        }

        // 아이디 중복 체크
        $user = $this->c->User->userCheck($id);
        if ($user) {

            $data = array(
                'result' => 'false',
                'msg'    => '중복된 아이디 입니다.'
            );

        } else {

            // 패스워드 해쉬
            $password = password_hash($password, PASSWORD_DEFAULT);

            $userAdd_data = array(
                'id'       => $id,
                'name'     => $name,
                'password' => $password
            );

            // 회원 가입
            $userAdd = $this->c->User->userAdd($userAdd_data);

            if ($userAdd) {
                
                $data = array(
                    'result' => 'true',
                    'msg'    => '회원 가입 되었습니다.'
                );

            } else {
                
                $data = array(
                    'result' => 'false',
                    'msg'    => '회원 가입에 실패 하였습니다. 관리자에게 문의 주세요.'
                );
            }
        }
        return $response->withJson($data);
    }

    // 로그인
    public function login ($request, $response, $args)
    {
        if($_SESSION['isLogin'] === 'true') {

            $data = array(
                'result' => 'true',
                'msg'    => '이미 로그인 하였습니다.'
            );
            return $response->withJson($data);
        }

        $id       = $request->getParam('id');
        $password = $request->getParam('password');

        // post 값 체크
        if(!($id && $password)) {
            $data = array(
                'result' => 'false',
                'msg'    => '올바른 데이터 값이 아닙니다.'
            );
        } else {

            // 유저 체크
            $user = $this->c->User->userCheck($id);
        
            if(!$user) {
                $data = array(
                    'result' => 'false',
                    'msg'    => '아이디가 없습니다.'
                );
            }

            // 패스워드 확인
            $confirm = password_verify($password, $user->password);

            if(!$confirm) {

                $data = array(
                    'result' => 'false',
                    'msg'    => '패스워드가 틀렸습니다.'
                );
            } else {

                //세션 저장
                $_SESSION['id']      = $id;
                $_SESSION['isLogin'] = 'true';
                
                $data = array(
                    'result' => 'true',
                    'msg'    => '로그인 성공 하였습니다.'
                );
            }
            
        }

        return $response->withJson($data);
    }

    // 회원정보 수정
    public function modify ($request, $response, $args)
    {
        // 로그인 체크
        if($_SESSION['isLogin'] !== 'true') {

            $data = array(
                'result' => 'false',
                'msg'    => '로그인 후 수정이 가능 합니다.'
            );
        } else {

            $id       = $_SESSION['id'];
            $password = $request->getParam('password');

            // 패스워드 해시
            $password = password_hash($password, PASSWORD_DEFAULT);

            $userModify_data = array(
                'id'       => $id,
                'password' => $password
            );

            // 회원 정보 변경
            $user = $this->c->User->userModify($userModify_data);

            if ($user) {

                $data = array(
                    'result' => 'true',
                    'msg'    => '회원 정보가 변경 되었습니다.'
                ); 
            } else {
                $data = array(
                    'result' => 'false',
                    'msg'    => '회원 정보가 변경이 실패 하였습니다.'
                ); 
            }
            
        }
        
        return $response->withJson($data);
    }

}