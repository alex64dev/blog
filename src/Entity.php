<?php


namespace App;


class Entity
{

    public static function hydrate(Object $entity, array $data, array $keys)
    {
        foreach ($keys as $key) {
            $setter = "set".str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
            $entity->$setter($data[$key]);
        }
    }
}