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

// Verificar se existe
$sql = "SELECT * FROM medicamento WHERE id_medicamento = $id_medicamento";
$res = mysqli_query($id, $sql);
$medicamento = mysqli_fetch_assoc($res);

if (!$medicamento) {
    header("Location: medicamentos.php");
    exit;
}

// Processar exclusão
if (isset($_POST['confirmar_exclusao'])) {
    $delete_sql = "DELETE FROM medicamento WHERE id_medicamento = $id_medicamento";
    
    if (mysqli_query($id, $delete_sql)) {
        header("Location: medicamentos.php?sucesso=Medicamento excluído com sucesso!");
        exit;
    } else {
        $erro = "Erro ao excluir medicamento: " . mysqli_error($id);
    }
}

if (isset($_POST['cancelar'])) {
    header("Location: medicamentos.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Medicamento - Cuida Bem</title>
    <style>
        <?php include 'estilo_copyright.css'; ?>
        /* Estilos similares ao editar_medicamento.php */
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
                <h1 class="card-title">Excluir Medicamento</h1>
                <a href="medicamentos.php" class="btn btn-secondary">← Voltar</a>
            </div>
            
            <?php if (isset($erro)): ?>
                <div class="error"><?php echo $erro; ?></div>
            <?php endif; ?>
            
            <div style="text-align: center; padding: 30px 0;">
                <div style="font-size: 48px; color: #e74c3c; margin-bottom: 20px;">⚠️</div>
                <h2 style="color: #2c3e50; margin-bottom: 15px;">Confirmação de Exclusão</h2>
                <p style="font-size: 18px; color: #7f8c8d; margin-bottom: 10px;">
                    Tem certeza que deseja excluir o medicamento:
                </p>
                <p style="font-size: 22px; color: #e74c3c; font-weight: bold; margin-bottom: 30px;">
                    "<?php echo htmlspecialchars($medicamento['nome']); ?>"
                </p>
                <p style="color: #95a5a6; font-style: italic;">
                    Esta ação não pode ser desfeita.
                </p>
            </div>
            
            <form method="POST">
                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" name="confirmar_exclusao" class="btn btn-danger">Sim, Excluir</button>
                    <button type="submit" name="cancelar" class="btn btn-secondary">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; echo rodapeSimples(); ?>
</body>
</html>