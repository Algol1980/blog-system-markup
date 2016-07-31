<?php
namespace app\controllers;

class UserController {
    public function actionProfile($matches) {
        var_dump($matches);
        echo 'profile action';
    }
}