<?php

namespace Source\Models;

use Source\Core\Connect;
use Source\Core\Model;
use PDO;

class Category extends Model {
    protected $id;
    protected $name;
    protected $deleted;

public function __construct(
        int $id = null,
        string $name = null,
        bool $deleted = false
    )
    {
        $this->table = "categories";
        $this->id = $id;
        $this->name = $name;
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

      public function findAll(): array
    {
        $sql = "SELECT * FROM categories WHERE deleted = 0";
        $stmt = Connect::getInstance()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteCategory(int $id): bool
    {
        $sql = "UPDATE categories SET deleted = true WHERE id = :id";
        $stmt = Connect::getInstance()->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            $this->errorMessage = "Erro ao deletar categoria: {$e->getMessage()}";
            return false;
        }
    }

    public function updateCategory(): bool {
    $sql = "UPDATE categories SET name = :name WHERE id = :id";

    $stmt = Connect::getInstance()->prepare($sql);
    $stmt->bindValue(":id", $this->id, \PDO::PARAM_INT);
    $stmt->bindValue(":name", $this->name);

    try {
        return $stmt->execute();
    } catch (\PDOException $e) {
        $this->errorMessage = "Erro ao atualizar categoria pelo ID: {$e->getMessage()}";
        return false;
    }
}

}