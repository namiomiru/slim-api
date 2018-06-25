<?php

use App\Controllers\UserController;
use App\Controllers\BoardController;

// 게시판 그룹
$app->group('/board', function () use ($loginCheck) {
    $this->post('/write', BoardController::class . ':write');
    $this->post('/{bid}/modify', BoardController::class . ':modify');
    $this->delete('/{bid}', BoardController::class . ':delete');
    $this->get('/page/{page}', BoardController::class . ':page');
    $this->get('/view/{bid}', BoardController::class . ':view');
    
});

// 유저 그룹
$app->group('/user', function () use ($loginCheck) {
    $this->post('/join', UserController::class . ':join');
    $this->post('/login', UserController::class . ':login');
    $this->post('/modify', UserController::class . ':modify');
});