<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuida Bem - Login</title>
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
        }
        
        .btn:hover {
            background: #357ae8;
            transform: translateY(-2px);
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
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">Cuida Bem</div>
        
        <?php
        // Inicializar variáveis
        $mensagem_erro = '';
        $mensagem_sucesso = '';
        
        if (isset($_POST['login'])) {
            include('conexao.php');
            
            $email = mysqli_real_escape_string($id, $_POST['email']);
            $senha = $_POST['senha'];
            
            $sql = "SELECT * FROM usuario WHERE email = '$email'";
            $res = mysqli_query($id, $sql);
            
            if ($res && mysqli_num_rows($res) > 0) {
                $usuario = mysqli_fetch_assoc($res);
                
                // Verificar senha (usando password_verify para senhas hash)
                if (password_verify($senha, $usuario['senha'])) {
                    $_SESSION['usuario'] = $usuario;
                    
                    // Redirecionar baseado no tipo de usuário
                    if ($usuario['tipo'] == 'idoso') {
                        header("Location: idoso_dashboard.php");
                    } else {
                        header("Location: dashboard.php");
                    }
                    exit;
                } else {
                    $mensagem_erro = "Senha incorreta!";
                }
            } else {
                $mensagem_erro = "Usuário não encontrado!";
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
        
        <form method="POST">
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            
            <button type="submit" name="login" class="btn">Entrar</button>
        </form>
        
        <div class="links">
    <a href="cadastrar.php">Cadastrar-se</a>
    <a href="esqueci_senha.php">Esqueceu a senha?</a>
</div>
    </div>
    <?php include 'footer.php'; ?>
<?php echo rodapeLogin(); ?>
</body>
</html>