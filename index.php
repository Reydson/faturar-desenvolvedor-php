<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Controllers\ClientController;
use App\Utils\Url;

$clientController = new ClientController();

//sistema de URL amigável, vide o arquivo .htaccess
$page = Url::parts()[0] ?? 'index';

switch ($page) {
    case 'index':
        $clientController->index();
        break;
    case 'clients':
        $clientController->clients();
        break;
    case 'client':
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            $clientController->client();
        }
        if($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            $clientController->clientremove();
        }
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $clientController->store();
        }
        if($_SERVER['REQUEST_METHOD'] == 'PUT') {
            $clientController->update();
        }
        break;
    case 'clientsave':
        
        break;
    default:
        $clientController->e404();
}

?>