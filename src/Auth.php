<?php


namespace App;


use App\Security\ForbiddenException;

class Auth
{
    /**
     * @throws ForbiddenException
     */
    public static function check(){
        if (session_status() === PHP_SESSION_NONE){
            session_start();
        }
        if(!isset($_SESSION['auth'])){
            throw new ForbiddenException();
        }
    }
}