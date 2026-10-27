<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale-1.0">
	<title>Input Validation & Sanitization Secuirity</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<div class="container">
		<h2> Validador & Sanitizador de Inputs</h2>
		<p>Teste  de Segurança e Prevenção de  injeção de Scripts (XSS)</p>

		<form action="process.php" method="POST">
			<div class="form-group">
			<label for="username">Nome de Usuário:</label>
			<input type="text" id="username" name="username" placeholder="Ex:  hackersec" required>
			</div>

			<div class="form-group">
				<label for="email">E-mail para verificação</label>
				<input type="email"  id="email" name="email" placeholder="Ex: analista@empresas.com" required>
			</div>

			<div class="form-group">
				<label for="target_url">URL suspeita para Análise</label>
				<input type="text" id="target_url" name="target_url" placeholder="Ex: https://site-suspeito.com/phising" required>
			</div>

			<div class="form-group">
				<input type="checkbox" id="terms" name="terms" value="accepted" required>
				<label for="terms">Declaro que estou analisando um artefato autorizado</label>
			</div>
			<button type="submit">Analisar & Processar Dados</button>
		</form>
	</div>
</body>
</html>