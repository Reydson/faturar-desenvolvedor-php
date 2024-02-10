<?php
namespace App\Utils;

class View
{
    /**
     * Carrega uma view.
     */
    static function load($viewName) {
        require_once(__DIR__.'/../../views/'.$viewName.'.php');
    }
}
?>