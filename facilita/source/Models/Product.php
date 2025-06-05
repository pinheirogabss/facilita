<?php

namespace Source\Models;

use Source\Core\Connect;
use Source\Core\Model;
use PDO;

class Product extends Model {
    protected $id;
    protected $name;
    protected $unit;
    protected $stock_quantity;
    protected $stock_min;
    protected $deleted;
    protected $cafe_id;
    protected $category_id;

     public function __construct(
        int $id = null,
        string $name = null,
        int $unit = null,
        int $stock_quantity = null,
        int $stock_min = null,
        bool $deleted = false,
        int $cafe_id = null,
        int $category_id = null
    )
    {
        $this->table = "products";
        $this->id = $id;
        $this->name = $name;
        $this->unit = $unit;
        $this->stock_quantity = $stock_quantity;
        $this->stock_min = $stock_min;   
        $this->deleted = $deleted;     
        $this->cafe_id = $cafe_id;
        $this->category_id = $category_id;
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

    
    public function getUnit(): ?int
    {
        return $this->unit;
    }

    public function setUnit(?int $unit): void
    {
        $this->unit = $unit;
    }

    public function getStockQuantity(): ?int
    {
        return $this->stock_quantity;
    }

    public function setStockQuantity(?int $stock_quantity): void
    {
        $this->stock_quantity = $stock_quantity;
    }

    public function getCafeId(): ?int
    {
        return $this->cafe_id;
    }

    public function setCafeId(?int $cafe_id): void
    {
        $this->cafe_id;
    }

    public function getCategory_id()
    {
        return $this->category_id;
    }

    public function setCategory_id($category_id)
    {
        $this->category_id = $category_id;
    }

public function getStockMin(): int
{
    return $this->stock_min;
}


    public function setStockMin($stock_min)
    {
        $this->stock_min = $stock_min;

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
        $sql = "SELECT * FROM products WHERE deleted = 0";
        $stmt = Connect::getInstance()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteProduct(int $id): bool
    {
        $sql = "UPDATE products SET deleted = true WHERE id = :id";
        $stmt = Connect::getInstance()->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            $this->errorMessage = "Erro ao deletar produto: {$e->getMessage()}";
            return false;
        }
    }

    public function updateProduct(): bool
{
    $sql = "UPDATE products SET name = :name, unit = :unit, stock_quantity = :stock_quantity, stock_min = :stock_min WHERE id = :id";

    $stmt = Connect::getInstance()->prepare($sql);
    $stmt->bindValue(":id", $this->id, \PDO::PARAM_INT);
    $stmt->bindValue(":name", $this->name);
    $stmt->bindValue(":unit", $this->unit);
    $stmt->bindValue(":stock_quantity", $this->stock_quantity);
    $stmt->bindValue(":stock_min", $this->stock_min);

    try {
        return $stmt->execute();
    } catch (\PDOException $e) {
        $this->errorMessage = "Erro ao atualizar produto pelo ID: {$e->getMessage()}";
        return false;
    }
}


}