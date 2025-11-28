<?php
function rodapeSimples() {
    $ano = date('Y');
    return "
    <div style='
        background: #2c3e50;
        color: white;
        text-align: center;
        padding: 20px;
        margin-top: 30px;
        font-size: 14px;
        border-top: 3px solid #4a90e2;
    '>
        <p style='margin: 5px 0;'>
            &copy; $ano Cuida Bem - Todos os direitos reservados
        </p>
        <p style='margin: 5px 0; opacity: 0.8; font-size: 12px;'>
            Desenvolvido com ❤️ para cuidar de quem você ama
        </p>
    </div>
    ";
}

function rodapeLogin() {
    $ano = date('Y');
    return "
    <div style='
        background: rgba(44, 62, 80, 0.9);
        color: white;
        text-align: center;
        padding: 15px;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        font-size: 12px;
    '>
        &copy; $ano Cuida Bem
    </div>
    ";
}
?>