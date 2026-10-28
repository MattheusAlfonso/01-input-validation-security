#  Motor de Validación y Sanitización Segura de Inputs (PHP/HTML5)

Una aplicación web desarrollada con PHP y HTML5 diseñada para demostrar mecanismos de sanitización y defensa contra ataques de Cross-Site Scripting (XSS) y manipulación de parámetros.

##  Propósito y Alcance
Este proyecto sirve como una implementación práctica de procesamiento seguro de datos de entrada en entornos web. Simula un formulario de análisis de amenazas donde las entradas del usuario son sanitizadas y validadas en el lado del servidor antes de ser mostradas o procesadas.

##  Funcionalidades de Seguridad Implementadas
* **Mitigación de XSS:** Uso de `htmlspecialchars()` con `ENT_QUOTES` y codificación `UTF-8` para prevenir Cross-Site Scripting Reflejado y Almacenado.
* **Validación de Correo Electrónico:** Filtrado nativo en PHP mediante `filter_var(..., FILTER_VALIDATE_EMAIL)` para garantizar el cumplimiento de normas RFC.
* **Tráfico Seguro vía POST:** Transmisión obligatoria mediante el método `POST` para evitar la exposición de datos sensibles en URLs/parámetros `GET` o en los logs de acceso del servidor HTTP.
* **Verificación Estricta de Estado:** Control en el servidor para la aceptación de términos de uso mediante operadores de comparación estricta (`===`).

## Prueba de Concepto (Prueba de Defensa XSS)
Al enviar una carga útil (payload) de XSS en el campo de usuario:
html
<script>alert('XSS')</script>

Resultado: El backend neutraliza el script ejecutable y muestra de forma segura la cadena de texto plano como &lt;script&gt;alert('XSS')&lt;/script&gt;. No ocurre ejecución de código JavaScript en el navegador del cliente.

Cómo Ejecutar

1.Clona este repositorio en tu entorno de servidor web local (ej. XAMPP htdocs o LAMP /var/www/html/).

2. Inicia el servicio Apache.

3.Accede a http://localhost/01-input-validation-security/Projeto1.php en tu navegador.

Desarrollado como parte del Portafolio de Ciberseguridad para roles internacionales de análisis de software y amenazas.
