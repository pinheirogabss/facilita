<?php

ob_start();

require  __DIR__ . "/../vendor/autoload.php";

// os headers abaixo são necessários para permitir o acesso a API por clientes externos ao domínio
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header('Access-Control-Allow-Credentials: true'); // Permitir credenciais

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

use CoffeeCode\Router\Router;

$route = new Router("http://localhost:8080/facilita/api",":");

$route->namespace("Source\WebService");

/* USERS */

$route->group("/users");

$route->post("/login", "Users:login");

$route->get("/", "Users:listUsers");

$route->get("/id/{id}", "Users:listUserById");

//$route->get("/email", "Users:listUserByEmail");

$route->post("/add", "Users:createUser");

$route->put("/update", "Users:updateUser");
$route->put("/update/password", "Users:updatePassword");


$route->delete("/delete/id/{id}", "Users:deleteUser");

$route->group("null");



//CAFES

$route->group("/cafes");
$route->get("/", "Cafes:listCafes");

$route->get("/id/{id}", "Cafes:listCafeById");

$route->post("/add", "Cafes:createCafe");

$route->put("/update", "Cafes:updateCafe");
$route->delete("/delete/id/{id}", "Cafes:deleteCafe");

//Products

$route->group("/products");
$route->get("/", "Products:listProducts");

$route->get("/id/{id}", "Products:listProductById");

$route->post("/add", "Products:createProduct");

$route->put("/update", "Products:updateProduct");
$route->delete("/delete/id/{id}", "Products:deleteProduct");

//Categories

$route->group("/categories");
$route->get("/", "Categories:listCategories");

$route->get("/id/{id}", "Categories:listCategoryById");

$route->post("/add", "Categories:createCategory");

$route->put("/update", "Categories:updateCategory");
$route->delete("/delete/id/{id}", "Categories:deleteCategory");


$route->dispatch();

/** ERROR REDIRECT */
if ($route->error()) {
    header('Content-Type: application/json; charset=UTF-8');
    http_response_code(404);

    echo json_encode([
        "code" => 404,
        "status" => "not_found",
        "message" => "URL não encontrada"
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

}

ob_end_flush();