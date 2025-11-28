<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

include('conexao.php');

$id_medicamento = $_GET['id'] ?? null;

if (!$id_medicamento) {
    header("Location: medicamentos.php");
    exit;
}

// Buscar dados do medicamento
$sql = "SELECT * FROM medicamento WHERE id_medicamento = $id_medicamento";
$res = mysqli_query($id, $sql);
$medicamento = mysqli_fetch_assoc($res);

if (!$medicamento) {
    header("Location: medicamentos.php");
    exit;
}

// Processar edição
if (isset($_POST['editar_medicamento'])) {
    $nome = mysqli_real_escape_string($id, $_POST['nome']);
    $dosagem = mysqli_real_escape_string($id, $_POST['dosagem']);
    $observacao = mysqli_real_escape_string($id, $_POST['observacao']);
    
    $update_sql = "UPDATE medicamento SET 
                  nome = '$nome', 
                  dosagem = '$dosagem', 
                  observacao = '$observacao' 
                  WHERE id_medicamento = $id_medicamento";
    
    if (mysqli_query($id, $update_sql)) {
        header("Location: medicamentos.php?sucesso=Medicamento atualizado com sucesso!");
        exit;
    } else {
        $erro = "Erro ao atualizar medicamento: " . mysqli_error($id);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Medicamento - Cuida Bem</title>
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
            max-width: 800px;
            margin: 0 auto;
            padding: 30px 0;
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
        
        .card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
        }
        
        .card-title {
            font-size: 24px;
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
        
        input, textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input:focus, textarea:focus {
            border-color: #4a90e2;
            outline: none;
        }
        
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
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
                    </ul>
                </nav>
                <div class="user-info">
                    <span>Olá, <?php echo $_SESSION['usuario']['nome']; ?></span>
                    <a href="logout.php" class="btn btn-danger">Sair</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">Editar Medicamento</h1>
                <a href="medicamentos.php" class="btn btn-secondary">← Voltar</a>
            </div>
            
            <?php if (isset($erro)): ?>
                <div class="error"><?php echo $erro; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="nome">Nome do Medicamento:</label>
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($medicamento['nome']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="dosagem">Dosagem:</label>
                    <input type="text" id="dosagem" name="dosagem" value="<?php echo htmlspecialchars($medicamento['dosagem']); ?>" placeholder="Ex: 1 comprimido, 500mg">
                </div>
                
                <div class="form-group">
                    <label for="observacao">Observações:</label>
                    <textarea id="observacao" name="observacao" placeholder="Instruções de uso, efeitos, etc."><?php echo htmlspecialchars($medicamento['observacao']); ?></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" name="editar_medicamento" class="btn btn-primary">Salvar Alterações</button>
                    <a href="medicamentos.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; echo rodapeSimples(); ?>
</body>
</html>