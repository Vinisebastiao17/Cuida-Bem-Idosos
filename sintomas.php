<?php 
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

include('conexao.php');

// Processar formulário de sintoma
if (isset($_POST['adicionar_sintoma'])) {
    $tipo = mysqli_real_escape_string($id, $_POST['tipo']);
    $nome = mysqli_real_escape_string($id, $_POST['nome']);
    $descricao = mysqli_real_escape_string($id, $_POST['descricao']);
    $intensidade = mysqli_real_escape_string($id, $_POST['intensidade']);
    $id_paciente = mysqli_real_escape_string($id, $_POST['id_paciente']);
    
    $sql = "INSERT INTO sintoma (tipo, nome, descricao, intensidade, id_paciente) 
            VALUES ('$tipo', '$nome', '$descricao', '$intensidade', $id_paciente)";
    
    if (mysqli_query($id, $sql)) {
        $mensagem_sucesso = "Sintoma registrado com sucesso!";
    } else {
        $mensagem_erro = "Erro ao registrar sintoma: " . mysqli_error($id);
    }
}

// Buscar sintomas com informações do paciente
$sintomas_sql = "SELECT s.*, p.nome as paciente_nome 
                 FROM sintoma s 
                 JOIN paciente p ON s.id_paciente = p.id_paciente 
                 ORDER BY s.data_registro DESC";
$sintomas_res = mysqli_query($id, $sintomas_sql);

// Buscar pacientes para o dropdown
$pacientes_sql = "SELECT * FROM paciente ORDER BY nome";
$pacientes_res = mysqli_query($id, $pacientes_sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sintomas - Cuida Bem</title>
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
            background: #9b59b6;
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
            border-bottom: 2px solid #9b59b6;
        }
        
        table tr:hover {
            background: #f8f9fa;
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
            min-height: 80px;
            resize: vertical;
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
        
        .badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .badge-condicao {
            background: #3498db;
            color: white;
        }
        
        .badge-sintoma {
            background: #e74c3c;
            color: white;
        }
        
        .badge-leve {
            background: #27ae60;
            color: white;
        }
        
        .badge-moderada {
            background: #f39c12;
            color: white;
        }
        
        .badge-grave {
            background: #c0392b;
            color: white;
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
                <h2 class="card-title">Registro de Sintomas</h2>
                <button class="btn btn-secondary" onclick="document.getElementById('form-sintoma').style.display='block'">
                    + Registrar Sintoma
                </button>
            </div>
            
            <?php if (isset($mensagem_sucesso)): ?>
                <div class="success"><?php echo $mensagem_sucesso; ?></div>
            <?php endif; ?>
            
            <?php if (isset($mensagem_erro)): ?>
                <div class="error"><?php echo $mensagem_erro; ?></div>
            <?php endif; ?>
            
            <div id="form-sintoma" style="display: none; margin-bottom: 30px;">
                <form method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="tipo">Tipo:</label>
                            <select id="tipo" name="tipo" required>
                                <option value="sintoma">Sintoma</option>
                                <option value="condicao">Condição</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="intensidade">Intensidade:</label>
                            <select id="intensidade" name="intensidade">
                                <option value="leve">Leve</option>
                                <option value="moderada">Moderada</option>
                                <option value="grave">Grave</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="nome">Nome:</label>
                        <input type="text" id="nome" name="nome" required placeholder="Ex: Febre, Dor de cabeça, Tosse...">
                    </div>
                    
                    <div class="form-group">
                        <label for="descricao">Descrição:</label>
                        <textarea id="descricao" name="descricao" placeholder="Descreva os sintomas em detalhes..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="id_paciente">Paciente:</label>
                        <select id="id_paciente" name="id_paciente" required>
                            <option value="">Selecione um paciente</option>
                            <?php while ($paciente = mysqli_fetch_assoc($pacientes_res)): ?>
                                <option value="<?php echo $paciente['id_paciente']; ?>">
                                    <?php echo htmlspecialchars($paciente['nome']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <button type="submit" name="adicionar_sintoma" class="btn btn-secondary">Registrar Sintoma</button>
                    <button type="button" class="btn btn-primary" onclick="document.getElementById('form-sintoma').style.display='none'">Cancelar</button>
                </form>
            </div>
            
            <?php if ($sintomas_res && mysqli_num_rows($sintomas_res) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Intensidade</th>
                        <th>Paciente</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($sintoma = mysqli_fetch_assoc($sintomas_res)): ?>
                    <tr>
                        <td>
                            <span class="badge <?php echo $sintoma['tipo'] == 'condicao' ? 'badge-condicao' : 'badge-sintoma'; ?>">
                                <?php echo $sintoma['tipo'] == 'condicao' ? 'Condição' : 'Sintoma'; ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($sintoma['nome']); ?></td>
                        <td><?php echo htmlspecialchars($sintoma['descricao'] ?: '-'); ?></td>
                        <td>
                            <?php if ($sintoma['intensidade']): ?>
                            <span class="badge badge-<?php echo $sintoma['intensidade']; ?>">
                                <?php echo ucfirst($sintoma['intensidade']); ?>
                            </span>
                            <?php else: ?>
                            -
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($sintoma['paciente_nome']); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($sintoma['data_registro'])); ?></td>
                        <td class="actions">
                            <a href="editar_sintoma.php?id=<?php echo $sintoma['id_sintoma']; ?>" class="btn btn-warning btn-small">Editar</a>
                            <a href="deletar_sintoma.php?id=<?php echo $sintoma['id_sintoma']; ?>" class="btn btn-danger btn-small" onclick="return confirm('Tem certeza?')">Excluir</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div style="text-align: center; padding: 40px; color: #7f8c8d;">
                <p style="font-size: 18px;">Nenhum sintoma registrado.</p>
                <p>Clique em "Registrar Sintoma" para começar.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'footer.php'; echo rodapeSimples(); ?>
</body>
</html>