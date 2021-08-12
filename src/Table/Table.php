<?php


namespace App\Table;

use \PDO;

abstract class Table
{
    protected PDO $pdo;
    protected string|null $table = null;
    protected $className = null;

    public function __construct(PDO $pdo)
    {
        if($this->table === null){
            throw new \Exception("La classe ". get_class($this) . " n'a pas de propriété table");
        }
        if($this->className === null){
            throw new \Exception("La classe ". get_class($this) . " n'a pas de propriété className");
        }
        $this->pdo = $pdo;
    }

    public function find(int $id) {
        $query = $this->pdo->prepare("SELECT * FROM $this->table WHERE id = :id");
        $query->execute([':id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->className);
        $result =  $query->fetch();
        if($result === false){
            throw new NotFoundException($this->table, $id);
        }
        return $result;
    }
}