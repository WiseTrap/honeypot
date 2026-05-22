# WISETrap Honeypot — Ubuntu Deployment Guide

This guide provides step-by-step instructions for deploying the WISETrap multi-vector honeypot framework on a fresh Ubuntu Server environment using Apache, PHP, and MySQL.

WISETrap is a modular cybersecurity honeypot platform designed to emulate vulnerable web applications and exposed attack surfaces for the purpose of monitoring, logging, analyzing, and studying malicious activity in controlled environments.

The framework is intended for defensive cybersecurity operations, security research, detection engineering, threat intelligence, and educational use only.

> WISETrap must be deployed exclusively in authorized, isolated, and legally compliant environments. The developers and contributors are not responsible for any misuse, unauthorized deployment, or illegal activity conducted using this software.

---

## Prerequisites

- [Ubuntu Server (minimal installation is fine)](https://ubuntu.com/download/server?github.com/WiseTrap)
- `sudo` access

---

## Step 1: Install Apache, PHP, MySQL, and Git

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install apache2 mysql-server php libapache2-mod-php php-mysql git -y
sudo mysql_secure_installation
sudo nano /etc/apache2/mods-enabled/dir.conf
```

---

## Step 2: Create a dedicated system user for the honeypot (no login shell)

```bash
sudo useradd -r -s /usr/sbin/nologin honeypot
```

---

## Step 3: Clone the repository

```bash
sudo -u root git clone https://github.com/WiseTrap/honeypot.git /var/www/honeypot
```

---

## Step 3.1: Configure environment file

The project uses a local `.env` file for sensitive configuration such as:

- Database credentials
- Application URL
- Secret keys

The `.env` file is intentionally ignored by Git and is not updated from the repository.

Create your local environment file:

```bash
cp /var/www/honeypot/.env.example /var/www/honeypot/.env
```

Then edit it:

```bash
nano /var/www/honeypot/.env
```

Example configuration:

```env
DOMAIN=honeypot.wise.local
DB_HOST=localhost
DB_NAME=wisedb
DB_PORT=3306
DB_USER=wiseUser
DB_PASSWORD=W1se@2026#SecurePwd
```

---

## Step 3.2: Configure Server Timezone

Set the server timezone to Jordan timezone to ensure that PHP, MySQL, logs, and honeypot events use the correct local time.

```bash
sudo timedatectl set-timezone Asia/Amman
timedatectl
```

Expected output:

```text
Time zone: Asia/Amman (+03, +0300)
```

Restart MySQL service:

```bash
sudo systemctl restart mysql
```

---

## Step 3.3: Import Database
Import the prepared SQL file included in the project:

```bash
sudo mysql < /var/www/honeypot/Databases.sql
```

---
## Step 4: Set secure permissions

```bash
sudo chown -R honeypot:www-data /var/www/honeypot
sudo chmod -R 755 /var/www/honeypot
sudo  chown -R www-data:www-data /var/www/honeypot/Storage
```

---

## Step 5: Enable Required Apache Modules

```bash
sudo a2enmod rewrite headers ssl
sudo systemctl reload apache2
```

### Note

If you have not created a self-signed SSL wildcard certificate yet, please refer to the following guide before continuing:

[SSL_Linux.md](https://github.com/WiseTrap/honeypot/blob/main/Setup/Linux/SSL_Linux.md)

This step is required before configuring the HTTPS (443) virtual host.

---

## Step 6: Configure Apache virtual host

```bash
sudo nano /etc/apache2/sites-available/honeypot.conf
```

Paste the following (change `ServerName` to your `domain` or IP):
```bash
<VirtualHost *:80>
    ServerName honeypot.wise.local
    DocumentRoot /var/www/honeypot/Public

    RewriteEngine On
    RewriteCond %{HTTPS} !=on
    RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

    <Directory /var/www/honeypot/Public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/honeypot_error.log
    CustomLog ${APACHE_LOG_DIR}/honeypot_access.log combined
</VirtualHost>
```

Enable the site:
```bash
sudo a2ensite honeypot.conf
sudo systemctl reload apache2
```

Add protocol SSL:
```bash
sudo nano /etc/apache2/sites-available/honeypot-ssl.conf
```

Paste the following (change `ServerName` to your `domain` or IP):
```bash
<VirtualHost *:443>
  ServerAdmin e@hotmail.com
  ServerName honeypot.wise.local
  DocumentRoot /var/www/honeypot/Public
  ErrorLog ${APACHE_LOG_DIR}/honeypot_err.log
  CustomLog ${APACHE_LOG_DIR}/honeypot_acc.log combined
  ServerSignature Off
  <Directory /var/www/honeypot/Public>
    Options -Indexes +SymLinksIfOwnerMatch
    AllowOverride None
    Require all granted
  </Directory>
  <LocationMatch "^/\.git(/|$)">
    Require all denied
  </LocationMatch>
  <FilesMatch "\.(env|log|ini|bak|sql|pem)$">
    Require all denied
  </FilesMatch>
  SSLEngine on
  SSLCertificateFile      /etc/apache2/ssl/wildcard.wise.local.crt
  SSLCertificateKeyFile   /etc/apache2/ssl/wildcard.wise.local.key
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
</VirtualHost>
```

Enable the site:
```bash
sudo a2ensite honeypot-ssl.conf
sudo systemctl reload apache2
```

---

## Step 7: Test the honeypot

[https://honeypot.wise.local](https://honeypot.wise.local)

---