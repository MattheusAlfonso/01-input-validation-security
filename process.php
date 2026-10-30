<?php
require_once 'security.php';

send_security_headers();
start_secure_session();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: Projeto1.php');
    exit();
}

if (!csrf_is_valid($_POST['csrf_token'] ?? null)) {
    http_response_code(403);
    exit('Requisição inválida (token CSRF ausente ou incorreto).');
}

// 1) VALIDAÇÃO na entrada (allowlist): rejeita o que não está no formato esperado.
// 2) ENCODING na saída: e() é aplicado só no HTML, então o dado original nunca é alterado.

$username       = trim((string)($_POST['username'] ?? ''));
$username_valid = preg_match('/^[A-Za-z0-9_.-]{3,32}$/', $username) === 1;

$email       = trim((string)($_POST['email'] ?? ''));
$email_valid = strlen($email) <= 254 && filter_var($email, FILTER_VALIDATE_EMAIL) !== false;

// A URL é tratada como um artefato suspeito: só http/https são aceitos,
// bloqueando esquemas perigosos como javascript: e data:
$target_url = trim((string)($_POST['target_url'] ?? ''));
$url_scheme = strtolower((string)parse_url($target_url, PHP_URL_SCHEME));
$url_valid  = strlen($target_url) <= 2048
    && filter_var($target_url, FILTER_VALIDATE_URL) !== false
    && in_array($url_scheme, ['http', 'https'], true);
$url_host   = $url_valid ? (string)parse_url($target_url, PHP_URL_HOST) : '';

$terms_accepted = isset($_POST['terms']) && $_POST['terms'] === 'accepted';

function status(bool $ok, string $okText, string $failText): string
{
    return $ok
        ? '<span class="ok">✓ ' . e($okText) . '</span>'
        : '<span class="fail">✗ ' . e($failText) . '</span>';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado da Análise - Security Output</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Relatório de Análise de Input</h2>
        <p>Dados validados (allowlist) no backend e codificados na saída</p>

        <div class="report">
            <p><strong>Usuário:</strong> <?= status($username_valid, 'Formato válido', 'Rejeitado: use 3 a 32 caracteres (letras, números, _ . -)') ?><br>
            <code><?= e($username) ?></code></p>

            <p><strong>E-mail:</strong> <?= status($email_valid, 'Formato válido', 'Formato de e-mail inválido') ?><br>
            <code><?= e($email) ?></code></p>

            <p><strong>URL Analisada:</strong> <?= status($url_valid, 'URL http(s) válida', 'Rejeitada: apenas URLs http/https são aceitas') ?><br>
            <code><?= e($target_url) ?></code>
            <?php if ($url_valid): ?><br>Domínio: <code><?= e($url_host) ?></code><?php endif; ?></p>

            <p><strong>Termos de Uso:</strong><br>
            <?= status($terms_accepted, 'Confirmado', 'Não aceito') ?></p>
        </div>

        <a href="Projeto1.php" class="back-link">← Voltar ao Formulário</a>
    </div>
</body>
</html>
