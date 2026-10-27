<?php
// Se não for POST, redireciona de volta para o formulário
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: Projeto1.php');
    exit();
}

// 1. Sanitização dos textos contra XSS
$username   = isset($_POST['username']) ? htmlspecialchars(trim($_POST['username']), ENT_QUOTES, 'UTF-8') : '';
$target_url = isset($_POST['target_url']) ? htmlspecialchars(trim($_POST['target_url']), ENT_QUOTES, 'UTF-8') : '';

// 2. Validação de E-mail
$raw_email   = $_POST['email'] ?? '';
$clean_email = filter_var($raw_email, FILTER_VALIDATE_EMAIL);

// 3. Verificação do Checkbox de Termos
$terms_accepted = isset($_POST['terms']) && $_POST['terms'] === 'accepted';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado da Análise - Security Output</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Relatório de Análise de Input</h2>
        <p>Dados higienizados e validados pelo backend PHP</p>
        
        <div style="background: #0f172a; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
            <p style="text-align: left; margin: 8px 0;"><strong>Usuário (Sanitizado):</strong><br><code><?= $username ?></code></p>
            
            <p style="text-align: left; margin: 8px 0;"><strong>E-mail (Validado):</strong><br>
            <?php if ($clean_email): ?>
                <span style="color: #4ade80;">✓ <?= htmlspecialchars($clean_email, ENT_QUOTES, 'UTF-8') ?></span>
            <?php else: ?>
                <span style="color: #f87171;">✗ Formato de E-mail Inválido!</span>
            <?php endif; ?>
            </p>

            <p style="text-align: left; margin: 8px 0;"><strong>URL Analisada:</strong><br><code><?= $target_url ?></code></p>
            
            <p style="text-align: left; margin: 8px 0;"><strong>Termos de Uso:</strong><br>
            <?= $terms_accepted ? '<span style="color: #4ade80;">✓ Confirmado</span>' : '<span style="color: #f87171;">✗ Não Aceito</span>' ?>
            </p>
        </div>

        <a href="Projeto1.php" style="display: block; text-align: center; color: #38bdf8; text-decoration: none; font-weight: bold;">← Voltar ao Formulário</a>
    </div>
</body>
</html>