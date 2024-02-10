<?php
namespace App\Entitys;

use App\Models\ClientDAO;

class Client implements \JsonSerializable
{
    private $id;
    private $name;
    private $email;
    private $phone;

    public function getId() { return  $this->id; }
    public function setId($id) { $this->id = $id; }
    public function getName() { return  $this->name; }
    public function setName($name) { $this->name = $name; }
    public function getEmail() { return  $this->email; }
    public function setEmail($email) { $this->email = $email; }
    public function getPhone() { return  $this->phone; }
    public function setPhone($phone) { $this->phone = $phone; }

    public function __construct($id =null, $name=null, $email=null, $phone=null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
    }

    public function jsonSerialize():mixed
    {
        $vars = get_object_vars($this);

        return $vars;
    }

    /**
     * Salva o cliente no banco de dados.
     */
    public function save()
    {
        (new ClientDAO())->store($this);
    }

    /**
     * Atualiza o cliente no banco de dados.
     */
    public function update()
    {
        (new ClientDAO())->update($this);
    }

    /**
     * Remove o cliente do banco de dados.
     */
    public function delete()
    {
        (new ClientDAO())->delete($this->id);
    }
}
?>