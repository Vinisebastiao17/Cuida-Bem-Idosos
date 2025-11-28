<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

include('conexao.php');

$id_paciente = $_GET['id'] ?? null;

if (!$id_paciente) {
    header("Location: pacientes.php");
    exit;
}

// Buscar dados do paciente
$sql = "SELECT * FROM paciente WHERE id_paciente = $id_paciente";
$res = mysqli_query($id, $sql);
$paciente = mysqli_fetch_assoc($res);

if (!$paciente) {
    header("Location: pacientes.php");
    exit;
}

// Formatar CPF para exibição
$cpf_formatado = $paciente['cpf'] ? 
    substr($paciente['cpf'], 0, 3) . '.' . 
    substr($paciente['cpf'], 3, 3) . '.' . 
    substr($paciente['cpf'], 6, 3) . '-' . 
    substr($paciente['cpf'], 9, 2) : '';

// Processar edição
if (isset($_POST['editar_paciente'])) {
    $nome = mysqli_real_escape_string($id, $_POST['nome']);
    $data_nascimento = mysqli_real_escape_string($id, $_POST['data_nascimento']);
    $cpf = mysqli_real_escape_string($id, $_POST['cpf']);
    
    // Limpar CPF
    $cpf_limpo = preg_replace('/[^\d]/', '', $cpf);
    
    if (!empty($cpf_limpo) && strlen($cpf_limpo) !== 11) {
        $erro = "CPF deve conter 11 dígitos!";
    } else {
        $update_sql = "UPDATE paciente SET 
                      nome = '$nome', 
                      data_nascimento = '$data_nascimento', 
                      cpf = '$cpf_limpo' 
                      WHERE id_paciente = $id_paciente";
        
        if (mysqli_query($id, $update_sql)) {
            header("Location: pacientes.php?sucesso=Paciente atualizado com sucesso!");
            exit;
        } else {
            $erro = "Erro ao atualizar paciente: " . mysqli_error($id);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Paciente - Cuida Bem</title>
    <style>
        /* ... (manter os mesmos estilos do pacientes.php) ... */
        
        /* Estilos do validador de CPF */
        .cpf-valido {
            border-color: #27ae60 !important;
            background-color: #f8fff9 !important;
        }
        
        .cpf-invalido {
            border-color: #e74c3c !important;
            background-color: #fff5f5 !important;
        }
    </style>
</head>
<body>
    <!-- ... (manter o mesmo header do pacientes.php) ... -->

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">Editar Paciente</h1>
                <a href="pacientes.php" class="btn btn-secondary">← Voltar</a>
            </div>
            
            <?php if (isset($erro)): ?>
                <div class="error"><?php echo $erro; ?></div>
            <?php endif; ?>
            
            <form method="POST" id="form-editar-paciente">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nome">Nome Completo:</label>
                        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($paciente['nome']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="cpf">CPF:</label>
                        <input type="text" id="cpf" name="cpf" value="<?php echo $cpf_formatado; ?>" placeholder="000.000.000-00" maxlength="14">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="data_nascimento">Data de Nascimento:</label>
                    <input type="date" id="data_nascimento" name="data_nascimento" value="<?php echo $paciente['data_nascimento']; ?>">
                </div>
                
                <div class="form-actions">
                    <button type="submit" name="editar_paciente" class="btn btn-primary">Salvar Alterações</button>
                    <a href="pacientes.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
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

                // Formatar valor inicial
                if (inputCPF.value) {
                    inputCPF.value = formatarCPF(inputCPF.value);
                }
            }

            // Validar CPF no envio do formulário
            const form = document.getElementById('form-editar-paciente');
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