[ 🇺🇸 English ](README.md) | [ 🇪🇸 Español ](README.es.md) | [ 🇧🇷 Português ](README.pt.md)

---

# Validación de Entradas y Codificación de Salida Segura (PHP)

![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?logo=php&logoColor=white)
![OWASP](https://img.shields.io/badge/OWASP-A03%20Injection-red)
![Enfoque](https://img.shields.io/badge/enfoque-AppSec-0284c7)

Una pequeña aplicación PHP que demuestra los dos hábitos que frenan la mayoría de las fallas de inyección: **validar la entrada con una allowlist** y **codificar la salida según el contexto donde se muestra**. Simula un formulario de analista donde se envían un usuario, un correo y una *URL sospechosa* para inspección.

![Vista previa del proyecto](preview.png)

## Modelo de amenazas

| Amenaza | OWASP Top 10 (2021) | Mitigación | Dónde |
|---|---|---|---|
| XSS reflejado en los campos del formulario | A03 Injection | Codificación de salida con `htmlspecialchars(ENT_QUOTES \| ENT_SUBSTITUTE, 'UTF-8')` al renderizar | `security.php` → `e()` |
| Entrada malformada o excesiva | A03 Injection | Allowlist en el servidor: regex para el usuario, `FILTER_VALIDATE_EMAIL`, límites de tamaño | `process.php` |
| URLs `javascript:` / `data:` | A03 Injection | La URL solo se acepta si es válida **y** su esquema es `http` o `https` | `process.php` |
| Cross-Site Request Forgery | A01 Broken Access Control | Token aleatorio por sesión verificado con `hash_equals()` | `security.php` |
| Script que evada las demás defensas | A05 Security Misconfiguration | `Content-Security-Policy` estricta (sin scripts ni estilos inline), `nosniff`, `X-Frame-Options: DENY` | `security.php` |
| Robo de la cookie de sesión | A07 Identification & Auth Failures | Cookie con `HttpOnly`, `SameSite=Strict`, `Secure` en HTTPS y `use_strict_mode` | `security.php` |

## Decisión de diseño: validar en la entrada, codificar en la salida

La primera versión escapaba los datos **apenas llegaban**. Funciona en una página, pero el valor guardado queda atado a HTML: se rompe al reutilizarlo en una URL, JSON o SQL, y aparece doblemente escapado si se vuelve a mostrar. La versión actual conserva el valor original, **rechaza** lo que no coincide con la allowlist y escapa **solo al generar el HTML**.

## Pruebas de defensa

Ejecuta la aplicación (ver abajo) y prueba:

| Entrada | Campo | Resultado esperado |
|---|---|---|
| `<script>alert('XSS')</script>` | Usuario | Rechazado por la allowlist; se muestra como texto inerte |
| `javascript:alert(1)` | URL | Rechazada: solo se aceptan URLs http/https |
| `analista@@empresa` | Correo | Formato de correo inválido |
| POST sin `csrf_token` | — | HTTP 403 |

```bash
# Prueba de CSRF: una petición sin token debe ser rechazada (403)
curl -i -X POST http://localhost:8000/process.php -d "username=prueba"
```

## Cómo ejecutar

Requisito: PHP 8.0+.

```bash
git clone https://github.com/MattheusAlfonso/01-input-validation-security.git
cd 01-input-validation-security
php -S localhost:8000
```

Abre <http://localhost:8000/Projeto1.php>.

## Lo que aprendí

- Escapar no es validar: `htmlspecialchars()` hace que el dato sea seguro **para HTML**, no seguro en general.
- Las allowlists (lo que *sí* se permite) son más sólidas que las blocklists (lo que *no*).
- La CSP es una segunda capa: aunque se cuele un error de codificación, los scripts inline no se ejecutan.

## Próximos pasos

- [ ] Pruebas automatizadas (PHPUnit) para cada payload de la tabla
- [ ] Consultar el dominio de la URL enviada en un feed de threat intel

---
*Parte de mi [portafolio de ciberseguridad](https://mattheusalfonso.github.io).*
