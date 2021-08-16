<?php
namespace App\Helpers;

Class Text {

    /**
     * @param string $content
     * @param int $limit
     * @return string
     * Function which cut a string
     */
    public static function excerpt(string $content, int $limit = 60){
        if (mb_strlen($content) <= $limit){
            return $content;
        }

        $latstSpace = mb_strpos($content, ' ', $limit);
        return mb_substr($content, 0 , $latstSpace) . "...";
    }
}