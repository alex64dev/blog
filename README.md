

<h1>Blog project</h1>


Blog create from scratch based on Php @Grafikart's tutorial. Contain bdd.sql file helping create database.
Altorouter is use to manage routing system.
In a second time, add MVC structure with template engine Twig.

## Database
To use bdd.sql file create a new Database with 2 options:
<ol>
    <li>Copy and paste all queries to create all tables</li>
    <li>In terminal, move to directory and connect your DBMS with DATABASENAME and load file with command "SOURCE bdd.sql;".</li>
</ol>
Remove te file when it's done

## Fixtures
Thanks to php file fill.php in commands folder the database can be filled with fake data. 
Faker library is used to simulate data and make tests.
Launch it in terminal with command: php commands/fill.php

Remove te file when it's done


## Libraries and formations used

* **[Php Grafikart's formation  »](https://grafikart.fr/tutoriels/presentation-tp-1161#autoplay)**

* **[Altorouter  »](https://github.com/dannyvankooten/AltoRouter)**

* **[PhpStan  »](https://github.com/phpstan/phpstan)**

* **[Faker  »](https://github.com/FakerPHP/Faker)**

* **[Valitron  »](https://github.com/vlucas/valitron)**