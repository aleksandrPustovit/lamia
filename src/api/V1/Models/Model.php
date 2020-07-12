<?php

namespace App\Api\V1\Models;
use App\Api\V1\Utils\DB;


/**
 * Class Model
 * @package App\Api\V1\Models
 */
abstract class  Model {
	protected $tableName;
	protected $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return \PDO|null
     */
    public function getConnection(){
        return (new DB())->getConnection();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function find(int $id){
        $tt = $this->getConnection()->prepare("SELECT * FROM {$this->tableName} WHERE id = :id" );
        $tt->execute([':id' => $id]);
        return $tt->fetch(\PDO::FETCH_ASSOC);
    }


	abstract public function save();
	abstract public function getTablename();


}