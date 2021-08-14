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
        $query = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $query->execute([':id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->className);
        $result =  $query->fetch();
        if($result === false){
            throw new NotFoundException($this->table, $id);
        }
        return $result;
    }

    public function exist(string $field, $value, ?int $exception = null): bool
    {
        $sql ="SELECT COUNT(id) FROM {$this->table} WHERE $field = ?";
        $params = [$value];
        if($exception !== null){
            $sql .= " AND id != ?";
            $params[] = $exception;
        }
        $query = $this->pdo->prepare($sql);
        $query->execute($params);
        return  (int)$query->fetch(PDO::FETCH_NUM)[0] > 0;
    }
}