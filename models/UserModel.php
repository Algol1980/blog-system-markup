<?php

/**
 * Class UserModel
 * @property int $id
 * @property string $email
 * @property string $firstName
 * @property string $lastName
 * @property string $password
 * @property DateTime $createdAt
 */
class UserModel
{
    private $id;
    private $email;
    private $firstName;
    private $lastName;
    private $password;
    private $createdAt;

//    const DB_FILENAME = 'users.db';

    public function __construct($id = null, $email = null, $firstName = null, $lastName = null, $password = null, $createdAt = null)
    {
        $this->id = intval($id);
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->password = $password;
        if (is_string($createdAt)) {
            $createdAt = new DateTime($createdAt);
        }
        if ($createdAt instanceof DateTime) {
            $this->createdAt = $createdAt;
        }
    }

    public function save()
    {
        if ($this->id) {
            $sql = 'UPDATE user SET email = :e, firstName = :fN, lastName = :lN, password = :pwd, createdAt = :createdAt';
        } else {
            $sql = 'INSERT INTO user (email, firstName, lastName, password, createdAt) VALUES (:e, :fN, :lN, :pwd, :createdAt)';
            $this->createdAt = new DateTime();
        }

        $statement = MySQLConnector::getInstance()->getPDO()->prepare($sql);
        $vars = [
            ':e' => $this->email,
            ':fN' => $this->firstName,
            ':lN' => $this->lastName,
            ':pwd' => sha1($this->password),
            ':createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
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

    public function delete()
    {
        if ($this->id) {
            $sql = "DELETE FROM user WHERE (id = :id)";
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

    public function load($array)
    {
        foreach ($array as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $array[$key];
            }
        }
    }

    public function validate()
    {
        $required = [
            'email',
            'firstName',
            'lastName',
            'email',
            'password'
        ];

        foreach ($required as $key) {
            if (!$this->$key) {
                return false;
            }
        }

        return true;
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
     * @param $id
     * @return UserModel user model
     */
    public static function getUserById($id)
    {
        $statement = MySQLConnector::getInstance()->getPDO()->prepare('SELECT * FROM user WHERE id = :id');
        $statement->execute([
            ':id' => $id,
        ]);
        if ($user = $statement->fetch(PDO::FETCH_ASSOC)) {
            $user = new UserModel($user['id'], $user['email'], $user['firstName'], $user['lastName'], $user['password'], $user['createdAt']);
            return $user;
        }

        return null;
    }

    /**
     * @param int $limit amount of return objects
     * @param bool $counters flag
     * @return UserModel[] array of user models
     */
    public static function getUsers($limit, $counters = false)
    {   if($counters) {
            $sql = 'SELECT *,
                    (SELECT COUNT(*) FROM post WHERE  user.id=post.user_id) AS countPosts,
                    (SELECT COUNT(*) FROM post WHERE  user.id=post.user_id AND image IS NOT NULL) AS countImages
                    FROM user LIMIT :limit';
        }
        else {
            $sql = 'SELECT * FROM user LIMIT :limit';
        }
        $statement = MySQLConnector::getInstance()->getPDO()->prepare($sql);
        $statement->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        if ($statement->execute()) {
            $users = [];
            $user = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach ($user as $item) {
                    $newItem = new UserModel($item['id'],
                        $item['email'],
                        $item['firstName'],
                        $item['lastName'],
                        $item['password'],
                        $item['createdAt']
                    );
                if($counters) {
                    $newItem->countPosts = $item['countPosts'];
                    $newItem->countImages = $item['countImages'];
                }

                $users[] = $newItem;
            }
            }

        return $users;
    }


    /**
     * @param $email
     * @param $password
     * @return UserModel
     */
    public static function checkUser($email, $password)
    {
        $password = sha1($password);
        $statement = MySQLConnector::getInstance()->getPDO()->prepare('SELECT * FROM user WHERE email = :email AND password = :pwd');
        $statement->execute([
            ':email' => $email,
            ':pwd' => $password,
        ]);
        if ($user = $statement->fetch(PDO::FETCH_ASSOC)) {
            $user = new UserModel($user['id'], $user['email'], $user['firstName'], $user['lastName'], $user['password'], $user['createdAt']);
            return $user;
        }

        return null;
    }

}