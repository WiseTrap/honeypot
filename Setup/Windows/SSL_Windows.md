# WISETrap SSL Wildcard Setup for Windows (Self-Signed CA)

This guide explains how to create and use a self-signed Wildcard TLS certificate for the WISETrap internal Windows environment using OpenSSL and Apache.

> This document uses the term "SSL" (Secure Sockets Layer) because it is the commonly recognized historical term.  
> In practice, the certificates and cryptographic operations described in this guide use modern TLS (Transport Layer Security) standards.

---

## Important Notice

This certificate is intended for internal use only.

It must NOT be used in production or public-facing environments.

All generated certificates are self-signed and require manual trust installation on client systems.

---

## Step 1: Create Working Directory

Open PowerShell as Administrator:

```powershell
mkdir C:\ssl
cd C:\ssl
```

---

## Step 2: Create Root Certificate Authority (CA)

### Generate Root Key

```powershell
openssl genrsa -out wise-rootCA.key 4096
```

### Create Root Certificate

```powershell
openssl req -x509 -new -nodes -key wise-rootCA.key -sha256 -days 3650 -out wise-rootCA.crt -subj "/C=JO/O=WISETrap/OU=RootCA/CN=WISETrap Root CA"
```

---

## Step 3: Create Wildcard Configuration File

Create config file:

```powershell
notepad wildcard.wise.local.cnf
```

Paste the following:

```ini
[ req ]
default_bits       = 2048
prompt             = no
default_md         = sha256
req_extensions     = req_ext
distinguished_name = dn

[ dn ]
CN = *.wise.local
O  = WISETrap
OU = Security

[ req_ext ]
subjectAltName = @alt_names

[ alt_names ]
DNS.1 = *.wise.local
DNS.2 = localhost
IP.1  = 127.0.0.1
```

Save and close.

---

## Step 4: Generate Certificate Signing Request (CSR)

```powershell
openssl genrsa -out wildcard.wise.local.key 2048

openssl req -new -key wildcard.wise.local.key -out wildcard.wise.local.csr -config wildcard.wise.local.cnf
```

---

## Step 5: Sign Certificate with Root CA

```powershell
openssl x509 -req -in wildcard.wise.local.csr -CA wise-rootCA.crt -CAkey wise-rootCA.key -CAcreateserial -out wildcard.wise.local.crt -days 825 -sha256 -extensions req_ext -extfile wildcard.wise.local.cnf
```

---

## Step 6: Install Certificate in Apache

Create SSL directory:

```powershell
mkdir C:\Apache\ssl
```

Copy files:

```powershell
copy wise-rootCA.crt C:\Apache\ssl\
copy wildcard.wise.local.crt C:\Apache\ssl\
copy wildcard.wise.local.key C:\Apache\ssl\
```

---

## Step 7: Configure Apache HTTP Virtual Host

Open:

```
C:\Apache\conf\extra\honeypot-http.conf
```

Add:

```apache
<VirtualHost *:80>
    ServerName honeypot.wise.local

    DocumentRoot "C:/Apache/htdocs/honeypot/Public"

    <Directory "C:/Apache/htdocs/honeypot/Public">
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog "logs/honeypot_http_error.log"
    CustomLog "logs/honeypot_http_access.log" common
</VirtualHost>
```

---

## Step 8: Configure Apache SSL Virtual Host

Open:

```
C:\Apache\conf\extra\honeypot-ssl.conf
```

Add:

```apache
<VirtualHost *:443>
    ServerName honeypot.wise.local

    DocumentRoot "C:/Apache/htdocs/honeypot/Public"

    <Directory "C:/Apache/htdocs/honeypot/Public">
        AllowOverride All
        Require all granted
    </Directory>

    SSLEngine on

    SSLCertificateFile "C:/Apache/ssl/wildcard.wise.local.crt"
    SSLCertificateKeyFile "C:/Apache/ssl/wildcard.wise.local.key"

    ErrorLog "logs/honeypot_ssl_error.log"
    CustomLog "logs/honeypot_ssl_access.log" common
</VirtualHost>
```

---

## Step 9: Enable Apache Modules and Virtual Hosts

Open:

```
C:\Apache\conf\httpd.conf
```

Ensure the following modules are enabled:

```apache
LoadModule rewrite_module modules/mod_rewrite.so
LoadModule ssl_module modules/mod_ssl.so
```

Add the following lines at the bottom of the file:

```apache
Include conf/extra/honeypot-http.conf
Include conf/extra/honeypot-ssl.conf
```

---

## Step 10: Restart Apache

Open PowerShell as Administrator:

```powershell
C:\Apache\bin\httpd.exe -k restart
```

If Apache is not installed as a service yet:

```powershell
C:\Apache\bin\httpd.exe -k install
C:\Apache\bin\httpd.exe -k start
```

---

## Step 11: Trust Root Certificate on Windows

1. Press `Win + R`
2. Run:

   ```
   certmgr.msc
   ```

3. Navigate to:

   ```
   Trusted Root Certification Authorities
   ```

4. Right click → Import
5. Select:

   ```
   wise-rootCA.crt
   ```

6. Complete the import wizard

---

## Step 12: Configure Local DNS Resolution

Open:

```
C:\Windows\System32\drivers\etc\hosts
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