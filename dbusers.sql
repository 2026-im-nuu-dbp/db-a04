// Create a new table called "dbusers"
CREATE TABLE dbusers (
    username VARCHAR(50) PRIMARY KEY,
    nickname VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    gender VARCHAR(10),
    interest VARCHAR(255)
);