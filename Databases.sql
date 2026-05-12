CREATE DATABASE wisedb;
CREATE USER "wiseUser"@"localhost" IDENTIFIED BY "xlXXDrcfxxsZDirG";
GRANT ALL PRIVILEGES ON wisedb.* TO "wiseUser"@"localhost";
ALTER USER 'wiseUser'@'localhost' IDENTIFIED WITH mysql_native_password BY 'xlXXDrcfxxsZDirG';

USE wisedb;

CREATE TABLE Users_Groups (
    GroupId TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    GroupName_En VARCHAR(50) NOT NULL,
    GroupName_Ar VARCHAR(50) NOT NULL
) ENGINE=InnoDB;
CREATE TABLE Users_Privileges (
    PrivilegeId TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    PrivilegeName_En VARCHAR(100) NOT NULL,
    PrivilegeName_Ar VARCHAR(100) NOT NULL,
    Privilege_URL VARCHAR(100) NOT NULL
) ENGINE=InnoDB;
CREATE TABLE Users_Groups_Privileges (
    GroupId TINYINT UNSIGNED NOT NULL,
    PrivilegeId TINYINT UNSIGNED NOT NULL,
    PRIMARY KEY (GroupId, PrivilegeId),
    FOREIGN KEY (GroupId) REFERENCES Users_Groups(GroupId) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (PrivilegeId) REFERENCES Users_Privileges(PrivilegeId) ON DELETE CASCADE ON UPDATE CASCADE
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
    VALUES ('Admin', 'مدير');
INSERT INTO Users_Privileges (PrivilegeName_En, PrivilegeName_Ar, Privilege_URL)
    VALUES
        ('Add user', 'انشاء مستخدم', '/users/add'),
        ('Edit user', 'تعديل مستخدم', '/users/edit'),
        ('Delete user', 'حذف مستخدم', '/users/delete'),
        ('View users', 'مشاهدة المستخدمين', '/users');
INSERT INTO Users (Username, Password, Email, PhoneNumber, SubscriptionDate, GroupId, Status)
    VALUES ('admin','18ebde30d6f04e1fe911a0e326cb56864ea3447e','admin@example.com','0785685087','2026-05-13',1,1);
INSERT INTO Users_Profiles (UserId,FirstName_En, LastName_En, Address_En,FirstName_Ar, LastName_Ar, Address_Ar,DOB, Image)
    VALUES (1,'Alaa','Alshalan','Jordan','الاء','الشعلان','الأردن','1992-12-17','avatar.jpg');