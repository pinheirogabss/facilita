<?php

namespace Source\WebService;

use Source\Models\Cafe;

class Cafes extends Api
{
    public function listCafes (): void
    {
        $cafes = new Cafe();
        $this->call(200, "success", "Lista de cafeterias", "success")
            ->back($cafes->findAll());
    }

    public function createCafe(array $data)
    {

        // verifica se os dados estão preenchidos
        if(in_array("", $data)){
            $this->call(400, "bad_request", "Dados inválidos", "error")->back();
            return;
        }

        $cafe = new Cafe(
            null,
            $data["name"] ?? null,
            $data["cnpj"] ?? null,
            $data["address"] ?? null
        );

        if(!$cafe->insert()){
            $this->call(500, "internal_server_error", $cafe->getErrorMessage(), "error")->back();
            return;
        }
        // montar $response com as informações necessárias para mostrar no front
        $response = [
            "name" => $cafe->getName(),
            "cnpj" => $cafe->getCnpj(),
            "address" => $cafe->getAddress()
        ];

        $this->call(201, "created", "Cafeteria criada com sucesso", "success")
            ->back($response);

    }

    public function listCafeById (array $data): void
    {

        if(!isset($data["id"])) {
            $this->call(400, "bad_request", "ID inválido", "error")->back();
            return;
        }

        if(!filter_var($data["id"], FILTER_VALIDATE_INT)) {
            $this->call(400, "bad_request", "ID inválido", "error")->back();
            return;
        }

        $cafe = new Cafe();
        if(!$cafe->findById($data["id"])){
            $this->call(200, "success", "Cafeteria não encontrada", "error")->back();
            return;
        }
        $response = [
            "name" => $cafe->getName(),
            "address" => $cafe->getAddress()
        ];
        $this->call(200, "success", "Encontrado com sucesso", "success")->back($response);
    }

    public function updateCafe(array $data): void
{
    if (!isset($data["id"])) {
        $this->call(400, "bad_request", "ID inválido", "error")->back();
        return;
    }

    if (in_array("", $data)) {
        $this->call(400, "bad_request", "Dados inválidos", "error")->back();
        return;
    }

    // Cria o objeto com os dados
    $cafe = new Cafe(
        null,
        $data["name"] ?? null,
        $data["cnpj"] ?? null,
        $data["address"] ?? null
    );

    // Seta o ID a ser atualizado
    $cafe->setId((int)$data["id"]);

    // Chama o método correto
    if (!$cafe->updateCafe()) {
        $this->call(500, "internal_server_error", $cafe->getErrorMessage(), "error")->back();
        return;
    }

    $response = [
        "id" => $cafe->getId(),
        "name" => $cafe->getName(),
        "cnpj" => $cafe->getCnpj(),
        "address" => $cafe->getAddress()
    ];

    $this->call(200, "success", "Cafeteria atualizada com sucesso", "success")
        ->back($response);
}

   public function deleteCafe(array $data): void
{
    if (!isset($data["id"]) || !filter_var($data["id"], FILTER_VALIDATE_INT)) {
        $this->call(400, "bad_request", "ID inválido", "error")->back();
        return;
    }

    $cafe = new Cafe();
    if (!$cafe->deleteCafe((int) $data["id"])) {
        $this->call(500, "internal_server_error", "Erro ao excluir cafeteria", "error")->back();
        return;
    }

    $this->call(200, "success", "Cafeteria excluída com sucesso", "success")->back();
}


}