<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuida Bem - Cadastro</title>
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
            padding: 20px;
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
        
        input, select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1em;
            transition: border-color 0.3s;
        }
        
        input:focus, select:focus {
            border-color: #4a90e2;
            outline: none;
        }
        
        .user-types {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .user-type {
            flex: 1;
            text-align: center;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .user-type.active {
            border-color: #4a90e2;
            background: rgba(74, 144, 226, 0.1);
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
        }
        
        .links a:hover {
            text-decoration: underline;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">Cuida Bem</div>
        
        <?php
        // Inicializar variáveis
        $mensagem_erro = '';
        $mensagem_sucesso = '';
        $selected_user_type = 'idoso';

        if (isset($_POST['cadastrar'])) {
            include('conexao.php');
            
            $nome = mysqli_real_escape_string($id, $_POST['nome']);
            $email = mysqli_real_escape_string($id, $_POST['email']);
            $senha = $_POST['senha'];
            $tipo = mysqli_real_escape_string($id, $_POST['tipo']);
            
            // Verificar se email já existe
            $check_sql = "SELECT id_usuario FROM usuario WHERE email = '$email'";
            $check_res = mysqli_query($id, $check_sql);
            
            if (mysqli_num_rows($check_res) > 0) {
                $mensagem_erro = "Este e-mail já está cadastrado! <a href='login.php' style='color:#721c24;'>Faça login aqui</a>";
                $selected_user_type = $tipo;
            } else {
                // Hash da senha
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                
                $sql = "INSERT INTO usuario (nome, email, senha, tipo) 
                        VALUES ('$nome', '$email', '$senha_hash', '$tipo')";
                
                if (mysqli_query($id, $sql)) {
                    $id_usuario = mysqli_insert_id($id);
                    
                    // Se for idoso, criar registro na tabela paciente
                    if ($tipo == 'idoso') {
                        $paciente_sql = "INSERT INTO paciente (nome, data_nascimento, cpf) 
                                        VALUES ('$nome', NULL, '')";
                        if (mysqli_query($id, $paciente_sql)) {
                            $id_paciente = mysqli_insert_id($id);
                            
                            // Atualizar usuário com id_paciente
                            $update_sql = "UPDATE usuario SET id_paciente = $id_paciente WHERE id_usuario = $id_usuario";
                            mysqli_query($id, $update_sql);
                        }
                    }
                    
                    $mensagem_sucesso = "Cadastro realizado com sucesso! <a href='login.php' style='color:#155724;'>Faça login aqui</a>";
                    // Limpar o formulário
                    echo '<script>document.getElementById("cadastro-form").reset();</script>';
                    $selected_user_type = 'idoso';
                } else {
                    $mensagem_erro = "Erro ao cadastrar: " . mysqli_error($id);
                    $selected_user_type = $tipo;
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

        <div class="info">
            <strong>Dica:</strong> Use um email diferente se já tiver conta.
        </div>
        
        <form method="POST" id="cadastro-form">
            <div class="form-group">
                <label for="nome">Nome Completo:</label>
                <input type="text" id="nome" name="nome" value="<?php echo isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required minlength="6">
            </div>
            
            <h3>Entrar como:</h3>
            <div class="user-types">
                <div class="user-type <?php echo $selected_user_type == 'idoso' ? 'active' : ''; ?>" onclick="selectUserType('idoso')">Idoso</div>
                <div class="user-type <?php echo $selected_user_type == 'cuidador' ? 'active' : ''; ?>" onclick="selectUserType('cuidador')">Cuidador</div>
                <div class="user-type <?php echo $selected_user_type == 'familiar' ? 'active' : ''; ?>" onclick="selectUserType('familiar')">Familiar</div>
            </div>
            
            <input type="hidden" name="tipo" id="tipo" value="<?php echo $selected_user_type; ?>">
            
            <button type="submit" name="cadastrar" class="btn">Cadastrar</button>
        </form>
        
        <div class="links">
            <a href="login.php">Já tem conta? Faça login</a>
        </div>
    </div>

    <script>
        function selectUserType(type) {
            document.querySelectorAll('.user-type').forEach(el => {
                el.classList.remove('active');
            });
            event.target.classList.add('active');
            document.getElementById('tipo').value = type;
        }

        // Verificar se há mensagem de sucesso e redirecionar após 3 segundos
        <?php if (!empty($mensagem_sucesso)): ?>
        setTimeout(function() {
            window.location.href = 'login.php';
        }, 3000);
        <?php endif; ?>
    </script>

    <!-- Rodapé Simples -->
    <div style='
        background: rgba(44, 62, 80, 0.9);
        color: white;
        text-align: center;
        padding: 15px;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        font-size: 12px;
    '>
        &copy; <?php echo date('Y'); ?> Cuida Bem - Todos os direitos reservados
    </div>
</body>
</html>