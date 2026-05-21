DROP DATABASE IF EXISTS wisedb;
DROP USER IF EXISTS 'wiseUser'@'localhost';
CREATE DATABASE wisedb;
CREATE USER 'wiseUser'@'localhost' IDENTIFIED BY 'W1se@2026#SecurePwd';
GRANT ALL PRIVILEGES ON wisedb.* TO 'wiseUser'@'localhost';
FLUSH PRIVILEGES;

USE wisedb;

CREATE TABLE Users_Groups (
    GroupId TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    GroupName_En VARCHAR(50) NOT NULL,
    GroupName_Ar VARCHAR(50) NOT NULL
) ENGINE=InnoDB;
CREATE TABLE Users (
    UserId INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    PhoneNumber VARCHAR(20) DEFAULT NULL UNIQUE,
    SubscriptionDate DATE NOT NULL,
    LastLogin TIMESTAMP NULL DEFAULT NULL,
    GroupId TINYINT UNSIGNED NOT NULL,
    Status TINYINT NOT NULL DEFAULT 1,
    FailedLogin TINYINT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (GroupId) REFERENCES Users_Groups(GroupId) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;
CREATE TABLE Users_Profiles (
    UserId INT UNSIGNED PRIMARY KEY,
    FirstName_En VARCHAR(50) NOT NULL,
    LastName_En VARCHAR(50) NOT NULL,
    Address_En VARCHAR(255),
    FirstName_Ar VARCHAR(50) NOT NULL,
    LastName_Ar VARCHAR(50) NOT NULL,
    Address_Ar VARCHAR(255),
    DOB DATE DEFAULT NULL,
    Image VARCHAR(255) DEFAULT 'avatar.jpg',
    FOREIGN KEY (UserId) REFERENCES Users(UserId) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;
INSERT INTO Users_Groups (GroupName_En, GroupName_Ar)
    VALUES ('Administrator', 'مسؤول النظام'),
           ('Login Trap', 'فخ تسجيل الدخول'),
           ('SQL Injection Trap', 'مصيدة حقن قواعد البيانات');
INSERT INTO Users (Username, Password, Email, PhoneNumber, SubscriptionDate, GroupId, Status)
    VALUES ('alaa','d4d5fcae6b91e068fe585e920bf3f49dba147955','alaa@wisetrap.org','0795888291','2026-05-15',1,1),
           ('yasmeen','d4d5fcae6b91e068fe585e920bf3f49dba147955','yasmeen@wisetrap.org','0792058083','2026-05-21',1,1),
           ('aya','d4d5fcae6b91e068fe585e920bf3f49dba147955','aya@wisetrap.org','0798625675','2026-05-21',1,1),
           ('hadeel','d4d5fcae6b91e068fe585e920bf3f49dba147955','hadeel@wisetrap.org','0788415530','2026-05-21',1,1),
           ('admin','18ebde30d6f04e1fe911a0e326cb56864ea3447e','login@wisetrap.org','0785685087','2026-05-15',2,1),
           ('root','18ebde30d6f04e1fe911a0e326cb56864ea3447e','sql@wisetrap.org','0788665577','2026-05-15',3,1);
INSERT INTO Users_Profiles (UserId,FirstName_En, LastName_En, Address_En,FirstName_Ar, LastName_Ar, Address_Ar,DOB, Image)
    VALUES (1,'Alaa','Alshalan','Hashemite Kingdom','الاء','الشعلان','المملكة الاردنية الهاشمية','2003-08-18','avatar.jpg'),
           (2,'Yasmeen','AbuReesha','Hashemite Kingdom','ياسمين','ابوريشه','المملكة الاردنية الهاشمية','2004-08-05','avatar.jpg'),
           (3,'Aya','AlSaifi','Hashemite Kingdom','ايه','الصيفي','المملكة الاردنية الهاشمية','2004-01-13','avatar.jpg'),
           (4,'Hadeel','Hushki','Hashemite Kingdom','هديل','حشكي','المملكة الاردنية الهاشمية','2004-08-26','avatar.jpg'),
           (5,'Wise','Trap','From world','الفخ','الذكي','من العالم','1992-12-17','trap.jpg'),
           (6,'Wise','SQL','From world','الفخ','الذكي','من العالم','1992-12-17','trap.jpg');

CREATE TABLE Attackers (
    attacker_id BIGINT PRIMARY KEY AUTO_INCREMENT,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT,
    first_seen TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_seen TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    country VARCHAR(100),
    is_bot BOOLEAN DEFAULT FALSE
);
CREATE TABLE TrapEndpoints (
    endpoint_id BIGINT PRIMARY KEY AUTO_INCREMENT,
    endpoint_name VARCHAR(255) NOT NULL,
    endpoint_url TEXT NOT NULL,
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE AttackLogs (
    log_id BIGINT PRIMARY KEY AUTO_INCREMENT,
    attacker_id BIGINT NOT NULL,
    endpoint_id BIGINT NULL,
    requested_url TEXT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    http_method VARCHAR(20),
    status_code INT,
    request_data LONGTEXT,
    response_data LONGTEXT,
    CONSTRAINT fk_attacklogs_attacker FOREIGN KEY (attacker_id) REFERENCES Attackers(attacker_id) ON DELETE CASCADE,
    CONSTRAINT fk_attacklogs_endpoint FOREIGN KEY (endpoint_id) REFERENCES TrapEndpoints(endpoint_id) ON DELETE CASCADE
);
CREATE TABLE Alerts (
    alert_id BIGINT PRIMARY KEY AUTO_INCREMENT,
    log_id BIGINT NOT NULL,
    alert_type VARCHAR(100),
    email_sent BOOLEAN DEFAULT FALSE,
    sent_at TIMESTAMP NULL,
    read_at TIMESTAMP NULL,
    status ENUM('pending', 'sent', 'failed', 'read') DEFAULT 'pending',
    CONSTRAINT fk_alerts_log FOREIGN KEY (log_id) REFERENCES AttackLogs(log_id) ON DELETE CASCADE
);
INSERT INTO TrapEndpoints (endpoint_name, endpoint_url, description, is_active)
 VALUES
     ('Login Trap', '/login.txt', 'Fake login file used to capture unauthorized access attempts', TRUE),
     ('SQL Injection', '/id=1', 'SQL Injection Honeypot', TRUE);

ALTER TABLE Users_Groups ADD trap_endpoint_id BIGINT NULL, ADD CONSTRAINT fk_group_trap FOREIGN KEY (trap_endpoint_id) REFERENCES TrapEndpoints(endpoint_id) ON DELETE SET NULL ON UPDATE CASCADE;
UPDATE Users_Groups SET trap_endpoint_id = 1 WHERE GroupId = 2;
UPDATE Users_Groups SET trap_endpoint_id = 2 WHERE GroupId = 3;