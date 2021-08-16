<?php


namespace App\Html;


class Alert
{
    public static function render(string $type, string $message): string
    {
        return "<div class='row'>
        <div class='col-md-12'>
            <div class='alert alert-{$type}' role='alert'>
                {$message}
            </div>
        </div>
    </div>";
    }
}