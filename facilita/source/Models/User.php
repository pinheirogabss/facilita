<?php

namespace Source\Models;

use Source\Core\Connect;
use Source\Core\Model;
use PDO;

class User extends Model
{
    protected $id;
    protected $name;
    protected $email;
    protected $password;
    protected $phone;
    protected $role;
    protected $deleted;
    protected $cafe_id;

    public function __construct(
        int $id = null,
        string $name = null,
        string $email = null,
        string $password = null,
        string $phone = null,
        string $role = null,
        bool $deleted = false,
        int $cafe_id = null
    )
    {
        $this->table = "users";
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->phone = $phone;
        $this->role = $role;
        $this->deleted = $deleted;
        $this->cafe_id = $cafe_id;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): void
    {
        $this->role = $role;
    }

        public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;

    }

        public function getDeleted()
    {
        return $this->deleted;
    }

    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

    }

     public function login () {
        echo "Olá, {$this->name}! Você está logado!";
    }

    public function insert (): bool
    {

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->errorMessage = "E-mail inválido";
            return false;
        }

        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = Connect::getInstance()->prepare($sql);
        $stmt->bindValue(":email", $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $this->errorMessage = "E-mail já cadastrado";
            return false;
        }

        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        if(!parent::insert()){
            $this->errorMessage = "Erro ao inserir o registro: {$this->getErrorMessage()}";
            return false;
        }

        return true;
    }

    public function findByEmail (string $email): bool
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = Connect::getInstance()->prepare($sql);
        $stmt->bindValue(":email", $email);

        try {
            $stmt->execute();
            $result = $stmt->fetch();
            if (!$result) {
                return false;
            }
            $this->id = $result->id;
            $this->name = $result->name;
            $this->email = $result->email;
            $this->password = $result->password;

            return true;
        } catch (PDOException $e) {
            $this->errorMessage = "Erro ao buscar o registro: {$e->getMessage()}";
            return false;
        }

    }


    public function findAll(): array
    {
        $sql = "SELECT * FROM users WHERE deleted = 0";
        $stmt = Connect::getInstance()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function deleteUser(int $id): bool
    {
        $sql = "UPDATE users SET deleted = true WHERE id = :id";
        $stmt = Connect::getInstance()->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            $this->errorMessage = "Erro ao deletar usuário: {$e->getMessage()}";
            return false;
        }
    }

    public function updateUser(): bool
    {
        $sql = "UPDATE users SET name = :name, email = :email, password = :password, phone = :phone, role = :role WHERE id = :id";

        $stmt = Connect::getInstance()->prepare($sql);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
        $stmt->bindValue(":name", $this->name);
        $stmt->bindValue(":email", $this->email);
        $stmt->bindValue(":password", $this->password ? password_hash($this->password, PASSWORD_DEFAULT) : $this->password);
        $stmt->bindValue(":phone", $this->phone);
        $stmt->bindValue(":role", $this->role);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            $this->errorMessage = "Erro ao atualizar usuário pelo ID: {$e->getMessage()}";
            return false;
        }
    }

    public function findById(int $id): bool
{
    $sql = "SELECT * FROM users WHERE id = :id AND deleted = 0 LIMIT 1";
    $stmt = Connect::getInstance()->prepare($sql);
    $stmt->bindValue(":id", $id, \PDO::PARAM_INT);
    $stmt->execute();

    $data = $stmt->fetch(\PDO::FETCH_ASSOC);

    if ($data) {
        $this->id = $data["id"];
        $this->name = $data["name"];
        $this->email = $data["email"];
        $this->password = $data["password"];
        $this->phone = $data["phone"];
        $this->role = $data["role"];
        $this->deleted = (bool)$data["deleted"];
        return true;
    }

    return false;
}

public function updatePassword(string $newPassword): bool
{
    if (empty($newPassword)) {
        $this->errorMessage = "Senha não pode ser vazia";
        return false;
    }

    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $sql = "UPDATE users SET password = :password WHERE id = :id";

    $stmt = Connect::getInstance()->prepare($sql);
    $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
    $stmt->bindValue(":password", $hashedPassword);

    try {
        return $stmt->execute();
    } catch (PDOException $e) {
        $this->errorMessage = "Erro ao atualizar senha: {$e->getMessage()}";
        return false;
    }
}



}