# WISETrap Honeypot — Windows Deployment Guide

This guide provides step-by-step instructions for deploying the WISETrap multi-vector honeypot framework on a fresh Microsoft Windows environment using Apache, PHP, and MySQL.

WISETrap is a modular cybersecurity honeypot platform designed to emulate vulnerable web applications and exposed attack surfaces for the purpose of monitoring, logging, analyzing, and studying malicious activity in controlled environments.

The framework is intended for defensive cybersecurity operations, security research, detection engineering, threat intelligence, and educational use only.

> WISETrap must be deployed exclusively in authorized, isolated, and legally compliant environments. The developers and contributors are not responsible for any misuse, unauthorized deployment, or illegal activity conducted using this software.

---

## Prerequisites
- [Windows 10, Windows 11, or Windows Server](https://www.microsoft.com/software-download)
- Administrator privileges

---

## Step 1: Install Apache
 - Download Apache 2.4.x Win64 from: [https://www.apachelounge.com/download/](https://www.apachelounge.com/download/)
   - After downloading Apache, move it to the correct path, preferably `C:/Apache24`.

---

## Step 2: Install PHP
 - Download PHP 8.x **Thread Safe** from: [https://windows.php.net/download](https://windows.php.net/download)
   - After downloading PHP, move it to the correct path, preferably `C:/php`.

---

## Step 3: Install MySQL
 - Download MySQL Installer 8.x from: [https://dev.mysql.com/downloads/installer/](https://dev.mysql.com/downloads/installer/)
   - After downloading MySQL, Install MySQL Server & MySQL Workbench

---

## Step 4: Install OpenSSL
- Download OpenSSL v4.x **Light** from:
  [https://slproweb.com/products/Win32OpenSSL.html](https://slproweb.com/products/Win32OpenSSL.html)
    - After downloading OpenSSL, Destination location: `C:\Program Files\OpenSSL-Win64`.

---

## Step 5: Install Git
- Download Git  v2.x from:
  [https://git-scm.com/install/windows](https://git-scm.com/install/windows)
    - Default Settings.

---

## Step 6: Configure Environment Variables (PATH)
Add the following paths to Windows PATH:
- OpenSSL:
  ```
  C:\Program Files\OpenSSL-Win64\bin
  ```
- PHP:
  ```
  C:\php
  ```
- Apache:
  ```
  C:\Apache24\bin
  ```
- MySQL:
  ```
  C:\Program Files\MySQL\MySQL Server 8.0\bin
  ```
  > **Note:**
  > The MySQL version directory may differ depending on the installed release (8.0, 8.4, 9.x, etc.).

### Steps to add PATH:
1. Open Start Menu
2. Search: **Environment Variables**
3. Open: **Edit the system environment variables**
4. Click: **Environment Variables**
5. Under **System Variables**, select `Path`
6. Click **Edit**
7. Click **New** and add each path
8. Click **OK** and restart terminal

---

## Step 7: Verify Installation
Run in PowerShell or CMD:

```bash
httpd -v
php -v
mysql --version
openssl version
```

Expected result: all commands return version numbers.

---

## Step 8: Download WISETrap
Clone the official WISETrap repository:

```powershell
git clone https://github.com/WiseTrap/honeypot.git C:\Apache24\htdocs\honeypot
```

After cloning, the project should exist at:

```text
C:\Apache24\htdocs\honeypot
```

---

## Step 9: Configure Environment File
The project uses a local `.env` file for sensitive configuration such as:

- Database credentials
- Application URL
- Secret keys
- Internal honeypot configuration

The `.env` file is intentionally excluded from Git version control and must be created manually.

Create the environment file:

```powershell
copy C:\Apache24\htdocs\honeypot\.env.example C:\Apache24\htdocs\honeypot\.env
```

Open:

```powershell
notepad C:\Apache24\htdocs\honeypot\.env
```

Example configuration:

```env
DOMAIN=honeypot.wise.local

DB_HOST=localhost
DB_PORT=3306
DB_NAME=wisedb

DB_USER=wiseUser
DB_PASSWORD=W1se@2026#SecurePwd
```

Save the file after editing.

---

## Step 10: Import Database
Import the prepared SQL file included with the project.

Open PowerShell:

```powershell
Get-Content C:\Apache24\htdocs\honeypot\Databases.sql | mysql -u root -p
```

Enter your MySQL root password when prompted.

---

## Step 11: Enable Required PHP Extensions

Create the main PHP configuration file:

```powershell
copy C:\php\php.ini-production C:\php\php.ini
```

Open:

```powershell
notepad C:\php\php.ini
```

Find the following lines:

```ini
;extension=pdo_mysql
;extension=mysqli
```

Remove the semicolon (`;`) to enable and **Save the file**.

---

## Step 12: SSL Certificate Setup (Required)

This step is required before configuring HTTPS (443) for WISETrap.

### Note

If you have not created a self-signed SSL wildcard certificate yet, you MUST complete the following guide first:

[SSL_Windows.md](https://github.com/WiseTrap/honeypot/blob/main/Setup/Windows/SSL_Windows.md)

This guide covers:
- Root Certificate Authority (CA) creation
- Wildcard certificate generation (*.wise.local)
- Certificate signing process
- Trusted Root installation on Windows

You must complete SSL setup before continuing with Apache HTTPS virtual host configuration.

---

## Step 13: Configure Apache HTTP Virtual Host

Open:

```
notepad C:\Apache24\conf\extra\honeypot-http.conf
```

Add:

```apache
<VirtualHost *:80>
    ServerName honeypot.wise.local
    ServerAlias *.wise.local
    DocumentRoot "C:/Apache24/htdocs/honeypot/Public"
    
    RewriteEngine On
    RewriteCond %{HTTPS} !=on
    RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
    <Directory "C:/Apache24/htdocs/honeypot/Public">
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
        DirectoryIndex index.php
    </Directory>

    ErrorLog "logs/honeypot_http_error.log"
    CustomLog "logs/honeypot_http_access.log" common
</VirtualHost>
```

---

## Step 14: Configure Apache SSL Virtual Host

Open:

```
notepad C:\Apache24\conf\extra\honeypot-ssl.conf
```

Add:

```apache
Listen 443

SSLProtocol all -SSLv3 -TLSv1 -TLSv1.1
SSLHonorCipherOrder On

SSLCipherSuite HIGH:!aNULL:!MD5:!3DES
SSLProxyCipherSuite HIGH:!aNULL:!MD5:!3DES

SSLSessionCache "shmcb:C:/Apache24/logs/ssl_scache(512000)"
SSLSessionCacheTimeout 300
<VirtualHost *:443>
    ServerName honeypot.wise.local
    ServerAlias *.wise.local
    
    DocumentRoot "C:/Apache24/htdocs/honeypot/Public"
    ServerSignature Off
    <Directory "C:/Apache24/htdocs/honeypot/Public">
        Options -Indexes +FollowSymLinks
        AllowOverride None
        Require all granted
        DirectoryIndex index.php
    </Directory>
    <LocationMatch "^/\.git(/|$)">
        Require all denied
    </LocationMatch>
    <FilesMatch "\.(env|log|ini|bak|sql|pem)$">
        Require all denied
    </FilesMatch>
    SSLEngine on
    SSLCertificateFile "C:/Apache24/ssl/wildcard.wise.local.crt"
    SSLCertificateKeyFile "C:/Apache24/ssl/wildcard.wise.local.key"
    <IfModule mod_headers.c>
        Header always set X-Content-Type-Options "nosniff"
        Header always set X-Frame-Options "SAMEORIGIN"
        Header always set Referrer-Policy "no-referrer-when-downgrade"
        Header always set Permissions-Policy "geolocation=(), microphone=()"
        Header always set Strict-Transport-Security "max-age=31536000"
        Header always set Content-Security-Policy "default-src 'self'; \
            script-src 'self' 'unsafe-inline'; \
            style-src 'self'; \
            img-src 'self' data:; \
            font-src 'self'; \
            connect-src 'self'; \
            object-src 'none'; \
            frame-ancestors 'none'; \
            base-uri 'self'; \
            form-action 'self'; \
            manifest-src 'self';"
    </IfModule>
    <FilesMatch "\.(php|html)$">
        Header set Cache-Control "no-store, no-cache, must-revalidate, private"
    </FilesMatch>
    <FilesMatch "\.(js|css|jpg|jpeg|png|gif|ico|woff|woff2|ttf|eot|svg|json)$">
        Header set Cache-Control "public, max-age=31536000, immutable"
    </FilesMatch>
    RewriteEngine On
    RewriteRule \.(css|js|jpg|jpeg|png|gif|ico|woff|woff2|ttf|eot|svg|json)$ - [L,NC]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ index.php [L]
    ErrorLog "logs/honeypot_ssl_error.log"
    CustomLog "logs/honeypot_ssl_access.log" common
</VirtualHost>
```

---

## Step 15: Enable Apache Modules and Virtual Hosts

Open:

```
notepad C:\Apache24\conf\httpd.conf
```
Update the following directive:

```apache
DirectoryIndex index.php
```

Update the `ServerName` directive:

Change from:

```apache
#ServerName www.example.com:80
```

To:

```apache
ServerName localhost:80
```

Ensure the `#` symbol is removed.

---

Ensure the following modules are enabled:

```apache
LoadModule ssl_module modules/mod_ssl.so
LoadModule rewrite_module modules/mod_rewrite.so
LoadModule headers_module modules/mod_headers.so
LoadModule socache_shmcb_module modules/mod_socache_shmcb.so
```

---

### Link PHP with Apache and include virtual host configurations

Add the following lines at the bottom of `httpd.conf`:

```apache
# PHP module
PHPIniDir "C:/php"
LoadModule php_module "C:/php/php8apache2_4.dll"
AddType application/x-httpd-php .php

Include conf/extra/honeypot-http.conf
Include conf/extra/honeypot-ssl.conf
```
> **Note:**  
> The PHP Apache module filename **php8apache2_4.dll** may vary depending on the installed PHP version.
---

## Step 16: Restart Apache

Open PowerShell as Administrator:

```powershell
httpd -k restart
```

If Apache is not installed as a service yet:

```powershell
httpd -k install
httpd -k start
```

---

## Step 17: Configure Local DNS Resolution

Open:

```
notepad C:\Windows\System32\drivers\etc\hosts
```

Add:

```txt
127.0.0.1 honeypot.wise.local
127.0.0.1 cp.wise.local
127.0.0.1 api.wise.local
```

Save the file.

---

## Result

WISETrap now supports trusted internal HTTPS communication for:

- https://honeypot.wise.local
- https://cp.wise.local
- https://api.wise.local

After importing the Root CA certificate, browsers should no longer display SSL warnings.

---