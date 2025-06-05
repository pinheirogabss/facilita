<?php

namespace Source\Models;

use Source\Core\Connect;
use Source\Core\Model;

class Order extends Model {
    protected $id;
    protected $costumer_name;
    protected $costumer_phone;
    protected $status;
    protected $description;
    protected $cafe_id;

     public function __construct(
        int $id = null,
        string $costumer_name = null,
        string $costumer_phone = null,
        string $status = null,
        string $description = null,
        int $cafe_id = null
    )
    {
        $this->table = "orders";
        $this->id = $id;
        $this->costumer_name = $costumer_name;
        $this->costumer_phone = $costumer_phone;
        $this->status = $status;
        $this->description = $description;
        $this->cafe_id = $cafe_id;

    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getCostumer_name()
    {
        return $this->costumer_name;
    }

    public function setCostumer_name($costumer_name)
    {
        $this->costumer_name = $costumer_name;

    }

    public function getCostumer_phone()
    {
        return $this->costumer_phone;
    }

    public function setCostumer_phone($costumer_phone)
    {
        $this->costumer_phone = $costumer_phone;

    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;

    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

    }

    public function getCafe_id()
    {
        return $this->cafe_id;
    }

    public function setCafe_id($cafe_id)
    {
        $this->cafe_id = $cafe_id;

    }
}
