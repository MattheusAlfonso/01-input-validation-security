[ 🇺🇸 English ](README.md) | [ 🇪🇸 Español ](README.es.md) | [ 🇧🇷 Português ](README.pt.md)

---

# Secure Input Validation & Output Encoding (PHP)

![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?logo=php&logoColor=white)
![OWASP](https://img.shields.io/badge/OWASP-A03%20Injection-red)
![Focus](https://img.shields.io/badge/focus-AppSec-0284c7)

A small PHP web app that shows the two habits that stop most injection bugs: **validate input against an allowlist** and **encode output for the context where it is displayed**. It simulates an analyst form where a username, an e-mail and a *suspicious URL* are submitted for inspection.

![Project Preview](preview.png)

## Threat model

| Threat | OWASP Top 10 (2021) | Mitigation | Where |
|---|---|---|---|
| Reflected XSS via form fields | A03 Injection | Context-aware output encoding with `htmlspecialchars(ENT_QUOTES \| ENT_SUBSTITUTE, 'UTF-8')` at render time | `security.php` → `e()` |
| Malformed or oversized input | A03 Injection | Server-side allowlist: regex for username, `FILTER_VALIDATE_EMAIL`, length limits | `process.php` |
| `javascript:` / `data:` URLs | A03 Injection | URL is accepted only if valid **and** its scheme is `http` or `https` | `process.php` |
| Cross-Site Request Forgery | A01 Broken Access Control | Per-session random token checked with `hash_equals()` | `security.php` |
| Script injection that slips through | A05 Security Misconfiguration | Strict `Content-Security-Policy` (no inline scripts or styles), `nosniff`, `X-Frame-Options: DENY` | `security.php` |
| Session cookie theft | A07 Identification & Auth Failures | Cookie flags `HttpOnly`, `SameSite=Strict`, `Secure` on HTTPS, `use_strict_mode` | `security.php` |

## Key design decision: validate on input, encode on output

The first version escaped data **as soon as it arrived**. That works for one page, but the stored value is now HTML-specific: it breaks when reused in a URL, JSON or SQL, and gets double-escaped if displayed again. The current version keeps the original value, **rejects** what does not match the allowlist, and escapes **only when rendering HTML**.

## Defense tests

Run the app (see below) and try:

| Input | Field | Expected result |
|---|---|---|
| `<script>alert('XSS')</script>` | Username | Rejected by the allowlist; shown as inert text |
| `javascript:alert(1)` | URL | Rejected: only http/https URLs are accepted |
| `analyst@@company` | E-mail | Invalid e-mail format |
| POST without `csrf_token` | — | HTTP 403 |

```bash
# CSRF test: request without token must be refused (403)
curl -i -X POST http://localhost:8000/process.php -d "username=test"
```

## How to run

Requirements: PHP 8.0+.

```bash
git clone https://github.com/MattheusAlfonso/01-input-validation-security.git
cd 01-input-validation-security
php -S localhost:8000
```

Open <http://localhost:8000/Projeto1.php>.

## What I learned

- Escaping is not validation: `htmlspecialchars()` makes data safe **for HTML**, not safe in general.
- Allowlists (what *is* allowed) are stronger than blocklists (what is *not*).
- CSP is a second layer: even if an encoding bug slips through, inline scripts do not run.

## Roadmap

- [ ] Automated tests (PHPUnit) for every payload in the table above
- [ ] Look up the submitted URL's domain against a threat-intel feed

---
*Part of my [cybersecurity portfolio](https://mattheusalfonso.github.io).*
