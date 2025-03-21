// Classe para representar um campo de entrada de código
class CampoDeCodigo {
    constructor(id) {
        this.campo = document.getElementById(id); // Acessa o campo de entrada pelo ID
    }

    // Método para verificar se o campo atual tem 1 caractere e mover o foco para o próximo campo
    moveFocoParaProximo(nextInputId) {
        if (this.campo.value.length === 1 && nextInputId) {
            document.getElementById(nextInputId).focus();
        }
    }

    // Método para distribuir os 4 caracteres do código nos campos individuais
    distribuirCodigo() {
        

        if (codigo.length === 4) {
            document.getElementById('digito1').value = codigo.charAt(0);
            document.getElementById('digito2').value = codigo.charAt(1);
            document.getElementById('digito3').value = codigo.charAt(2);
            document.getElementById('digito4').value = codigo.charAt(3);
        }
    }
}

// Classe para representar o processo de verificação do código
class VerificadorCodigo {
    verificar() {
        // Redireciona para a página senhaNova.html
        window.location.href = "senhaNova.html";
    }
}

// Instanciando o objeto CampoDeCodigo para o campo de código completo
const campoCodigo = new CampoDeCodigo('campoCodigo');

// Adicionando eventos para mover o foco e distribuir o código conforme a entrada
campoCodigo.campo.addEventListener('input', function () {
    campoCodigo.moveFocoParaProximo('campoCodigo2');  // Move o foco para o próximo campo
    campoCodigo.distribuirCodigo();  // Distribui o código nos campos
});

// Instanciando o objeto VerificadorCodigo para validar o código
const verificador = new VerificadorCodigo();

// Adicionando o evento para verificar o código
document.getElementById('btnVerificar').addEventListener('click', function() {
    verificador.verificar();  // Verifica o código e redireciona
});
function verificarCodigo() {
    // Simplesmente aceita qualquer código
    window.location.href = "senhaNova.html";
}
