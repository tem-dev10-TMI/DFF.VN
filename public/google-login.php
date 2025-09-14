<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../controller/UserController.php';

$controller = new UserController();
$controller->loginWithGoogle(); // chỉ tạo authUrl và redirect
