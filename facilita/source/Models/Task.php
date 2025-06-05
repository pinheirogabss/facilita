<?php

namespace Source\Models;

use Source\Core\Connect;
use Source\Core\Model;

class Task extends Model {
    protected $id;
    protected $description;
    protected $status;
    protected $user_id;
    protected $cafe_id;

   
     public function __construct(
        int $id = null,
        string $description = null,
        string $status = null,
        int $user_id = null,
        int $cafe_id = null
    )
    {
        $this->table = "tasks";
        $this->id = $id;
        $this->description = $description;
        $this->status = $status;
        $this->user_id = $user_id;
        $this->cafe_id = $cafe_id;

    }

    public function getCafe_id()
    {
        return $this->cafe_id;
    }

    public function setCafe_id($cafe_id)
    {
        $this->cafe_id = $cafe_id;

    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;

    }

    public function getUser_id()
    {
        return $this->user_id;
    }

    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;

    }
 
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

    }
}