<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuida Bem - Recuperar Senha</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #4a90e2, #5cb85c);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .container {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            max-width: 400px;
            width: 90%;
        }
        
        .logo {
            font-size: 2em;
            color: #4a90e2;
            text-align: center;
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 500;
        }
        
        input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1em;
            transition: border-color 0.3s;
        }
        
        input:focus {
            border-color: #4a90e2;
            outline: none;
        }
        
        .btn {
            width: 100%;
            padding: 15px;
            background: #4a90e2;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 15px;
        }
        
        .btn:hover {
            background: #357ae8;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: #6c757d;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .links {
            text-align: center;
            margin-top: 20px;
        }
        
        .links a {
            color: #4a90e2;
            text-decoration: none;
            margin: 0 10px;
        }
        
        .links a:hover {
            text-decoration: underline;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .steps {
            margin-bottom: 20px;
        }
        
        .step {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .step-number {
            background: #4a90e2;
            color: white;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">Cuida Bem</div>
        <div class="subtitle">Recuperação de Senha</div>
        
        <?php
        include('conexao.php');
        
        $mensagem_erro = '';
        $mensagem_sucesso = '';
        $exibir_form_email = true;
        $exibir_form_codigo = false;
        $exibir_form_nova_senha = false;
        $email_recuperacao = '';
        
        // Etapa 1: Solicitar recuperação por email
        if (isset($_POST['solicitar_recuperacao'])) {
            $email = mysqli_real_escape_string($id, $_POST['email']);
            
            $sql = "SELECT * FROM usuario WHERE email = '$email'";
            $res = mysqli_query($id, $sql);
            
            if ($res && mysqli_num_rows($res) > 0) {
                $usuario = mysqli_fetch_assoc($res);
                
                // Gerar código de recuperação (6 dígitos)
                $codigo_recuperacao = sprintf("%06d", mt_rand(1, 999999));
                $expiracao = date('Y-m-d H:i:s', strtotime('+1 hour'));
                
                // Salvar código no banco (em produção, use uma tabela específica para recuperação)
                $update_sql = "UPDATE usuario SET codigo_recuperacao = '$codigo_recuperacao', 
                               expiracao_codigo = '$expiracao' WHERE email = '$email'";
                mysqli_query($id, $update_sql);
                
                // Em produção, enviar email com o código
                // Para desenvolvimento, vamos exibir o código na tela
                $_SESSION['codigo_recuperacao'] = $codigo_recuperacao;
                $_SESSION['email_recuperacao'] = $email;
                $_SESSION['expiracao_codigo'] = $expiracao;
                
                $mensagem_sucesso = "Código de recuperação enviado para seu email!<br>
                                    <strong>Código (para testes): $codigo_recuperacao</strong>";
                $exibir_form_email = false;
                $exibir_form_codigo = true;
                $email_recuperacao = $email;
                
            } else {
                $mensagem_erro = "Email não encontrado em nosso sistema!";
            }
        }
        
        // Etapa 2: Verificar código
        if (isset($_POST['verificar_codigo'])) {
            $codigo_digitado = $_POST['codigo'];
            $email = $_SESSION['email_recuperacao'];
            
            $sql = "SELECT * FROM usuario WHERE email = '$email' AND codigo_recuperacao = '$codigo_digitado' 
                    AND expiracao_codigo > NOW()";
            $res = mysqli_query($id, $sql);
            
            if ($res && mysqli_num_rows($res) > 0) {
                $exibir_form_codigo = false;
                $exibir_form_nova_senha = true;
                $mensagem_sucesso = "Código verificado com sucesso! Agora defina sua nova senha.";
            } else {
                $mensagem_erro = "Código inválido ou expirado!";
                $exibir_form_codigo = true;
            }
        }
        
        // Etapa 3: Redefinir senha
        if (isset($_POST['redefinir_senha'])) {
            $nova_senha = $_POST['nova_senha'];
            $confirmar_senha = $_POST['confirmar_senha'];
            $email = $_SESSION['email_recuperacao'];
            
            if ($nova_senha !== $confirmar_senha) {
                $mensagem_erro = "As senhas não coincidem!";
                $exibir_form_nova_senha = true;
            } elseif (strlen($nova_senha) < 6) {
                $mensagem_erro = "A senha deve ter pelo menos 6 caracteres!";
                $exibir_form_nova_senha = true;
            } else {
                // Hash da nova senha
                $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
                
                $update_sql = "UPDATE usuario SET senha = '$senha_hash', 
                               codigo_recuperacao = NULL, expiracao_codigo = NULL 
                               WHERE email = '$email'";
                
                if (mysqli_query($id, $sql)) {
                    $mensagem_sucesso = "Senha redefinida com sucesso! Você já pode fazer login com sua nova senha.";
                    $exibir_form_nova_senha = false;
                    
                    // Limpar sessão
                    unset($_SESSION['codigo_recuperacao']);
                    unset($_SESSION['email_recuperacao']);
                    unset($_SESSION['expiracao_codigo']);
                } else {
                    $mensagem_erro = "Erro ao redefinir senha. Tente novamente.";
                    $exibir_form_nova_senha = true;
                }
            }
        }
        
        // Exibir mensagens
        if (!empty($mensagem_erro)) {
            echo "<div class='error'>$mensagem_erro</div>";
        }
        
        if (!empty($mensagem_sucesso)) {
            echo "<div class='success'>$mensagem_sucesso</div>";
        }
        ?>
        
        <!-- Etapa 1: Solicitar recuperação por email -->
        <?php if ($exibir_form_email): ?>
        <div class="steps">
            <div class="step">
                <div class="step-number">1</div>
                <div>Informe seu email cadastrado</div>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <div>Digite o código recebido</div>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <div>Redefina sua senha</div>
            </div>
        </div>
        
        <form method="POST">
            <div class="form-group">
                <label for="email">Email cadastrado:</label>
                <input type="email" id="email" name="email" required placeholder="seu@email.com">
            </div>
            
            <button type="submit" name="solicitar_recuperacao" class="btn">Enviar Código de Recuperação</button>
        </form>
        <?php endif; ?>
        
        <!-- Etapa 2: Verificar código -->
        <?php if ($exibir_form_codigo): ?>
        <div class="info">
            Código enviado para: <strong><?php echo $email_recuperacao; ?></strong>
        </div>
        
        <form method="POST">
            <div class="form-group">
                <label for="codigo">Código de 6 dígitos:</label>
                <input type="text" id="codigo" name="codigo" required maxlength="6" 
                       placeholder="000000" pattern="[0-9]{6}" title="Digite o código de 6 dígitos">
            </div>
            
            <button type="submit" name="verificar_codigo" class="btn">Verificar Código</button>
        </form>
        <?php endif; ?>
        
        <!-- Etapa 3: Redefinir senha -->
        <?php if ($exibir_form_nova_senha): ?>
        <form method="POST">
            <div class="form-group">
                <label for="nova_senha">Nova Senha:</label>
                <input type="password" id="nova_senha" name="nova_senha" required 
                       minlength="6" placeholder="Mínimo 6 caracteres">
            </div>
            
            <div class="form-group">
                <label for="confirmar_senha">Confirmar Nova Senha:</label>
                <input type="password" id="confirmar_senha" name="confirmar_senha" required 
                       minlength="6" placeholder="Digite a senha novamente">
            </div>
            
            <button type="submit" name="redefinir_senha" class="btn">Redefinir Senha</button>
        </form>
        <?php endif; ?>
        
        <div class="links">
            <a href="login.php">← Voltar para o Login</a>
        </div>
        
        <?php if (!$exibir_form_email && !$exibir_form_codigo && !$exibir_form_nova_senha): ?>
        <div class="links">
            <a href="login.php" class="btn btn-secondary">Fazer Login</a>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>