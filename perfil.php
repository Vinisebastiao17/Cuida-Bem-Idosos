<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

include('conexao.php');

$usuario = $_SESSION['usuario'];
$mensagem_sucesso = '';
$mensagem_erro = '';

// Buscar dados atualizados do usuário
$sql = "SELECT * FROM usuario WHERE id_usuario = " . $usuario['id_usuario'];
$res = mysqli_query($id, $sql);
$usuario_atualizado = mysqli_fetch_assoc($res);

// Atualizar dados do usuário na sessão
$_SESSION['usuario'] = $usuario_atualizado;
$usuario = $usuario_atualizado;

// Processar atualização do perfil
if (isset($_POST['atualizar_perfil'])) {
    $nome = mysqli_real_escape_string($id, $_POST['nome']);
    $email = mysqli_real_escape_string($id, $_POST['email']);
    
    // Verificar se o email já existe (exceto para o próprio usuário)
    $check_sql = "SELECT id_usuario FROM usuario WHERE email = '$email' AND id_usuario != " . $usuario['id_usuario'];
    $check_res = mysqli_query($id, $check_sql);
    
    if (mysqli_num_rows($check_res) > 0) {
        $mensagem_erro = "Este e-mail já está em uso por outro usuário!";
    } else {
        $update_sql = "UPDATE usuario SET nome = '$nome', email = '$email' WHERE id_usuario = " . $usuario['id_usuario'];
        
        if (mysqli_query($id, $update_sql)) {
            // Atualizar sessão
            $_SESSION['usuario']['nome'] = $nome;
            $_SESSION['usuario']['email'] = $email;
            $usuario = $_SESSION['usuario'];
            
            $mensagem_sucesso = "Perfil atualizado com sucesso!";
        } else {
            $mensagem_erro = "Erro ao atualizar perfil: " . mysqli_error($id);
        }
    }
}

// Processar alteração de senha
if (isset($_POST['alterar_senha'])) {
    $senha_atual = $_POST['senha_atual'];
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    
    // Verificar senha atual
    if (!password_verify($senha_atual, $usuario['senha'])) {
        $mensagem_erro = "Senha atual incorreta!";
    } elseif ($nova_senha !== $confirmar_senha) {
        $mensagem_erro = "As novas senhas não coincidem!";
    } elseif (strlen($nova_senha) < 6) {
        $mensagem_erro = "A nova senha deve ter pelo menos 6 caracteres!";
    } else {
        $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
        $update_sql = "UPDATE usuario SET senha = '$nova_senha_hash' WHERE id_usuario = " . $usuario['id_usuario'];
        
        if (mysqli_query($id, $update_sql)) {
            $mensagem_sucesso = "Senha alterada com sucesso!";
        } else {
            $mensagem_erro = "Erro ao alterar senha: " . mysqli_error($id);
        }
    }
}

// Buscar estatísticas do usuário
$estatisticas = [
    'total_medicamentos' => 0,
    'total_pacientes' => 0,
    'total_sintomas' => 0
];

// Contar medicamentos
$med_sql = "SELECT COUNT(*) as total FROM medicamento";
$med_res = mysqli_query($id, $med_sql);
if ($med_res) {
    $estatisticas['total_medicamentos'] = mysqli_fetch_assoc($med_res)['total'];
}

// Contar pacientes
$pac_sql = "SELECT COUNT(*) as total FROM paciente";
$pac_res = mysqli_query($id, $pac_sql);
if ($pac_res) {
    $estatisticas['total_pacientes'] = mysqli_fetch_assoc($pac_res)['total'];
}

// Contar sintomas
$sin_sql = "SELECT COUNT(*) as total FROM sintoma";
$sin_res = mysqli_query($id, $sin_sql);
if ($sin_res) {
    $estatisticas['total_sintomas'] = mysqli_fetch_assoc($sin_res)['total'];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - Cuida Bem</title>
    <style>
        <?php include 'estilo_copyright.css'; ?>
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: #f5f5f5;
            color: #333;
        }
        
        .header {
            background: #4a90e2;
            color: white;
            padding: 15px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
        }
        
        .nav ul {
            display: flex;
            list-style: none;
            gap: 20px;
        }
        
        .nav a {
            color: white;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        
        .nav a:hover {
            background: rgba(255,255,255,0.2);
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
            font-size: 14px;
        }
        
        .btn-primary {
            background: #4a90e2;
            color: white;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-danger {
            background: #d9534f;
            color: white;
        }
        
        .btn-success {
            background: #28a745;
            color: white;
        }
        
        .content {
            padding: 30px 0;
        }
        
        .profile-header {
            display: flex;
            align-items: center;
            gap: 30px;
            margin-bottom: 40px;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4a90e2, #5cb85c);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: white;
            font-weight: bold;
        }
        
        .profile-info h1 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .profile-info p {
            color: #7f8c8d;
            margin-bottom: 5px;
        }
        
        .user-type {
            display: inline-block;
            background: #e74c3c;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-top: 10px;
        }
        
        .user-type.idoso { background: #e74c3c; }
        .user-type.cuidador { background: #3498db; }
        .user-type.familiar { background: #9b59b6; }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            border-left: 4px solid #4a90e2;
        }
        
        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            color: #4a90e2;
            margin-bottom: 10px;
        }
        
        .stat-label {
            color: #7f8c8d;
            font-size: 14px;
        }
        
        .card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
        }
        
        .card-title {
            font-size: 20px;
            color: #2c3e50;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #34495e;
        }
        
        input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input:focus {
            border-color: #4a90e2;
            outline: none;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 25px;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
        }
        
        .danger-zone {
            border: 2px solid #e74c3c;
            background: #fdf2f2;
        }
        
        .danger-zone .card-title {
            color: #e74c3c;
        }
        
        .tab-container {
            margin-top: 30px;
        }
        
        .tabs {
            display: flex;
            border-bottom: 2px solid #eee;
            margin-bottom: 25px;
        }
        
        .tab {
            padding: 12px 25px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
            font-weight: 600;
            color: #7f8c8d;
        }
        
        .tab.active {
            color: #4a90e2;
            border-bottom-color: #4a90e2;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .tabs {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">Cuida Bem</div>
                <nav class="nav">
                    <ul>
                        <li><a href="dashboard.php">Dashboard</a></li>
                        <li><a href="medicamentos.php">Medicamentos</a></li>
                        <li><a href="pacientes.php">Pacientes</a></li>
                        <li><a href="sintomas.php">Sintomas</a></li>
                        <li><a href="perfil.php">Perfil</a></li>
                    </ul>
                </nav>
                <div class="user-info">
                    <span>Olá, <?php echo $usuario['nome']; ?></span>
                    <a href="logout.php" class="btn btn-danger">Sair</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container content">
        <!-- Cabeçalho do Perfil -->
        <div class="profile-header">
            <div class="avatar">
                <?php echo strtoupper(substr($usuario['nome'], 0, 1)); ?>
            </div>
            <div class="profile-info">
                <h1><?php echo htmlspecialchars($usuario['nome']); ?></h1>
                <p><?php echo htmlspecialchars($usuario['email']); ?></p>
                <p>Membro desde: <?php echo date('d/m/Y', strtotime($usuario['data_criacao'] ?? 'now')); ?></p>
                <span class="user-type <?php echo $usuario['tipo']; ?>">
                    <?php 
                    $tipos = [
                        'idoso' => 'Idoso',
                        'cuidador' => 'Cuidador', 
                        'familiar' => 'Familiar'
                    ];
                    echo $tipos[$usuario['tipo']] ?? 'Usuário';
                    ?>
                </span>
            </div>
        </div>

        <!-- Estatísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo $estatisticas['total_medicamentos']; ?></div>
                <div class="stat-label">Medicamentos Cadastrados</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $estatisticas['total_pacientes']; ?></div>
                <div class="stat-label">Pacientes Cadastrados</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $estatisticas['total_sintomas']; ?></div>
                <div class="stat-label">Sintomas Registrados</div>
            </div>
        </div>

        <!-- Sistema de Abas -->
        <div class="tab-container">
            <div class="tabs">
                <div class="tab active" onclick="openTab('dados-pessoais')">Dados Pessoais</div>
                <div class="tab" onclick="openTab('alterar-senha')">Alterar Senha</div>
            </div>

            <!-- Aba: Dados Pessoais -->
            <div id="dados-pessoais" class="tab-content active">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Informações Pessoais</h2>
                    </div>
                    
                    <?php if (!empty($mensagem_sucesso) && isset($_POST['atualizar_perfil'])): ?>
                        <div class="success"><?php echo $mensagem_sucesso; ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($mensagem_erro) && isset($_POST['atualizar_perfil'])): ?>
                        <div class="error"><?php echo $mensagem_erro; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="form-group">
                            <label for="nome">Nome Completo:</label>
                            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">E-mail:</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="tipo">Tipo de Usuário:</label>
                            <input type="text" id="tipo" value="<?php echo $tipos[$usuario['tipo']] ?? 'Usuário'; ?>" disabled style="background: #f8f9fa;">
                            <small style="color: #7f8c8d;">O tipo de usuário não pode ser alterado.</small>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" name="atualizar_perfil" class="btn btn-primary">Salvar Alterações</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Aba: Alterar Senha -->
            <div id="alterar-senha" class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Alterar Senha</h2>
                    </div>
                    
                    <?php if (!empty($mensagem_sucesso) && isset($_POST['alterar_senha'])): ?>
                        <div class="success"><?php echo $mensagem_sucesso; ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($mensagem_erro) && isset($_POST['alterar_senha'])): ?>
                        <div class="error"><?php echo $mensagem_erro; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="form-group">
                            <label for="senha_atual">Senha Atual:</label>
                            <input type="password" id="senha_atual" name="senha_atual" required>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="nova_senha">Nova Senha:</label>
                                <input type="password" id="nova_senha" name="nova_senha" required minlength="6">
                            </div>
                            
                            <div class="form-group">
                                <label for="confirmar_senha">Confirmar Nova Senha:</label>
                                <input type="password" id="confirmar_senha" name="confirmar_senha" required minlength="6">
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" name="alterar_senha" class="btn btn-success">Alterar Senha</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Zona de Perigo -->
        <div class="card danger-zone">
            <div class="card-header">
                <h2 class="card-title">🚨 Zona de Perigo</h2>
            </div>
            <p style="color: #e74c3c; margin-bottom: 20px;">
                <strong>Atenção:</strong> Estas ações são irreversíveis. Proceda com cuidado.
            </p>
            <div class="form-actions">
                <a href="logout.php" class="btn btn-danger">Sair da Conta</a>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; echo rodapeSimples(); ?>

    <script>
        function openTab(tabName) {
            // Esconder todas as abas
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Remover active de todas as tabs
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Mostrar a aba selecionada
            document.getElementById(tabName).classList.add('active');
            
            // Ativar a tab clicada
            event.target.classList.add('active');
        }

        // Validação de senha
        document.addEventListener('DOMContentLoaded', function() {
            const novaSenha = document.getElementById('nova_senha');
            const confirmarSenha = document.getElementById('confirmar_senha');
            
            function validarSenhas() {
                if (novaSenha.value !== confirmarSenha.value) {
                    confirmarSenha.style.borderColor = '#e74c3c';
                } else {
                    confirmarSenha.style.borderColor = '#27ae60';
                }
            }
            
            if (novaSenha && confirmarSenha) {
                novaSenha.addEventListener('input', validarSenhas);
                confirmarSenha.addEventListener('input', validarSenhas);
            }
            <script>
<?php include 'validar_cpf_simples.js'; ?>
</script>
        });
    </script>
</body>
</html>