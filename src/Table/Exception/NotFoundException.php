<?php


namespace App\Table\Exception;


use Throwable;

class NotFoundException extends \Exception
{
    public function __construct(string $table, $id)
    {
        $this->message = "Aucun enregistrement ne correspond à l'élémant #$id dans la table $table";
    }
}