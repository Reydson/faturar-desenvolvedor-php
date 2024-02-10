<?php
namespace App\Controllers;

abstract class Controller
{
    /**
     * Imprime uma resposta em JSON.
     */
    protected function jsonResponse($success, $message, $data) {
        if(!$success) {
            http_response_code(500);
        }
        $result = $success ? 'success' : 'error';
        echo json_encode(compact('result', 'message','data'));
    }
}
?>