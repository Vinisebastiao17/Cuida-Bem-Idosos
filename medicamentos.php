<?php 
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

include('conexao.php');

// Processar formulário de medicamento
if (isset($_POST['adicionar_medicamento'])) {
    $nome = mysqli_real_escape_string($id, $_POST['nome']);
    $dosagem = mysqli_real_escape_string($id, $_POST['dosagem']);
    $observacao = mysqli_real_escape_string($id, $_POST['observacao']);
    
    $sql = "INSERT INTO medicamento (nome, dosagem, observacao) 
            VALUES ('$nome', '$dosagem', '$observacao')";
    
    if (mysqli_query($id, $sql)) {
        $mensagem = "Medicamento adicionado com sucesso!";
    } else {
        $erro = "Erro ao adicionar medicamento: " . mysqli_error($id);
    }
}

// Buscar medicamentos
$medicamentos_sql = "SELECT * FROM medicamento ORDER BY nome";
$medicamentos_res = mysqli_query($id, $medicamentos_sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuida Bem - Medicamentos</title>
    <style>
        /* Estilos similares ao dashboard... */
        :root {
            --primary-color: #4a90e2;
            --secondary-color: #5cb85c;
            --danger-color: #d9534f;
            --warning-color: #f0ad4e;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }
        
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
            background: var(--primary-color);
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
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: var(--primary-color);
            color: white;
        }
        
        .btn-danger {
            background: var(--danger-color);
            color: white;
        }
        
        .content {
            padding: 30px 0;
        }
        
        .card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        table th, table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        table th {
            background: #f8f9fa;
            font-weight: 600;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        input, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
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
                <h2>Medicamentos</h2>
                <button class="btn btn-primary" onclick="document.getElementById('form-medicamento').style.display='block'">
                    Adicionar Medicamento
                </button>
            </div>
            
            <?php if (isset($mensagem)): ?>
                <div class="success"><?php echo $mensagem; ?></div>
            <?php endif; ?>
            
            <?php if (isset($erro)): ?>
                <div class="error"><?php echo $erro; ?></div>
            <?php endif; ?>
            
            <div id="form-medicamento" style="display: none; margin-bottom: 20px;">
                <form method="POST">
                    <div class="form-group">
                        <label for="nome">Nome do Medicamento:</label>
                        <input type="text" id="nome" name="nome" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="dosagem">Dosagem:</label>
                        <input type="text" id="dosagem" name="dosagem" placeholder="Ex: 1 comprimido">
                    </div>
                    
                    <div class="form-group">
                        <label for="observacao">Observações:</label>
                        <textarea id="observacao" name="observacao"></textarea>
                    </div>
                    
                    <button type="submit" name="adicionar_medicamento" class="btn btn-primary">Salvar</button>
                    <button type="button" class="btn" onclick="document.getElementById('form-medicamento').style.display='none'">Cancelar</button>
                </form>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Dosagem</th>
                        <th>Observações</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($medicamento = mysqli_fetch_assoc($medicamentos_res)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($medicamento['nome']); ?></td>
                        <td><?php echo htmlspecialchars($medicamento['dosagem']); ?></td>
                        <td><?php echo htmlspecialchars($medicamento['observacao']); ?></td>
                        
                        <td class="actions">
                     <a href="editar_medicamento.php?id=<?php echo $medicamento['id_medicamento']; ?>" class="btn btn-warning btn-small">Editar</a>
                     <a href="deletar_medicamento.php?id=<?php echo $medicamento['id_medicamento']; ?>" class="btn btn-danger btn-small" onclick="return confirm('Tem certeza?')">Excluir</a>
                    </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>