<?php


namespace App\api\V1\Utils;


/**
 * Class DB
 * @package App\api\V1\Utils
 */
class DB
{
    private $dbConnection = null;


    public function __construct()
    {
        $host = '127.0.0.2';
        $port = '3306';
        $db   = 'lamia';
        $user = 'root';
        $pass = '';
        try {
            $this->dbConnection = new \PDO(
                "mysql:host=$host;port=$port;charset=utf8mb4;dbname=$db",
                $user,
                $pass
            );
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    /**
     * @return \PDO|null
     */
    public function getConnection()
    {
        return $this->dbConnection;
    }

}
