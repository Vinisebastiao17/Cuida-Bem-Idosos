// validar_cpf_simples.js - Validador de CPF para o Cuida Bem

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

function inicializarValidacaoCPF() {
    const inputsCPF = document.querySelectorAll('input[name*="cpf"], input[id*="cpf"]');
    
    inputsCPF.forEach(input => {
        input.addEventListener('input', function() {
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
        
        if (input.value) {
            input.value = formatarCPF(input.value);
        }
    });
}

// CSS básico
const estiloCPF = `
    .cpf-valido { border-color: #27ae60 !important; background-color: #f8fff9 !important; }
    .cpf-invalido { border-color: #e74c3c !important; background-color: #fff5f5 !important; }
`;

// Inicializar
document.addEventListener('DOMContentLoaded', function() {
    const style = document.createElement('style');
    style.textContent = estiloCPF;
    document.head.appendChild(style);
    inicializarValidacaoCPF();
});