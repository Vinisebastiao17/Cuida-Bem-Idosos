<?php
function exibirLogo($tamanho = 'normal') {
    $tamanhos = [
        'pequeno' => '20px',
        'normal' => '24px', 
        'grande' => '32px',
        'xl' => '48px'
    ];
    
    $tamanho_fonte = $tamanhos[$tamanho] ?? '24px';
    
    return "
    <div class='logo-cuida-bem' style='
        font-size: $tamanho_fonte;
        font-weight: bold;
        color: #4a90e2;
        display: flex;
        align-items: center;
        gap: 10px;
    '>
        <span style='
            background: #4a90e2;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        '>❤️</span>
        Cuida Bem
    </div>
    ";
}

function exibirLogoCompleto() {
    return "
    <div class='logo-completo' style='
        text-align: center;
        padding: 20px 0;
    '>
        <div style='
            background: linear-gradient(135deg, #4a90e2, #5cb85c);
            color: white;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            margin: 0 auto 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        '>❤️</div>
        <h1 style='
            color: #2c3e50;
            margin: 0;
            font-size: 2.5em;
        '>Cuida Bem</h1>
        <p style='
            color: #7f8c8d;
            margin: 5px 0 0;
            font-size: 1.1em;
        '>Cuidado de quem você ama</p>
    </div>
    ";
}
?>