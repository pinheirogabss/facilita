<?php

namespace Source\WebService;

use Source\Models\Product;

class Products extends Api
{
    public function listProducts (): void
    {
        $products = new Product();
        $this->call(200, "success", "Lista de produtos", "success")
            ->back($products->findAll());
    }

    public function createProduct(array $data)
    {

        // verifica se os dados estão preenchidos
        if(in_array("", $data)){
            $this->call(400, "bad_request", "Dados inválidos", "error")->back();
            return;
        }

        $product = new Product(
            null,
            $data["name"] ?? null,
            $data["unit"] ?? null,
            $data["stock_quantity"] ?? null,
            $data["stock_min"] ?? null
        );

        if(!$product->insert()){
            $this->call(500, "internal_server_error", $product->getErrorMessage(), "error")->back();
            return;
        }
        // montar $response com as informações necessárias para mostrar no front
        $response = [
            "name" => $product->getName(),
            "unit" => $product->getUnit(),
            "stock_min" => $product->getStockMin(),
            "stock_quantity" => $product->getStockQuantity()
        ];

        $this->call(201, "created", "Produto criado com sucesso", "success")
            ->back($response);

    }

    public function listProductByID (array $data): void
    {

        if(!isset($data["id"])) {
            $this->call(400, "bad_request", "ID inválido", "error")->back();
            return;
        }

        if(!filter_var($data["id"], FILTER_VALIDATE_INT)) {
            $this->call(400, "bad_request", "ID inválido", "error")->back();
            return;
        }

        $product = new Product();
        if(!$product->findById($data["id"])){
            $this->call(200, "success", "Produto não encontrado", "error")->back();
            return;
        }
        $response = [
            "name" => $product->getName(),
            "unit" => $product->getUnit()
        ];
        $this->call(200, "success", "Encontrado com sucesso", "success")->back($response);
    }

    public function updateProduct (array $data): void
    {
        if(!isset($data["id"])) {
            $this->call(400, "bad_request", "ID inválido", "error")->back();
            return;
        }
        
        if(in_array("", $data)){
            $this->call(400, "bad_request", "Dados inválidos", "error")->back();
            return;
        }

        $product = new Product(
            null,
            $data["name"] ?? null,
            $data["unit"] ?? null,
            $data["stock_quantity"] ?? null,
            $data["stock_min"] ?? null,
        
        );

        if(!$product->updateProduct()){
            $this->call(500, "internal_server_error", $product->getErrorMessage(), "error")->back();
            return;
        }
        // montar $response com as informações necessárias para mostrar no front
        $response = [
            "name" => $product->getName(),
            "unit" => $product->getUnit(),
            "stock_min" => $product->getStockMin(),
            "stock_quantity" => $product->getStockQuantity()
        ];


        //var_dump($data);
        $this->call(200, "success", "Produto atualizado com sucesso", "success")
            ->back($data);
    }

    public function deleteProduct(array $data): void
{
    if (!isset($data['id']) || !filter_var($data['id'], FILTER_VALIDATE_INT)) {
        $this->call(400, "bad_request", "ID inválido", "error")->back();
        return;
    }

    $product = new Product();

    if (!$product->deleteById($data['id'])) {
        $this->call(500, "internal_server_error", "Erro ao excluir produto", "error")->back();
        return;
    }

    $this->call(200, "success", "Produto excluído com sucesso", "success")->back();
}

}