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

// Buscar dados do sintoma
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

// Buscar pacientes para o dropdown
$pacientes_sql = "SELECT * FROM paciente ORDER BY nome";
$pacientes_res = mysqli_query($id, $pacientes_sql);

// Processar edição
if (isset($_POST['editar_sintoma'])) {
    $tipo = mysqli_real_escape_string($id, $_POST['tipo']);
    $nome = mysqli_real_escape_string($id, $_POST['nome']);
    $descricao = mysqli_real_escape_string($id, $_POST['descricao']);
    $intensidade = mysqli_real_escape_string($id, $_POST['intensidade']);
    $id_paciente = mysqli_real_escape_string($id, $_POST['id_paciente']);
    
    $update_sql = "UPDATE sintoma SET 
                  tipo = '$tipo', 
                  nome = '$nome', 
                  descricao = '$descricao', 
                  intensidade = '$intensidade', 
                  id_paciente = $id_paciente 
                  WHERE id_sintoma = $id_sintoma";
    
    if (mysqli_query($id, $update_sql)) {
        header("Location: sintomas.php?sucesso=Sintoma atualizado com sucesso!");
        exit;
    } else {
        $erro = "Erro ao atualizar sintoma: " . mysqli_error($id);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Sintoma - Cuida Bem</title>
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
        
        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input:focus, select:focus, textarea:focus {
            border-color: #9b59b6;
            outline: none;
        }
        
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
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
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
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
                <h1 class="card-title">Editar Sintoma</h1>
                <a href="sintomas.php" class="btn btn-secondary">← Voltar</a>
            </div>
            
            <?php if (isset($erro)): ?>
                <div class="error"><?php echo $erro; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="tipo">Tipo:</label>
                        <select id="tipo" name="tipo" required>
                            <option value="sintoma" <?php echo $sintoma['tipo'] == 'sintoma' ? 'selected' : ''; ?>>Sintoma</option>
                            <option value="condicao" <?php echo $sintoma['tipo'] == 'condicao' ? 'selected' : ''; ?>>Condição</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="intensidade">Intensidade:</label>
                        <select id="intensidade" name="intensidade">
                            <option value="leve" <?php echo $sintoma['intensidade'] == 'leve' ? 'selected' : ''; ?>>Leve</option>
                            <option value="moderada" <?php echo $sintoma['intensidade'] == 'moderada' ? 'selected' : ''; ?>>Moderada</option>
                            <option value="grave" <?php echo $sintoma['intensidade'] == 'grave' ? 'selected' : ''; ?>>Grave</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($sintoma['nome']); ?>" required placeholder="Ex: Febre, Dor de cabeça, Tosse...">
                </div>
                
                <div class="form-group">
                    <label for="descricao">Descrição:</label>
                    <textarea id="descricao" name="descricao" placeholder="Descreva os sintomas em detalhes..."><?php echo htmlspecialchars($sintoma['descricao']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="id_paciente">Paciente:</label>
                    <select id="id_paciente" name="id_paciente" required>
                        <option value="">Selecione um paciente</option>
                        <?php 
                        mysqli_data_seek($pacientes_res, 0); // Reset do ponteiro do resultado
                        while ($paciente = mysqli_fetch_assoc($pacientes_res)): 
                        ?>
                            <option value="<?php echo $paciente['id_paciente']; ?>" 
                                <?php echo $paciente['id_paciente'] == $sintoma['id_paciente'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($paciente['nome']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="submit" name="editar_sintoma" class="btn btn-primary">Salvar Alterações</button>
                    <a href="sintomas.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; echo rodapeSimples(); ?>
</body>
</html>