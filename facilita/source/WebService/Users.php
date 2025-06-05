<?php

namespace Source\WebService;

use Source\Core\JWTToken;
use Source\Models\User;

class Users extends Api
{
    public function listUsers (): void
    {
        $users = new User();
        //var_dump($users->findAll());
        $this->call(200, "success", "Lista de usuários", "success")
            ->back($users->findAll());
    }
    
    public function createUser(array $data)
    {
       
        // verifica se os dados estão preenchidos
        if(in_array("", $data)){
            $this->call(400, "bad_request", "Dados inválidos", "error")->back();
            return;
        }

        $user = new User(
            null,
            $data["name"] ?? null,
            $data["email"] ?? null,
            $data["password"] ?? null,
            $data["phone"] ?? null,
            $data["role"] ?? null,
            false,
            $data["cafe_id"] ?? null


        );

        if(!$user->insert()){
            $this->call(500, "internal_server_error", $user->getErrorMessage(), "error")->back();
            return;
        }
        // montar $response com as informações necessárias para mostrar no front
        $response = [
            "name" => $user->getName(),
            "email" => $user->getEmail(),
            "role" => $user->getRole(),
            "phone" => $user->getPhone()
        ];

        $this->call(201, "created", "Usuário criado com sucesso", "success")
            ->back($response);

    }

    public function listUserById (array $data): void
    {

        if(!isset($data["id"])) {
            $this->call(400, "bad_request", "ID inválido", "error")->back();
            return;
        }

        if(!filter_var($data["id"], FILTER_VALIDATE_INT)) {
            $this->call(400, "bad_request", "ID inválido", "error")->back();
            return;
        }

        $user = new User();
        if(!$user->findById($data["id"])){
            $this->call(200, "success", "Usuário não encontrado", "error")->back();
            return;
        }
        $response = [
            "name" => $user->getName(),
            "email" => $user->getEmail(),
            "role" => $user->getRole(),
            "phone" => $user->getPhone()
        ];
        $this->call(200, "success", "Encontrado com sucesso", "success")->back($response);
    }

        public function listUserByEmail(array $data): void
    {
        if (empty($data['email'])) {
            $this->call(400, "bad_request", "Email não informado", "error")->back();
            return;
        }

        $user = new User();
        $result = $user->listUserByEmail($data['email']);

        if (!$result) {
            $this->call(404, "not_found", "Usuário não encontrado", "error")->back();
            return;
        }

        $this->call(200, "success", "Usuário encontrado", "success")->back($result);
    }


    public function deleteUser(array $data): void
    {
        if (empty($data['id']) || !filter_var($data['id'], FILTER_VALIDATE_INT)) {
            $this->call(400, "bad_request", "ID inválido", "error")->back();
            return;
        }

        $user = new User();
        if (!$user->deleteUser((int)$data['id'])) {
            $this->call(500, "internal_server_error", $user->getErrorMessage() ?? "Erro ao excluir usuário", "error")->back();
            return;
        }

        $this->call(200, "success", "Usuário excluído com sucesso", "success")->back();
    }

    public function updateUser(array $data): void
    {
        if (empty($data['id']) || !filter_var($data['id'], FILTER_VALIDATE_INT)) {
            $this->call(400, "bad_request", "ID inválido", "error")->back();
            return;
        }

        if (empty($data['name']) && empty($data['email']) && empty($data['password']) && empty($data['phone']) && empty($data['role'])) {
            $this->call(400, "bad_request", "Nenhum dado para atualizar", "error")->back();
            return;
        }

        $user = new User(
            (int)$data['id'],
            $data['name'] ?? null,
            $data['email'] ?? null,
            $data['password'] ?? null,
            $data['phone'] ?? null,
            $data['role'] ?? null
        );

        if (!$user->updateUser()) {
            $this->call(500, "internal_server_error", $user->getErrorMessage() ?? "Erro ao atualizar usuário", "error")->back();
            return;
        }

        $this->call(200, "success", "Usuário atualizado com sucesso", "success")->back();
    }

        public function login(array $data): void
    {
        // Verificar se os dados de login foram fornecidos
        if (empty($data["email"]) || empty($data["password"])) {
            $this->call(400, "bad_request", "Credenciais inválidas", "error")->back();
            return;
        }

        $user = new User();

        if(!$user->findByEmail($data["email"])){
            $this->call(401, "unauthorized", "Usuário não encontrado", "error")->back();
            return;
        }

        if(!password_verify($data["password"], $user->getPassword())){
            $this->call(401, "unauthorized", "Senha inválida", "error")->back();
            return;
        }

        // Gerar o token JWT
        $jwt = new JWTToken();
        $token = $jwt->create([
            "email" => $user->getEmail(),
            "name" => $user->getName()
        ]);

        // Retornar o token JWT na resposta
        $this->call(200, "success", "Login realizado com sucesso", "success")
            ->back([
                "token" => $token,
                "user" => [
                    "id" => $user->getId(),
                    "name" => $user->getName(),
                    "email" => $user->getEmail()
                ]
            ]);

    }
    
    public function updatePassword(array $data): void {
    if (empty($data['id']) || !filter_var($data['id'], FILTER_VALIDATE_INT)) {
        $this->call(400, "bad_request", "ID inválido", "error")->back();
        return;
    }

    if (empty($data['password'])) {
        $this->call(400, "bad_request", "Senha não informada", "error")->back();
        return;
    }

    $user = new User((int)$data['id']);

    if (!$user->findById($data['id'])) {
        $this->call(404, "not_found", "Usuário não encontrado", "error")->back();
        return;
    }

    if (!$user->updatePassword($data['password'])) {
        $this->call(500, "internal_server_error", $user->getErrorMessage() ?? "Erro ao atualizar senha", "error")->back();
        return;
    }

    $this->call(200, "success", "Senha atualizada com sucesso", "success")->back();
}

}