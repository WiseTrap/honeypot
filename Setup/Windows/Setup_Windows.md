# WISETrap Honeypot — Windows Deployment Guide

This guide provides step-by-step instructions for deploying the WISETrap multi-vector honeypot framework on a fresh Microsoft Windows environment using Apache, PHP, and MySQL.

WISETrap is a modular cybersecurity honeypot platform designed to emulate vulnerable web applications and exposed attack surfaces for the purpose of monitoring, logging, analyzing, and studying malicious activity in controlled environments.

The framework is intended for defensive cybersecurity operations, security research, detection engineering, threat intelligence, and educational use only.

> WISETrap must be deployed exclusively in authorized, isolated, and legally compliant environments. The developers and contributors are not responsible for any misuse, unauthorized deployment, or illegal activity conducted using this software.

---

## Prerequisites
- [Windows 10, Windows 11, or Windows Server](https://www.microsoft.com/software-download?github.com/WiseTrap)
- Administrator privileges

---

### Step 1: Install Apache
 - Download Apache 2.4.x Win64 from:  
 [https://www.apachelounge.com/download/](https://www.apachelounge.com/download/)
   - After downloading Apache, move it to the correct path, preferably `C:/apache`.

---

### Step 2: Install PHP
 - Download PHP 8.x **Thread Safe** from:  
 [https://windows.php.net/download](https://windows.php.net/download)
   - After downloading PHP, move it to the correct path, preferably `C:/php`.

---

### Step 3: Install MySQL
 - Download MySQL Installer 8.x from: 
  [https://dev.mysql.com/downloads/installer/](https://dev.mysql.com/downloads/installer/)
   - After downloading MySQL, Install MySQL Server & MySQL Workbench

---

### Step 4: Install OpenSSL
- Download OpenSSL v4.x **Light** from:
  [https://slproweb.com/products/Win32OpenSSL.html](https://slproweb.com/products/Win32OpenSSL.html)
    - After downloading OpenSSL, Destination location: `C:\Program Files\OpenSSL-Win64`.

---

### Step 5: Configure Environment Variables (PATH)
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
  C:\Apache\bin
  ```

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

## Step 6: Verify Installation
Run in PowerShell or CMD:

```bash
apache -v
php -v
mysql --version
openssl version
```

Expected result: all commands return version numbers.

---

## Step 7: SSL Certificate Setup (Required)

This step is required before configuring HTTPS (443) for WISETrap.

### Note

If you have not created a self-signed SSL wildcard certificate yet, you MUST complete the following guide first:

[SSL_Windows.md](https://github.com/WiseTrap/honeypot/blob/main/Setup/Windows/SSL_Windows.md)

This guide covers:

- Root Certificate Authority (CA) creation
- Wildcard certificate generation (*.wise.local)
- Certificate signing process
- Apache SSL configuration
- Trusted Root installation on Windows

You must complete SSL setup before continuing with Apache HTTPS virtual host configuration.

---