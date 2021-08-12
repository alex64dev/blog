<?php

use App\Connect;

require dirname(__DIR__).'/vendor/autoload.php';

$pdo = $pdo = Connect::getPdo();

$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
$pdo->exec('TRUNCATE TABLE post_category');
$pdo->exec('TRUNCATE TABLE post');
$pdo->exec('TRUNCATE TABLE category');
$pdo->exec('TRUNCATE TABLE user');
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

$faker = Faker\Factory::create();

$posts = [];
$categories = [];

for ($i = 0; $i < 50; $i++){
    $pdo->exec("INSERT INTO post SET name = '{$faker->sentence()}', slug = '{$faker->slug()}', created_at = '{$faker->date()} {$faker->time}', content = '{$faker->paragraphs(rand(3,15), true)}'");
    $posts[] = $pdo->lastInsertId();
}

for ($i = 0; $i < 5; $i++){
    $pdo->exec("INSERT INTO category SET name = '{$faker->sentence(3)}', slug = '{$faker->slug()}'");
    $categories[] = $pdo->lastInsertId();
}

foreach ($posts as $post){
    $cats = $faker->randomElements($categories, rand(0, count($categories)));
    foreach ($cats as $cat) {
        $pdo->exec("INSERT INTO post_category SET post_id = $post, category_id = $cat");
    }
}

$password = password_hash('admin', PASSWORD_BCRYPT);
$pdo->exec("INSERT INTO user SET username = 'admin', password = '$password'");