<?php 
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

include('conexao.php');

// Processar formulário de paciente
if (isset($_POST['adicionar_paciente'])) {
    $nome = mysqli_real_escape_string($id, $_POST['nome']);
    $data_nascimento = mysqli_real_escape_string($id, $_POST['data_nascimento']);
    $cpf = mysqli_real_escape_string($id, $_POST['cpf']);
    
    // Limpar e validar CPF no servidor
    $cpf_limpo = preg_replace('/[^\d]/', '', $cpf);
    
    if (!empty($cpf_limpo) && strlen($cpf_limpo) !== 11) {
        $mensagem_erro = "CPF deve conter 11 dígitos!";
    } else {
        $sql = "INSERT INTO paciente (nome, data_nascimento, cpf) 
                VALUES ('$nome', '$data_nascimento', '$cpf_limpo')";
        
        if (mysqli_query($id, $sql)) {
            $mensagem_sucesso = "Paciente adicionado com sucesso!";
        } else {
            $mensagem_erro = "Erro ao adicionar paciente: " . mysqli_error($id);
        }
    }
}

// Buscar pacientes
$pacientes_sql = "SELECT * FROM paciente ORDER BY nome";
$pacientes_res = mysqli_query($id, $pacientes_sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pacientes - Cuida Bem</title>
    <style>
        <?php include 'estilo_copyright.css'; ?>
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
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
            background: #27ae60;
            color: white;
        }
        
        .btn-danger {
            background: #d9534f;
            color: white;
        }
        
        .btn-warning {
            background: #f39c12;
            color: white;
        }
        
        .content {
            padding: 30px 0;
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
            font-size: 24px;
            color: #2c3e50;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
            border-bottom: 2px solid #4a90e2;
        }
        
        table tr:hover {
            background: #f8f9fa;
        }
        
        .form-group {
            margin-bottom: 20px;
            position: relative;
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
            transition: all 0.3s;
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
        
        .actions {
            display: flex;
            gap: 8px;
        }
        
        .btn-small {
            padding: 6px 12px;
            font-size: 12px;
        }
        
        .cpf-formatado {
            font-family: 'Courier New', monospace;
            letter-spacing: 1px;
        }
        
        /* Estilos do validador de CPF */
        .cpf-valido {
            border-color: #27ae60 !important;
            background-color: #f8fff9 !important;
        }
        
        .cpf-invalido {
            border-color: #e74c3c !important;
            background-color: #fff5f5 !important;
        }
        
        .cpf-erro {
            color: #e74c3c;
            font-size: 12px;
            margin-top: 5px;
            font-weight: 500;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .actions {
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
                    <span>Olá, <?php echo $_SESSION['usuario']['nome']; ?></span>
                    <a href="logout.php" class="btn btn-danger">Sair</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container content">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Pacientes</h2>
                <button class="btn btn-primary" onclick="document.getElementById('form-paciente').style.display='block'">
                    + Adicionar Paciente
                </button>
            </div>
            
            <?php if (isset($mensagem_sucesso)): ?>
                <div class="success"><?php echo $mensagem_sucesso; ?></div>
            <?php endif; ?>
            
            <?php if (isset($mensagem_erro)): ?>
                <div class="error"><?php echo $mensagem_erro; ?></div>
            <?php endif; ?>
            
            <div id="form-paciente" style="display: none; margin-bottom: 30px;">
                <form method="POST" id="form-cadastro-paciente">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nome">Nome Completo:</label>
                            <input type="text" id="nome" name="nome" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="cpf">CPF:</label>
                            <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" maxlength="14">
                            <div style="color: #7f8c8d; font-size: 11px; margin-top: 4px;">
                                Opcional - Será formatado automaticamente
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="data_nascimento">Data de Nascimento:</label>
                        <input type="date" id="data_nascimento" name="data_nascimento">
                    </div>
                    
                    <button type="submit" name="adicionar_paciente" class="btn btn-primary">Salvar Paciente</button>
                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('form-paciente').style.display='none'">Cancelar</button>
                </form>
            </div>
            
            <?php if ($pacientes_res && mysqli_num_rows($pacientes_res) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Data Nascimento</th>
                        <th>CPF</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($paciente = mysqli_fetch_assoc($pacientes_res)): 
                        $cpf_formatado = $paciente['cpf'] ? 
                            substr($paciente['cpf'], 0, 3) . '.' . 
                            substr($paciente['cpf'], 3, 3) . '.' . 
                            substr($paciente['cpf'], 6, 3) . '-' . 
                            substr($paciente['cpf'], 9, 2) : 
                            'Não informado';
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($paciente['nome']); ?></td>
                        <td><?php echo $paciente['data_nascimento'] ? date('d/m/Y', strtotime($paciente['data_nascimento'])) : 'Não informada'; ?></td>
                        <td class="cpf-formatado"><?php echo $cpf_formatado; ?></td>
                        <td class="actions">
                            <a href="editar_paciente.php?id=<?php echo $paciente['id_paciente']; ?>" class="btn btn-warning btn-small">Editar</a>
                            <a href="deletar_paciente.php?id=<?php echo $paciente['id_paciente']; ?>" class="btn btn-danger btn-small" onclick="return confirm('Tem certeza?')">Excluir</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div style="text-align: center; padding: 40px; color: #7f8c8d;">
                <p style="font-size: 18px;">Nenhum paciente cadastrado.</p>
                <p>Clique em "Adicionar Paciente" para começar.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'footer.php'; echo rodapeSimples(); ?>

    <!-- Validador de CPF Integrado -->
    <script>
        function validarCPF(cpf) {
            cpf = cpf.replace(/[^\d]/g, '');
            if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) return false;
            
            let soma = 0;
            for (let i = 0; i < 9; i++) {
                soma += parseInt(cpf.charAt(i)) * (10 - i);
            }
            let resto = soma % 11;
            let digito1 = resto < 2 ? 0 : 11 - resto;
            if (digito1 !== parseInt(cpf.charAt(9))) return false;
            
            soma = 0;
            for (let i = 0; i < 10; i++) {
                soma += parseInt(cpf.charAt(i)) * (11 - i);
            }
            resto = soma % 11;
            let digito2 = resto < 2 ? 0 : 11 - resto;
            return digito2 === parseInt(cpf.charAt(10));
        }

        function formatarCPF(cpf) {
            cpf = cpf.replace(/[^\d]/g, '');
            if (cpf.length <= 3) return cpf;
            if (cpf.length <= 6) return cpf.replace(/(\d{3})(\d{0,3})/, '$1.$2');
            if (cpf.length <= 9) return cpf.replace(/(\d{3})(\d{3})(\d{0,3})/, '$1.$2.$3');
            return cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{0,2})/, '$1.$2.$3-$4');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const inputCPF = document.getElementById('cpf');
            
            if (inputCPF) {
                inputCPF.addEventListener('input', function() {
                    const cursor = this.selectionStart;
                    this.value = formatarCPF(this.value);
                    this.setSelectionRange(cursor, cursor);
                    
                    const cpfLimpo = this.value.replace(/[^\d]/g, '');
                    this.classList.remove('cpf-valido', 'cpf-invalido');
                    
                    if (cpfLimpo.length === 11) {
                        if (validarCPF(this.value)) {
                            this.classList.add('cpf-valido');
                        } else {
                            this.classList.add('cpf-invalido');
                        }
                    }
                });

                // Formatar valor inicial se existir
                if (inputCPF.value) {
                    inputCPF.value = formatarCPF(inputCPF.value);
                }
            }

            // Validar CPF no envio do formulário
            const form = document.getElementById('form-cadastro-paciente');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const inputCPF = document.getElementById('cpf');
                    const cpf = inputCPF.value.replace(/[^\d]/g, '');
                    
                    if (cpf.length > 0 && cpf.length !== 11) {
                        e.preventDefault();
                        alert('CPF deve conter 11 dígitos!');
                        inputCPF.focus();
                        return false;
                    }
                    
                    if (cpf.length === 11 && !validarCPF(inputCPF.value)) {
                        e.preventDefault();
                        alert('CPF inválido! Verifique os números.');
                        inputCPF.focus();
                        return false;
                    }
                });
            }
        });
    </script>
</body>
</html>