<?php

session_start();

// db config 로드 $settings 변수 
require_once __DIR__ . '/../config/database.php';

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);

$container = $app->getContainer();

$container['db'] = function () use ($settings) {
    try {
        $pdo = new PDO(
            sprintf(
                'mysql:host=%s;dbname=%s;port=%s;charset=%s',
                $settings['host'],
                $settings['name'],
                $settings['port'],
                $settings['charset']
            ),
            $settings['username'],
            $settings['password']
        );
    } catch (PDOException $e) {
        // 데이터베이스 연결 실패
        echo "데이터베이스 연결 실패";
        exit;
    }
    return $pdo;
};

$container['User'] = function($container){
    return new \App\Models\User($container);
};
$container['Board'] = function($container){
    return new \App\Models\Board($container);
};

require_once __DIR__ . '/../routes/api.php';