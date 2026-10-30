[ 🇺🇸 English ](README.md) | [ 🇪🇸 Español ](README.es.md) | [ 🇧🇷 Português ](README.pt.md)

---

# Validação de Entrada & Codificação de Saída Segura (PHP)

![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?logo=php&logoColor=white)
![OWASP](https://img.shields.io/badge/OWASP-A03%20Injection-red)
![Foco](https://img.shields.io/badge/foco-AppSec-0284c7)

Uma pequena aplicação PHP que demonstra os dois hábitos que barram a maioria das falhas de injeção: **validar a entrada com uma allowlist** e **codificar a saída para o contexto onde ela é exibida**. Simula um formulário de analista onde um usuário, um e-mail e uma *URL suspeita* são enviados para inspeção.

![Preview do Projeto](preview.png)

## Modelo de ameaças

| Ameaça | OWASP Top 10 (2021) | Mitigação | Onde |
|---|---|---|---|
| XSS refletido pelos campos do formulário | A03 Injection | Codificação de saída com `htmlspecialchars(ENT_QUOTES \| ENT_SUBSTITUTE, 'UTF-8')` na hora de exibir | `security.php` → `e()` |
| Entrada malformada ou gigante | A03 Injection | Allowlist no servidor: regex no usuário, `FILTER_VALIDATE_EMAIL`, limites de tamanho | `process.php` |
| URLs `javascript:` / `data:` | A03 Injection | A URL só é aceita se for válida **e** o esquema for `http` ou `https` | `process.php` |
| Cross-Site Request Forgery | A01 Broken Access Control | Token aleatório por sessão verificado com `hash_equals()` | `security.php` |
| Script que escape das outras defesas | A05 Security Misconfiguration | `Content-Security-Policy` restritiva (sem scripts nem estilos inline), `nosniff`, `X-Frame-Options: DENY` | `security.php` |
| Roubo do cookie de sessão | A07 Identification & Auth Failures | Cookie com `HttpOnly`, `SameSite=Strict`, `Secure` em HTTPS e `use_strict_mode` | `security.php` |

## Decisão de design: validar na entrada, codificar na saída

A primeira versão escapava os dados **assim que chegavam**. Funciona em uma página, mas o valor guardado vira algo específico de HTML: quebra quando reutilizado numa URL, JSON ou SQL, e sai escapado em dobro se exibido de novo. A versão atual mantém o valor original, **rejeita** o que não bate com a allowlist e escapa **só na hora de gerar o HTML**.

## Testes de defesa

Rode a aplicação (veja abaixo) e teste:

| Entrada | Campo | Resultado esperado |
|---|---|---|
| `<script>alert('XSS')</script>` | Usuário | Rejeitado pela allowlist; exibido como texto inerte |
| `javascript:alert(1)` | URL | Rejeitada: só URLs http/https são aceitas |
| `analista@@empresa` | E-mail | Formato de e-mail inválido |
| POST sem `csrf_token` | — | HTTP 403 |

```bash
# Teste de CSRF: requisição sem token deve ser recusada (403)
curl -i -X POST http://localhost:8000/process.php -d "username=teste"
```

## Como executar

Requisito: PHP 8.0+.

```bash
git clone https://github.com/MattheusAlfonso/01-input-validation-security.git
cd 01-input-validation-security
php -S localhost:8000
```

Acesse <http://localhost:8000/Projeto1.php>.

## O que aprendi

- Escapar não é validar: `htmlspecialchars()` deixa o dado seguro **para HTML**, não seguro em geral.
- Allowlists (o que *é* permitido) são mais fortes que blocklists (o que *não* é).
- A CSP é uma segunda camada: mesmo que um erro de codificação passe, scripts inline não executam.

## Próximos passos

- [ ] Testes automatizados (PHPUnit) para cada payload da tabela acima
- [ ] Consultar o domínio da URL enviada em um feed de threat intel

---
*Parte do meu [portfólio de cibersegurança](https://mattheusalfonso.github.io).*
