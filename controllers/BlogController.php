<?php

namespace app\controllers;

use app\components\Twig;
use app\models\PostModel;
use app\components\Router;
use app\components\Pagination;

class BlogController
{
    public function actionIndex($params)
    {

        $id = $params[1];
        if (isset($params[2])) {
            $page = (int)$params[2];
        } else {
            $page = 1;
        }


        $posts = PostModel::findPostsByUser($id, $page);

        $path = '/blog/index/' . $id . '/';
        $totalPosts = PostModel::getTotalPosts($id);
        $pagination = new Pagination($totalPosts, $page, $path);

        echo Twig::getInstance()->render('blog/index.twig', [
                                                             'posts' => $posts,
                                                             'path' => $path,
                                                             'pagination' => $pagination
                                                            ]);
    }

    public function actionAdd()
    {


        $post = new PostModel();
        $post->load($_POST, $_FILES);


        if ($post->validate()) {
            $post->setUserId($_SESSION['userId']);
            $post->save();
            Router::redirect("/blog/index/" . $_SESSION['userId']);
        }

        echo Twig::getInstance()->render('blog/add.twig');
    }

    public function actionSearch($params)
    {
        $id = $params[1];
        if (isset($params[2])) {
            $page = (int)$params[2];
        } else {
            $page = 1;
        }



    }
}