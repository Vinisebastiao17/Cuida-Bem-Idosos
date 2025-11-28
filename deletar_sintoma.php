<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

include('conexao.php');

$id_sintoma = $_GET['id'] ?? null;

if (!$id_sintoma) {
    header("Location: sintomas.php");
    exit;
}

// Verificar se existe
$sql = "SELECT s.*, p.nome as paciente_nome 
        FROM sintoma s 
        JOIN paciente p ON s.id_paciente = p.id_paciente 
        WHERE s.id_sintoma = $id_sintoma";
$res = mysqli_query($id, $sql);
$sintoma = mysqli_fetch_assoc($res);

if (!$sintoma) {
    header("Location: sintomas.php");
    exit;
}

// Processar exclusão
if (isset($_POST['confirmar_exclusao'])) {
    $delete_sql = "DELETE FROM sintoma WHERE id_sintoma = $id_sintoma";
    
    if (mysqli_query($id, $delete_sql)) {
        header("Location: sintomas.php?sucesso=Sintoma excluído com sucesso!");
        exit;
    } else {
        $erro = "Erro ao excluir sintoma: " . mysqli_error($id);
    }
}

if (isset($_POST['cancelar'])) {
    header("Location: sintomas.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Sintoma - Cuida Bem</title>
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
        
        .warning-box {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        
        .warning-icon {
            font-size: 48px;
            color: #e74c3c;
            margin-bottom: 15px;
        }
        
        .sintoma-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            justify-content: center;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
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
                <h1 class="card-title">Excluir Sintoma</h1>
                <a href="sintomas.php" class="btn btn-secondary">← Voltar</a>
            </div>
            
            <?php if (isset($erro)): ?>
                <div class="error"><?php echo $erro; ?></div>
            <?php endif; ?>
            
            <div class="warning-box">
                <div class="warning-icon">⚠️</div>
                <h2 style="color: #e74c3c; margin-bottom: 15px;">Confirmação de Exclusão</h2>
                <p style="font-size: 18px; color: #2c3e50; margin-bottom: 10px;">
                    Tem certeza que deseja excluir o seguinte registro:
                </p>
                
                <div class="sintoma-info">
                    <div class="info-item">
                        <span><strong>Tipo:</strong></span>
                        <span><?php echo $sintoma['tipo'] == 'condicao' ? 'Condição' : 'Sintoma'; ?></span>
                    </div>
                    <div class="info-item">
                        <span><strong>Nome:</strong></span>
                        <span><?php echo htmlspecialchars($sintoma['nome']); ?></span>
                    </div>
                    <div class="info-item">
                        <span><strong>Paciente:</strong></span>
                        <span><?php echo htmlspecialchars($sintoma['paciente_nome']); ?></span>
                    </div>
                    <div class="info-item">
                        <span><strong>Intensidade:</strong></span>
                        <span><?php echo ucfirst($sintoma['intensidade']); ?></span>
                    </div>
                    <div class="info-item">
                        <span><strong>Data do registro:</strong></span>
                        <span><?php echo date('d/m/Y H:i', strtotime($sintoma['data_registro'])); ?></span>
                    </div>
                </div>
                
                <p style="color: #95a5a6; font-style: italic; margin-top: 15px;">
                    Esta ação não pode ser desfeita.
                </p>
            </div>
            
            <form method="POST">
                <div class="form-actions">
                    <button type="submit" name="confirmar_exclusao" class="btn btn-danger">Sim, Excluir</button>
                    <button type="submit" name="cancelar" class="btn btn-secondary">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; echo rodapeSimples(); ?>
</body>
</html>