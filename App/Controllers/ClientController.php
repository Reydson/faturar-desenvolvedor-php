<?php
namespace App\Controllers;

use App\Models\ClientDAO;
use App\Entitys\Client;
use App\Utils\View;
use App\Utils\Url;

class ClientController extends Controller
{
    /**
     * Valida os campos do formulário de cadastro/atualização de cliente
     */
    private function validarDados($dados) {
        //Validação do nome
        if(strlen($dados->name) < 5 || strlen($dados->name) > 30 ) {
            throw new \Exception('O nome do cliente deve ter entre 5 e 30 caracteres');
        }
        //Validação do e-mail
        if(strlen($dados->email) < 5 || strlen($dados->email) > 30 ) {
            throw new \Exception('O email do cliente deve ter entre 5 e 30 caracteres');
        }
        if(strpos($dados->email, '@') === false) {
            throw new \Exception('O email do cliente deve conter  o caractere @');
        }
        //Validação do nome
        if(strlen($dados->phone) != 14 && strlen($dados->phone) != 15 ) {
            throw new \Exception('Telefone do cliente inválido');
        }
    }

    /**
     * Exibe a página inicial de gerenciamento de clientes.
     */
    public function index()
    {
        View::load('client/index');
    }

    /**
     * Retorna um JSON com as informações dos clientes. 
     */
    public function clients()
    {
        try {
            $clienteDAO = new ClientDAO();
            $page = Url::parts()[1] ??  1;
            $this->jsonResponse(
                true,
                "",
                [
                    'pageCount' => $clienteDAO->pageCount(),
                    'clients' =>$clienteDAO->paginate($page)
                ]
            );
        } catch(\Exception $e){
            $this->jsonResponse(false, $e->getMessage(), []);
        }
    }

    /**
     * Retorna um JSON com as informações de determinado cliente a partir de sua id. 
     */
    public function client()
    {
        try {
            $clienteDAO = new ClientDAO();
            $id = Url::parts()[1];
            $this->jsonResponse(
                true,
                "",
                $clienteDAO->find($id)
            );
        } catch(\Exception $e){
            $this->jsonResponse(false, $e->getMessage(), []);
        }
    }

    /**
     * Remove um cliente a partir de sua id. 
     */
    public function clientremove()
    {
        try {
            $clienteDAO = new ClientDAO();
            $id = Url::parts()[1];
            $clienteDAO->find($id)->delete();
            $this->jsonResponse(
                true,
                "",
                ""
            );
        } catch(\Exception $e){
            $this->jsonResponse(false, $e->getMessage(), []);
        }
    }

    /**
     * Armazena um novo cliente. 
     */
    public function store()
    {
        try {
            $clienteDAO = new ClientDAO();
            $dados = json_decode(file_get_contents('php://input'));
            $this->validarDados($dados);

            $cliente = new Client(null, $dados->name, $dados->email, $dados->phone);
            $cliente->save();

            $this->jsonResponse(
                true,
                "",
                ""
            );
        } catch(\Exception $e){
            $this->jsonResponse(false, $e->getMessage(), []);
        }
    }

    /**
     * Atualiza um cliente já existente. 
     */
    public function update()
    {
        try {
            $clienteDAO = new ClientDAO();
            $dados = json_decode(file_get_contents('php://input'));
            $this->validarDados($dados);

            $cliente = $clienteDAO->find($dados->id);
            $cliente->setName($dados->name);
            $cliente->setEmail($dados->email);
            $cliente->setPhone($dados->phone);
            $cliente->update();

            $this->jsonResponse(
                true,
                "",
                ""
            );
        } catch(\Exception $e){
            $this->jsonResponse(false, $e->getMessage(), []);
        }
    }

    /**
     * Exibe uma página de erro 404. 
     */
    public function e404()
    {
        http_response_code(404);
        View::load('404');
    }
}
?>