<?php

namespace Source\Models;

use Source\Core\Connect;
use Source\Core\Model;
use PDO;
use PDOException;

class Cafe extends Model {
    protected $id;
    protected $name;
    protected $cnpj;
    protected $address;
    protected $deleted;

    public function __construct (
          
        int $id = null,
        string $name = null,
        string $cnpj = null,
        string $address = null,
        bool $deleted = false
    )
    {
        $this->table = "cafes";
        $this->id = $id;
        $this->name = $name;
        $this->cnpj = $cnpj;
        $this->address = $address;
        $this->deleted = $deleted;
    }

     public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getCnpj(): ?string
    {
        return $this->cnpj;
    }

    public function setCnpj(?string $cnpj): void
    {
        $this->cnpj = $cnpj;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    public function getDeleted()
    {
        return $this->deleted;
    }

    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM cafes WHERE deleted = 0";
        $stmt = Connect::getInstance()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteCafe(int $id): bool
    {
        $sql = "UPDATE cafes SET deleted = true WHERE id = :id";
        $stmt = Connect::getInstance()->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            $this->errorMessage = "Erro ao deletar cafe: {$e->getMessage()}";
            return false;
        }
    }

    public function updateCafe(): bool
{
    $sql = "UPDATE cafes SET name = :name, cnpj = :cnpj, address = :address WHERE id = :id";

    $stmt = Connect::getInstance()->prepare($sql);
    $stmt->bindValue(":id", $this->id, \PDO::PARAM_INT);
    $stmt->bindValue(":name", $this->name);
    $stmt->bindValue(":cnpj", $this->cnpj);
    $stmt->bindValue(":address", $this->address);

    try {
        return $stmt->execute();
    } catch (\PDOException $e) {
        $this->errorMessage = "Erro ao atualizar cafeteria pelo ID: {$e->getMessage()}";
        return false;
    }
}

}