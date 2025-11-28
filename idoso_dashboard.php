<?php 
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] != 'idoso') {
    header("Location: login.php");
    exit;
}

include('conexao.php');

// Marcar medicamento como tomado
if (isset($_POST['marcar_tomado'])) {
    $id_medicamento = $_POST['id_medicamento'];
    $sql = "UPDATE medicamentos_tratamentos SET status = 'concluído' WHERE id_medicamento = $id_medicamento";
    mysqli_query($id, $sql);
}

// Registrar sintoma
if (isset($_POST['registrar_sintoma'])) {
    $tipo = 'sintoma';
    $nome = mysqli_real_escape_string($id, $_POST['nome_sintoma']);
    $intensidade = mysqli_real_escape_string($id, $_POST['intensidade']);
    $id_paciente = $_SESSION['usuario']['id_paciente']; // Associar ao paciente
    
    $sql = "INSERT INTO sintoma (tipo, nome, intensidade, id_paciente) 
            VALUES ('$tipo', '$nome', '$intensidade', $id_paciente)";
    mysqli_query($id, $sql);
}

// Buscar medicamentos do dia
$hoje = date('Y-m-d');
$medicamentos_sql = "SELECT m.nome, m.dosagem, h.horario, mt.status, mt.id_medicamento
                    FROM medicamentos_tratamentos mt
                    JOIN medicamento m ON mt.id_medicamento = m.id_medicamento
                    JOIN horarios h ON mt.id_horario = h.id_horario
                    WHERE mt.id_paciente = {$_SESSION['usuario']['id_paciente']}
                    ORDER BY h.horario";
$medicamentos_res = mysqli_query($id, $medicamentos_sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuida Bem - Idoso</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        
        body {
            background: #f0f8ff;
            color: #333;
            min-height: 100vh;
        }
        
        .header {
            background: #4a90e2;
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .logo {
            font-size: 2em;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .user-info {
            font-size: 1.1em;
            opacity: 0.9;
        }
        
        .container {
            padding: 20px;
            max-width: 500px;
            margin: 0 auto;
        }
        
        .card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .card-title {
            color: #4a90e2;
            margin-bottom: 15px;
            font-size: 1.3em;
            border-bottom: 2px solid #4a90e2;
            padding-bottom: 5px;
        }
        
        .medication-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        
        .medication-time {
            font-weight: bold;
            font-size: 1.2em;
            color: #2c3e50;
            min-width: 70px;
        }
        
        .medication-info {
            flex-grow: 1;
            margin-left: 15px;
        }
        
        .medication-name {
            font-weight: bold;
            color: #34495e;
        }
        
        .medication-dosage {
            color: #7f8c8d;
            font-size: 0.9em;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s;
        }
        
        .btn-taken {
            background: #27ae60;
            color: white;
        }
        
        .btn-taken:disabled {
            background: #bdc3c7;
            cursor: not-allowed;
        }
        
        .btn-pending {
            background: #f39c12;
            color: white;
        }
        
        .btn-emergency {
            background: #e74c3c;
            color: white;
            padding: 20px;
            font-size: 1.3em;
            width: 100%;
            margin-top: 10px;
        }
        
        .symptom-form {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        .symptom-input {
            flex-grow: 1;
            padding: 10px;
            border: 2px solid #bdc3c7;
            border-radius: 8px;
            font-size: 1em;
        }
        
        .btn-symptom {
            background: #9b59b6;
            color: white;
        }
        
        .alertas {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 15px 0;
        }
        
        .nav-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 20px;
        }
        
        .nav-btn {
            background: #3498db;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
        }
        
        .taken {
            opacity: 0.6;
            text-decoration: line-through;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">Cuida Bem</div>
        <div class="user-info">Olá, <?php echo $_SESSION['usuario']['nome']; ?></div>
    </div>

    <div class="container">
        <!-- Card de Medicamentos do Dia -->
        <div class="card">
            <div class="card-title">Medicamentos de Hoje</div>
            
            <?php while ($med = mysqli_fetch_assoc($medicamentos_res)): ?>
            <div class="medication-item <?php echo $med['status'] == 'concluído' ? 'taken' : ''; ?>">
                <div class="medication-time">
                    <?php echo date('H:i', strtotime($med['horario'])); ?>
                </div>
                <div class="medication-info">
                    <div class="medication-name"><?php echo $med['nome']; ?></div>
                    <div class="medication-dosage"><?php echo $med['dosagem']; ?></div>
                </div>
                <?php if ($med['status'] == 'pendente'): ?>
                <form method="POST" style="margin: 0;">
                    <input type="hidden" name="id_medicamento" value="<?php echo $med['id_medicamento']; ?>">
                    <button type="submit" name="marcar_tomado" class="btn btn-taken">✓ Tomado</button>
                </form>
                <?php else: ?>
                <button class="btn btn-pending" disabled>✓ Tomado</button>
                <?php endif; ?>
            </div>
            <?php endwhile; ?>
        </div>

        <!-- Card de Sintomas -->
        <div class="card">
            <div class="card-title">Registrar Sintoma</div>
            <form method="POST" class="symptom-form">
                <input type="text" name="nome_sintoma" class="symptom-input" placeholder="Ex: Febre, Dor de cabeça..." required>
                <select name="intensidade" style="padding: 10px; border: 2px solid #bdc3c7; border-radius: 8px;">
                    <option value="leve">Leve</option>
                    <option value="moderada">Moderada</option>
                    <option value="grave">Grave</option>
                </select>
                <button type="submit" name="registrar_sintoma" class="btn btn-symptom">+</button>
            </form>
        </div>

        <!-- Alertas Importantes -->
        <div class="alertas">
            <strong>Alertas importantes</strong><br>
            • Próximo medicamento: 16:00<br>
            • Consulta com cardiologista amanhã
        </div>

        <!-- Navegação -->
        <div class="nav-buttons">
            <a href="idoso_perfil.php" class="nav-btn">Meu Perfil</a>
            <a href="idoso_historico.php" class="nav-btn">Histórico</a>
        </div>

        <!-- Botão de Emergência -->
        <button class="btn btn-emergency" onclick="window.location.href='emergencia.php'">
            🚨 EMERGÊNCIA
        </button>
    </div>

    <script>
        // Atualizar automaticamente a cada minuto
        setInterval(() => {
            location.reload();
        }, 60000);

        // Confirmar antes de marcar como tomado
        document.addEventListener('submit', function(e) {
            if (e.target.name === 'marcar_tomado') {
                if (!confirm('Confirmar que tomou este medicamento?')) {
                    e.preventDefault();
                }
            }
        });
    </script>
</body>
</html>