<?php


namespace App\Table;


use App\Model\User;
use App\Table\Exception\NotFoundException;
use \PDO;

final class UserTable extends Table
{
    protected ?string $table = 'user';
    protected $className = User::class;

    public function findByUsername(string $username)
    {
        $query = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE username = :username");
        $query->execute([':username' => $username]);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->className);
        $result =  $query->fetch();
        if($result === false){
            throw new NotFoundException($this->table, $username);
        }
        return $result;
    }

}