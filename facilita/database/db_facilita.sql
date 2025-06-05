DROP DATABASE IF EXISTS db_facilita; 
CREATE DATABASE db_facilita;
USE db_facilita;

-- Tabela: Cafeterias
CREATE TABLE cafes (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100),
    cnpj VARCHAR(20) UNIQUE,
    address TEXT
);

-- Tabela: Usuários
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password TEXT,
    phone VARCHAR(20),
    role ENUM('admin', 'owner', 'employee'),
    cafe_id INTEGER,
    FOREIGN KEY (cafe_id) REFERENCES cafes(id)
);

-- Tabela: Categorias de Produtos
CREATE TABLE categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL
);

-- Inserindo as 3 categorias fixas
INSERT INTO categories (name) VALUES 
('doce'), 
('salgado'), 
('bebida');

-- Tabela: Produtos (Cardápio / Estoque) com categoria
CREATE TABLE products (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100),
    unit VARCHAR(20),
    stock_quantity INT,
    stock_min INT,
    cafe_id INTEGER,
    category_id INTEGER,
    FOREIGN KEY (cafe_id) REFERENCES cafes(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Tabela: Movimentações de Estoque
CREATE TABLE stock_movements (
    id SERIAL PRIMARY KEY,
    product_id INTEGER,
    type ENUM('in', 'out'),
    quantity INT,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reason TEXT,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Tabela: Pedidos
CREATE TABLE orders (
    id SERIAL PRIMARY KEY,
    customer_name VARCHAR(100),
    customer_phone VARCHAR(20),
    status ENUM('concluido', 'pendente'),
    description TEXT,
    cafe_id INTEGER,
    FOREIGN KEY (cafe_id) REFERENCES cafes(id)
);

-- Tabela: Itens do Pedido
CREATE TABLE order_items (
    id SERIAL PRIMARY KEY,
    order_id INTEGER,
    product_id INTEGER,
    size VARCHAR(50),
    quantity INTEGER,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Tabela: Tarefas
CREATE TABLE tasks (
    id SERIAL PRIMARY KEY,
    description TEXT,
    status ENUM('concluida', 'pendente'),
    user_id INTEGER,
    cafe_id INTEGER,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (cafe_id) REFERENCES cafes(id)
);
