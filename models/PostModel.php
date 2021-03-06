<?php

namespace app\models;

use app\components\MySQLConnector;
use app;

/**
 * Class PostModel
 *
 * @property string $title
 * @property string $body
 * @property string $image
 * @property string $createdAt
 */
class PostModel
{
    private $id;
    private $title;
    private $body;
    private $image;
    private $createdAt;
    private $userId;


    public function __construct($id = null, $title = null, $body = null, $image = null, $createdAt = null, $userId = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->body = $body;
        $this->image = $image;
//        $this->createdAt = $createdAt;
        if (is_string($createdAt)) {
            $createdAt = new \DateTime($createdAt);
        }
        if ($createdAt instanceof \DateTime) {
            $this->createdAt = $createdAt;
        }
        $this->userId = $userId;
    }


    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return null
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return null
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return null
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setUserId($value)
    {
        $this->userId = $value;
    }


    public function save()
    {
        if ($this->id) {
            $sql = 'UPDATE post SET title = :title, body = :body, image = :image, createdAt = :createdAt, user_id = :user_id,';
        } else {
            $sql = 'INSERT INTO post (title, body, image, createdAt, user_id) VALUES (:title, :body, :image, :createdAt, :user_id)';
            $this->createdAt = new \DateTime();
        }
        $name = false;

        if (
            $this->image['error'] == 0 &&
            is_uploaded_file($this->image['tmp_name'])
        ) {
            $imageInfo = getimagesize($this->image['tmp_name']);
            if ($imageInfo) {
                $pathInfo = pathinfo($this->image['name']);

                $name = "img_" .
                    time() . "." .
                    $pathInfo['extension'];

//                $img = new Imagick($this->image['tmp_name']);
//                $img->thumbnailImage(100, 0);
//                $img->writeImage("img/thumb_" . $name);

                move_uploaded_file(
                    $this->image['tmp_name'], "img/" . $name
                );
                $this->image = $name;
            }
        } else {
            $this->image = NULL;
        }

        $statement = MySQLConnector::getInstance()->getPDO()->prepare($sql);
        $vars = [
            ':title' => $this->title,
            ':body' => $this->body,
            ':image' => $this->image,
            ':createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            ':user_id' => $this->userId
        ];
        if ($statement->execute($vars)) {
            if (!$this->id) {
                $this->id = intval(MySQLConnector::getInstance()->getPDO()->lastInsertId());
            }

            return true;
        } else {
            var_dump($statement->errorInfo());
        }

        return false;
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        } else {
            throw new Exception("Unknown property");
        }
    }

    /**
     * @param $userId
     * @param int $page
     * @return PostModel[]
     */
    public static function findPostsByUser($userId, $page = 1)
    {

        $shift = ($page - 1) * app\PER_PAGE;
        $sql = 'SELECT * FROM post WHERE user_id = :userId LIMIT :limit OFFSET :offset';
        $statement = MySQLConnector::getInstance()->getPDO()->prepare($sql);
        $statement->bindValue(':userId', (int)$userId, \PDO::PARAM_INT);
        $statement->bindValue(':limit', (int)app\PER_PAGE, \PDO::PARAM_INT);
        $statement->bindValue(':offset', (int)$shift, \PDO::PARAM_INT);
        if ($statement->execute()) {
            $rows = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $posts = [];

            foreach ($rows as $row) {
                $post = new PostModel(
                    $row['id'],
                    $row['title'], $row['body'],
                    $row['image'],
                    $row['createdAt'],
                    $row['userId']
                );
                $posts[] = $post;
            }
        }
        return $posts;
    }

    public function load($post, $files)
    {
        $fields = [
            'title',
            'body',
            'image',
        ];

        foreach ($fields as $field) {
            if (isset($post[$field])) {
                $this->$field = $post[$field];
            }

            if (isset($files[$field])) {
                $this->$field = $files[$field];
            }
        }
    }

    public function validate()
    {
        $required = [
            'title',
            'body',
        ];

        foreach ($required as $req) {
            if (!$this->$req) {
                return false;
            }
        }

        return true;
    }

    public static function getTotalPosts($userId)
    {
        $sql = 'SELECT COUNT(*) FROM post WHERE user_id = :userId';
        $statment = MySQLConnector::getInstance()->getPDO()->prepare($sql);
        $statment->bindValue(':userId', (int)$userId, \PDO::PARAM_INT);
        if ($statment->execute()) {
            $totalPosts = $statment->fetch();
            return $totalPosts[0];
        } else {
            return false;
        }
    }

    public static function getTotalPostsSearch($searchText)
    {
        $sql = 'SELECT COUNT(*) FROM post WHERE title LIKE :searchText OR body LIKE :searchText';
        $statment = MySQLConnector::getInstance()->getPDO()->prepare($sql);
        $statment->bindValue(':searchText', '%' . $searchText . '%', \PDO::PARAM_STR);
        if ($statment->execute()) {
            $totalPosts = $statment->fetch();
            return $totalPosts[0];
        } else {
            return false;
        }
    }

    public static function getTotalImages($userId)
    {
        $sql = 'SELECT COUNT(*) FROM post WHERE user_id = :userId AND image IS NOT NULL';
        $statment = MySQLConnector::getInstance()->getPDO()->prepare($sql);
        $statment->bindValue(':userId', (int)$userId, \PDO::PARAM_INT);
        if ($statment->execute()) {
            $totalPosts = $statment->fetch();
            return $totalPosts;
        } else {
            return false;
        }
    }

    public static function addCountInfo($users)
    {
        $newUsers = [];
        foreach ($users as $user) {

            $countPosts = PostModel::getTotalPostCount($user->id);
            $countImages = PostModel::getTotalImages($user->id);
            $user->countPosts = $countPosts[0];
            $user->countImages = $countImages[0];
            $newUsers[] = $user;
        }
        return $newUsers;
    }

    public function delete()
    {
        if ($this->id) {
            $sql = "DELETE FROM post WHERE id = :id";
            $statement = MySQLConnector::getInstance()->getPDO()->prepare($sql);
            if ($statement->execute([
                ':id' => $this->id,
            ])
            ) {
                $this->id = null;
                return true;
            }
        }

        return false;
    }

    public static function search($page = 1, $searchText, $userId = false)
    {
        $shift = ($page - 1) * app\PER_PAGE;
        if ($userId) {
            $sql = 'SELECT * FROM post WHERE user_id = :userId
            AND (title LIKE :searchText OR body LIKE :searchText)
            LIMIT :limit OFFSET :offset';
        } else {
            $sql = 'SELECT * FROM post WHERE title LIKE :searchText OR body LIKE :searchText
            LIMIT :limit OFFSET :offset';
        }

        $statement = MySQLConnector::getInstance()->getPDO()->prepare($sql);
        $statement->bindValue(':userId', (int)$userId, \PDO::PARAM_INT);
        $statement->bindValue(':limit', (int)app\PER_PAGE, \PDO::PARAM_INT);
        $statement->bindValue(':offset', (int)$shift, \PDO::PARAM_INT);
        $statement->bindValue(':searchText', '%' . $searchText . '%', \PDO::PARAM_STR);
        if ($statement->execute()) {
            $rows = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $posts = [];

            foreach ($rows as $row) {
                $post = new PostModel(
                    $row['id'],
                    $row['title'],
                    $row['body'],
                    $row['image'],
                    $row['createdAt'],
                    $row['userId']
                );
                $posts[] = $post;
            }
        }
        return $posts;
    }


}