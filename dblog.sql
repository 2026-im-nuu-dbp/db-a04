-- 建立登入紀錄表 dblog
CREATE TABLE dblog (
	id INT AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(50) NOT NULL,
	login_time DATETIME NOT NULL,
	success TINYINT(1) NOT NULL,
);