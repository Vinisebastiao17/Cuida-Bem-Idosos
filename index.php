<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuida Bem - Cuidado de quem você ama</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #4a90e2, #5cb85c);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .container {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-align: center;
            max-width: 400px;
            width: 90%;
        }
        
        .logo {
            font-size: 2.5em;
            color: #4a90e2;
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        .tagline {
            color: #666;
            margin-bottom: 30px;
            font-size: 1.1em;
        }
        
        .btn {
            display: block;
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            text-align: center;
        }
        
        .btn-primary {
            background: #4a90e2;
            color: white;
        }
        
        .btn-secondary {
            background: #5cb85c;
            color: white;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .emergency {
            background: #d9534f;
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">Cuida Bem</div>
        <div class="tagline">Cuidado de quem você ama</div>
        
        <a href="login.php" class="btn btn-primary">Login</a>
        <a href="cadastrar.php" class="btn btn-secondary">Cadastrar</a>
        <a href="emergencia.php" class="btn emergency">EMERGÊNCIA</a>
    </div>
</body>
</html>