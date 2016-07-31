<?php

namespace app\controllers;

use app\models\UserModel;
use app\components\Router;
use app\components\Twig;

class SiteController

{
    public function actionIndex()
    {


        $users = UserModel::getUsers(5, $counters=true);

        echo Twig::getInstance()->render('site/index.twig', [
            'isGuest' => !isset($_SESSION['user']),
            'users' => $users
        ]);

    }

    public function actionLogin()
    {

        if (!empty($_POST)) {
            if (isset($_POST['email']) && isset($_POST['password'])) {
                if ($user = UserModel::checkUser($_POST['email'], $_POST['password'])) {
                    $_SESSION['user'] = true;
                    $_SESSION['firstName'] = $user->firstName;
                    $_SESSION['lastName'] = $user->lastName;
                    $_SESSION['userId'] = $user->id;
                    Router::redirect('/');
                }
            }
        } else {
            echo Twig::getInstance()->render('site/login.twig');
        }
    }

    public function actionLogout()
    {
        session_start();
        session_destroy();
        Router::redirect('/');
    }

    public function actionRegister()
    {
        if (!empty($_POST)) {
            $user = new UserModel();
            $user->load($_POST);
            if ($user->validate()) {
                if ($user->save()) {
                    $_SESSION['user'] = true;
                    $_SESSION['firstName'] = $user->firstName;
                    $_SESSION['lastName'] = $user->lastName;
                    $_SESSION['userId'] = $user->id;
                    Router::redirect('/');
                }
            }
        }


        echo Twig::getInstance()->render('site/register.twig');
    }


}