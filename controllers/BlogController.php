<?php

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

        require_once __DIR__ . DIRECTORY_SEPARATOR .
            '..' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR
            . 'blog' . DIRECTORY_SEPARATOR . 'index.php';

        $path = '/blog/index/' . $id . '/';
        $totalPosts = PostModel::getTotalPosts($id);
        if ($pagination = new Pagination($totalPosts, $page, $path)) {

            require_once __DIR__ . DIRECTORY_SEPARATOR .
                '..' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR
                . 'components' . DIRECTORY_SEPARATOR . 'pagination.php';
        }
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


        require_once __DIR__ . DIRECTORY_SEPARATOR .
            '..' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR
            . 'blog' . DIRECTORY_SEPARATOR . 'add.php';
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