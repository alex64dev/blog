<?php


namespace App\Table;

use App\Table\Exception\NotFoundException;
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

    public function findAll(): array
    {
        $query = $this->pdo->query("SELECT * FROM {$this->table}");
        $query->setFetchMode(PDO::FETCH_CLASS, $this->className);
        return $query->fetchAll();
    }

    public function delete(int $id)
    {
        $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $result = $query->execute([':id' => $id]);
        if($result === false){
            throw new \Exception("Impossible de supprimer #$id dans la table {$this->table}");
        }
    }

    public function new(array $data): int
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }
        $query = $this->pdo->prepare("INSERT INTO {$this->table} SET " . implode(', ', $fields));
        $result = $query->execute($data);
        if($result === false){
            throw new \Exception("Impossible d'ajouter l'élément dans la table {$this->table}");
        }

        return (int)$this->pdo->lastInsertId();
    }

    public function edit(array $data, int $id)
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }
        $query = $this->pdo->prepare("UPDATE {$this->table} SET " . implode(', ', $fields) ."
                                                WHERE id = :id");
        $result = $query->execute(array_merge($data, ['id' => $id]));
        if($result === false){
            throw new \Exception("Impossible de modifer l'élément dans la table {$this->table}");
        }
    }

    public function queryFetchAll(string $sql): array
    {
        return $this->pdo->query($sql, PDO::FETCH_CLASS, $this->className)->fetchAll();
    }

    public function findBy(string $property)
    {
        $query = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE {} = :property");
        $query->execute([':property' => $property]);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->className);
        $result =  $query->fetch();
        if($result === false){
            throw new NotFoundException($this->table, $property);
        }
        return $result;
    }
}