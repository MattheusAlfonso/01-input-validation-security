#  Validador & Sanitizador Seguro de Inputs (PHP/HTML5)

Uma aplicação web leve desenvolvida em PHP e HTML5 para demonstrar técnicas de higienização de dados de entrada e mecanismos de defesa contra ataques de Cross-Site Scripting (XSS) e manipulação de parâmetros.

##  Propósito e Escopo
Este projeto serve como uma implementação prática de processamento seguro de dados de entrada em ambientes web. Ele simula um formulário de análise de ameaças onde os dados enviados pelo usuário são estritamente sanitizados e validados no lado do servidor antes de serem exibidos ou processados.

##  Camadas de Defesa Implementadas
* **Mitigação de XSS:** Uso da função `htmlspecialchars()` com `ENT_QUOTES` e codificação `UTF-8` para prevenir Cross-Site Scripting (Refletido e Armazenado).
* **Validação de E-mail:** Filtragem nativa em PHP através de `filter_var(..., FILTER_VALIDATE_EMAIL)` para garantir conformidade com os padrões RFC.
* **Tráfego Seguro via POST:** Envio obrigatório dos dados pelo método `POST`, evitando a exposição de parâmetros sensíveis em URLs (`GET`) ou logs de acesso do servidor HTTP.
* **Verificação Estricta de Estado:** Validação do checkbox de termos no backend utilizando operadores de comparação estrita (`===`).

## 🧪 Prova de Conceito (Teste de Defesa contra XSS)
Ao submeter um payload malicioso no campo de nome de usuário:
´´html
<script>alert('XSS')</script>

Resultado: O backend neutraliza o script executável e renderiza o texto de forma segura como &lt;script&gt;alert('XSS')&lt;/script&gt;. Não ocorre nenhuma execução de código JavaScript no navegador.

Como Executar

1. Clone este repositório no diretório do seu servidor web local (ex: XAMPP htdocs ou LAMP /var/www/html/).

2. Inicie o serviço do Apache.

3. Acesse http://localhost/01-input-validation-security/Projeto1.php no seu navegador.
