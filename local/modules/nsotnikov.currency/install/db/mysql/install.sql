CREATE TABLE IF NOT EXISTS currency_rates (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    CODE VARCHAR(3) NOT NULL,
    DATE DATETIME NOT NULL,
    COURSE FLOAT NOT NULL
);