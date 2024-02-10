<?php
namespace App\Utils;

class Url
{
    /**
     * Divide a url em partes.
     */
    static function parts() {
        if (isset($_GET['url'])) {
            return explode( '/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
?>