USE scandiwebAssignment;
CREATE TABLE Products(
                      id INT(4) AUTO_INCREMENT PRIMARY KEY,
					  sku VARCHAR(255) NOT NULL UNIQUE,
                      name VARCHAR(255) NOT NULL,
					  price DECIMAL(10,0) NOT NULL,
					  type VARCHAR(255) NOT NULL,
                      value VARCHAR(255) NOT NULL
);