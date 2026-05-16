# WISETrap Honeypot – Setup Guide for Ubuntu Server (Apache)

This guide walks you through installing the WISETrap credential honeypot on a fresh **Ubuntu Server** (20.04 / 22.04 / 24.04) with Apache, MySQL, and PHP.

The honeypot is a fake login panel that logs every login attempt (IP, timestamp, username, password, user agent) and then shows fake data to the attacker.

---

## Prerequisites

- Ubuntu Server (minimal installation is fine)
- `sudo` access
- Git installed (`sudo apt install git -y`)
- GitHub SSH key added to your `wiseTrap` organization (or use HTTPS)

---

## Step 1: Install Apache, PHP, MySQL

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install apache2 mysql-server php libapache2-mod-php php-mysql -y
sudo mysql_secure_installation && nano /etc/apache2/mods-enabled/dir.conf
```

---

## Step 2: Create a dedicated system user for the honeypot (no login shell)

```bash
sudo useradd -r -s /usr/sbin/nologin honeypot
```

---

## Step 3: Clone the repository

```bash
sudo -u root git clone git@github.com:WiseTrap/honeypot.git /var/www/honeypot
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
DB_PASSWORD=xlXXDrcfxxsZDirG
```

---

## Step 3.2: Import Database
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

## Step 5: Configure Apache virtual host

```bash
sudo nano /etc/apache2/sites-available/honeypot.conf
```

Paste the following (change `ServerName` to your `domain` or IP):
```bash
<VirtualHost *:80>
    ServerName honeypot.wise.local
    DocumentRoot /var/www/honeypot/Public

    RewriteEngine On
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

## Step 6: Enable SSL and reload Apache

```bash
sudo a2enmod rewrite headers ssl
sudo systemctl reload apache2
```

---

## Step 7: Test the honeypot

[https://honeypot.wise.local](https://honeypot.wise.local)

---