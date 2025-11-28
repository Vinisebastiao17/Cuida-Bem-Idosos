<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuida Bem - Emergência</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: #d9534f;
            color: white;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        
        .container {
            max-width: 600px;
            padding: 40px;
        }
        
        .logo {
            font-size: 3em;
            margin-bottom: 20px;
            font-weight: bold;
        }
        
        .emergency-title {
            font-size: 2.5em;
            margin-bottom: 30px;
        }
        
        .emergency-contacts {
            background: rgba(255,255,255,0.1);
            border-radius: 15px;
            padding: 30px;
            margin: 30px 0;
        }
        
        .contact {
            padding: 15px;
            margin: 10px 0;
            background: rgba(255,255,255,0.2);
            border-radius: 8px;
            font-size: 1.2em;
        }
        
        .btn {
            display: inline-block;
            padding: 15px 30px;
            background: white;
            color: #d9534f;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin: 10px;
            transition: all 0.3s;
        }
        
        .btn:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">Cuida Bem</div>
        <h1 class="emergency-title">EMERGÊNCIA</h1>
        
        <div class="emergency-contacts">
            <div class="contact">SAMU: <strong>192</strong></div>
            <div class="contact">Bombeiros: <strong>193</strong></div>
            <div class="contact">Polícia: <strong>190</strong></div>
            <div class="contact">Emergência Móvel: <strong>911</strong></div>
        </div>
        
        <p style="margin-bottom: 20px; font-size: 1.2em;">
            Em caso de emergência, ligue imediatamente para os números acima ou entre em contato com seu médico.
        </p>
        
        <a href="dashboard.php" class="btn">Voltar ao Dashboard</a>
        <a href="index.php" class="btn">Página Inicial</a>
    </div>
</body>
</html>