<?php 
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

include('conexao.php');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuida Bem - Dashboard</title>
    <style>
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
        
        .btn-danger {
            background: var(--danger-color);
            color: white;
        }
        
        .dashboard {
            padding: 30px 0;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .card h3 {
            color: var(--dark-color);
            margin-bottom: 10px;
        }
        
        .number {
            font-size: 2em;
            font-weight: bold;
            color: var(--primary-color);
        }
        
        .schedule {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .schedule-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .schedule-time {
            font-weight: bold;
            font-size: 1.2em;
        }
        
        .schedule-medication {
            flex-grow: 1;
            margin-left: 20px;
        }
        
        .schedule-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.9em;
        }
        
        .status-pending {
            background: var(--warning-color);
            color: white;
        }
        
        .status-completed {
            background: var(--secondary-color);
            color: white;
        }
        
        .emergency-btn {
            background: var(--danger-color);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            font-weight: bold;
            font-size: 1.2em;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 20px;
        }
        
        .emergency-btn:hover {
            background: #c9302c;
            transform: scale(1.02);
        }

        .no-data {
            text-align: center;
            color: #666;
            padding: 40px;
            font-style: italic;
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

    <div class="container dashboard">
        <h1>Dashboard</h1>
        
        <div class="dashboard-grid">
            <?php
            // Contar pacientes
            $pacientes_sql = "SELECT COUNT(*) as total FROM paciente";
            $pacientes_res = mysqli_query($id, $pacientes_sql);
            $total_pacientes = $pacientes_res ? mysqli_fetch_assoc($pacientes_res)['total'] : 0;
            
            // Contar medicamentos
            $medicamentos_sql = "SELECT COUNT(*) as total FROM medicamento";
            $medicamentos_res = mysqli_query($id, $medicamentos_sql);
            $total_medicamentos = $medicamentos_res ? mysqli_fetch_assoc($medicamentos_res)['total'] : 0;
            
            // Contar tratamentos
            $tratamentos_sql = "SELECT COUNT(*) as total FROM tratamento";
            $tratamentos_res = mysqli_query($id, $tratamentos_sql);
            $total_tratamentos = $tratamentos_res ? mysqli_fetch_assoc($tratamentos_res)['total'] : 0;
            
            // Contar sintomas
            $sintomas_sql = "SELECT COUNT(*) as total FROM sintoma";
            $sintomas_res = mysqli_query($id, $sintomas_sql);
            $total_sintomas = $sintomas_res ? mysqli_fetch_assoc($sintomas_res)['total'] : 0;
            ?>
            
            <div class="card">
                <h3>Pacientes</h3>
                <div class="number"><?php echo $total_pacientes; ?></div>
            </div>
            <div class="card">
                <h3>Medicamentos</h3>
                <div class="number"><?php echo $total_medicamentos; ?></div>
            </div>
            <div class="card">
                <h3>Tratamentos</h3>
                <div class="number"><?php echo $total_tratamentos; ?></div>
            </div>
            <div class="card">
                <h3>Sintomas</h3>
                <div class="number"><?php echo $total_sintomas; ?></div>
            </div>
        </div>
        
        <div class="schedule">
            <h2>Próximos Medicamentos</h2>
            <?php
            // Consulta corrigida - dosagem vem da tabela medicamento
            $horarios_sql = "SELECT m.nome, m.dosagem, h.horario, mt.status, mt.id_medicamento
                            FROM medicamentos_tratamentos mt
                            JOIN medicamento m ON mt.id_medicamento = m.id_medicamento
                            JOIN horarios h ON mt.id_horario = h.id_horario
                            WHERE mt.status = 'pendente'
                            ORDER BY h.horario";
            $horarios_res = mysqli_query($id, $horarios_sql);
            
            if ($horarios_res && mysqli_num_rows($horarios_res) > 0) {
                while ($horario = mysqli_fetch_assoc($horarios_res)) {
                    echo "<div class='schedule-item'>";
                    echo "<div class='schedule-time'>" . date('H:i', strtotime($horario['horario'])) . "</div>";
                    echo "<div class='schedule-medication'>{$horario['nome']} | {$horario['dosagem']}</div>";
                    echo "<div class='schedule-status status-pending'>Pendente</div>";
                    echo "</div>";
                }
            } else {
                echo "<div class='no-data'>Nenhum medicamento agendado para hoje.</div>";
            }
            ?>
        </div>
        
        <div class="emergency-btn" onclick="window.location.href='emergencia.php'">
            🚨 EMERGÊNCIA
        </div>
    </div>

    <!-- Rodapé Simples -->
    <div style='
        background: #2c3e50;
        color: white;
        text-align: center;
        padding: 20px;
        margin-top: 40px;
        font-size: 14px;
    '>
        &copy; <?php echo date('Y'); ?> Cuida Bem - Todos os direitos reservados
    </div>
</body>
</html>