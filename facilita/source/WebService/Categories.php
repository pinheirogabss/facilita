<?php

namespace Source\WebService;

use Source\Models\Category;

class Categories extends Api
{
    public function listCategories(): void
    {
        $categories = new Category();
        $this->call(200, "success", "Lista de categorias", "success")
            ->back($categories->findAll());
    }

    public function createCategory(array $data): void
    {
        if (in_array("", $data)) {
            $this->call(400, "bad_request", "Dados inválidos", "error")->back();
            return;
        }

        $category = new Category(
            null,
            $data["name"] ?? null
        );

        if (!$category->insert()) {
            $this->call(500, "internal_server_error", $category->getErrorMessage(), "error")->back();
            return;
        }

        $response = [
            "name" => $category->getName()
        ];

        $this->call(201, "created", "Categoria criada com sucesso", "success")
            ->back($response);
    }

    public function listCategoryById(array $data): void
    {
        if (!isset($data["id"]) || !filter_var($data["id"], FILTER_VALIDATE_INT)) {
            $this->call(400, "bad_request", "ID inválido", "error")->back();
            return;
        }

        $category = new Category();
        if (!$category->findById($data["id"])) {
            $this->call(200, "success", "Categoria não encontrada", "error")->back();
            return;
        }

        $response = [
            "name" => $category->getName()
        ];

        $this->call(200, "success", "Encontrado com sucesso", "success")->back($response);
    }

    public function updateCategory(array $data): void
    {
        if (!isset($data["id"])) {
            $this->call(400, "bad_request", "ID inválido", "error")->back();
            return;
        }

        if (in_array("", $data)) {
            $this->call(400, "bad_request", "Dados inválidos", "error")->back();
            return;
        }

        $category = new Category(
            (int)$data["id"],
            $data["name"] ?? null
        );

        if (!$category->updateCategory()) {
            $this->call(500, "internal_server_error", $category->getErrorMessage(), "error")->back();
            return;
        }

        $response = [
            "id" => $category->getId(),
            "name" => $category->getName()
        ];

        $this->call(200, "success", "Categoria atualizada com sucesso", "success")
            ->back($response);
    }

    public function deleteCategory(array $data): void
    {
        if (!isset($data["id"]) || !filter_var($data["id"], FILTER_VALIDATE_INT)) {
            $this->call(400, "bad_request", "ID inválido", "error")->back();
            return;
        }

        $category = new Category();
        if (!$category->deleteCategory((int)$data["id"])) {
            $this->call(500, "internal_server_error", "Erro ao excluir categoria", "error")->back();
            return;
        }

        $this->call(200, "success", "Categoria excluída com sucesso", "success")->back();
    }
}
