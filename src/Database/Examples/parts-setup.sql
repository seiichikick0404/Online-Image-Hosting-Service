CREATE TABLE IF NOT EXISTS parts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    description TEXT,
    price FLOAT,
    quantityInStock INT
);