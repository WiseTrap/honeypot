# WISETrap SSL Wildcard Setup (Self-Signed CA)

This document explains how to create and use a self-signed Wildcard SSL certificate for WISETrap internal environment.

Important note about naming:
This document uses the term "SSL" (Secure Sockets Layer) because it is the common historical name.
However, the protocol actually used by the openssl commands (with -sha256, etc.) is TLS 1.2/1.3 (Transport Layer Security) – the modern, secure standard.
Old SSL versions (SSLv2, SSLv3) are deprecated and insecure. Whenever you see "SSL" in this guide, we mean TLS in practice.
---

## 1. Create Root Certificate Authority (CA)

```bash
mkdir ~/ssl && cd ~/ssl

openssl genrsa -out wise-rootCA.key 4096

openssl req -x509 -new -nodes \
  -key wise-rootCA.key \
  -sha256 -days 3650 \
  -out wise-rootCA.crt \
  -subj "/C=JO/O=WISETrap/OU=RootCA/CN=WISETrap Root CA"
```

---

## 2. Create Wildcard Config

```bash
nano wildcard.wise.local.cnf
```

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

---

## 3. Generate Certificate

```bash
openssl genrsa -out wildcard.wise.local.key 2048

openssl req -new \
  -key wildcard.wise.local.key \
  -out wildcard.wise.local.csr \
  -config wildcard.wise.local.cnf
```

---

## 4. Sign Certificate with Root CA

```bash
openssl x509 -req \
  -in wildcard.wise.local.csr \
  -CA wise-rootCA.crt \
  -CAkey wise-rootCA.key \
  -CAcreateserial \
  -out wildcard.wise.local.crt \
  -days 825 -sha256 \
  -extensions req_ext \
  -extfile wildcard.wise.local.cnf
```

---

## 5. Install in Apache

```bash
sudo mkdir -p /etc/apache2/ssl

sudo cp wildcard.wise.local.crt /etc/apache2/ssl/
sudo cp wildcard.wise.local.key /etc/apache2/ssl/
```

---

## 6. Trust Root CA (IMPORTANT)

Install `wise-rootCA.crt` on all client machines:
- Windows → Trusted Root Certificates
- Linux → update-ca-certificates
- Browser import if needed

---

## Result

Now all subdomains work:

- https://honeypot.wise.local
- https://cp.wise.local
- https://api.wise.local

without browser warnings.