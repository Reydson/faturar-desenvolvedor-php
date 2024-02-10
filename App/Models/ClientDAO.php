<?php
namespace App\Models;

use App\DB;
use App\Entitys\Client;

class ClientDAO
{
    private $pdo;

    public function __construct() {
        $this->pdo = DB::pdo();
    }

    /**
     * Converte uma linha da query  em um objeto Client.
     *
     * @param array $row Linha da query.
     * @return Client Objeto cliente. 
     */
    private function createClient($row) {
        return new Client(
            intval($row['id']),
            $row['name'],
            $row['email'],
            $row['phone']
        );
    }

    /**
     * Armazena o objeto no banco de dados.
     *
     * @param Client $client Objeto a ser armazenado. 
     */
    public function store(Client $client)
    {
        $stmt = $this->pdo->prepare("INSERT INTO clients ( name, email, phone ) VALUES ( :name, :email, :phone )");
        $stmt->execute([
            'name' => $client->getName(),
            'email' => $client->getEmail(),
            'phone' => $client->getPhone()
        ]);
        $client->setId($this->pdo->lastInsertId());
    }

    /**
     * Atualiza o objeto no banco de dados.
     *
     * @param Client $client Objeto a ser atualizado. 
     */
    public function update(Client $client)
    {
        $stmt = $this->pdo->prepare("UPDATE clients SET name = :name, email = :email, phone = :phone WHERE id = :id");
        $stmt->execute([
            'name' => $client->getName(),
            'email' => $client->getEmail(),
            'phone' => $client->getPhone(),
            'id' => $client->getId()
        ]);
    }

    /**
     * Remove o objeto do banco de dados.
     *
     * @param int $id Identificador único do cliente. 
     */
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM clients WHERE id = :id");
        $stmt->execute([
            'id' => $id
        ]);
    }

    /**
     * Busca um objeto no banco de dados.
     *
     * @param int $id Identificador único do cliente. 
     */
    public function find($id)
    {
        $stmt = $this->pdo->prepare("SELECT id, name, email, phone FROM clients WHERE  id = :id");
        $stmt->execute([
            'id' => $id
        ]);
        if($stmt->rowCount() == 0) {
            throw(new \Exception('Cliente inexistente'));
        }
        $row = $stmt->fetch();
        return $this->createClient($row);
    }

    /**
     * Busca todos os clientes do banco de dados.
     * 
     */
    public function all()
    {
        $stmt = $this->pdo->prepare("SELECT id, name, email, phone FROM clients");
        $stmt->execute();
        $clients = [];
        while ($row = $stmt->fetch()) {
            $clients[] = $this->createClient($row);
        }
        return $clients;
    }

    /**
     * Busca uma página de clientes.
     *
     * @param int $page Página desejada.
     * @param int $perPage  Quantidade de registros por página. 
     */
    public function paginate($page, $perPage = 10)
    {
        if(!is_numeric($page)) {
            throw(new \Exception('A página deve ser um número'));
        }
        $stmt = $this->pdo->prepare("SELECT id, name, email, phone FROM clients ORDER BY id DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $perPage, $this->pdo::PARAM_INT);
        $stmt->bindValue(':offset', ($page - 1) * $perPage, $this->pdo::PARAM_INT);
        $stmt->execute();
        $clients = [];
        while ($row = $stmt->fetch()) {
            $clients[] = $this->createClient($row);
        }
        return $clients;
    }

    /**
     * Busca o total de páginas para determinado tamanho de página.
     *
     * @param int $perPage  Quantidade de registros por página. 
     */
    public function pageCount($perPage = 10)
    {
        $stmt = $this->pdo->prepare("SELECT count(*) as quantity FROM clients");
        $stmt->execute();
        $quantity = $stmt->fetch()['quantity'];
        return ceil($quantity/$perPage);
    }
}
?>