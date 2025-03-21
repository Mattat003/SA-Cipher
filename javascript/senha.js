class ValidadorCodigo {
    constructor(formulario) {
        this.formulario = formulario;
        this.codigo = this.formulario.txtcodigo;
    }

    // Método para validar o código
    validarCodigo() {
        if (this.codigo.value === "") {
            alert("Por favor, insira o código enviado para o seu e-mail.");
            return false;
        }
        return true;
    }

    // Método para redirecionar o usuário para a próxima página
    redirecionar() {
        window.location.href = "email.html";
    }

    // Método principal para validar e redirecionar
    processarCodigo(event) {
        if (!this.validarCodigo()) {
            event.preventDefault(); // Impede o envio do formulário caso a validação falhe
            return false;
        }
        this.redirecionar();  // Caso a validação seja bem-sucedida, redireciona
        return false;
    }
}

// Função para ser chamada no evento de submit
function validaCodigo(event) {
    const formulario = document.forms["frmCodigo"];
    const validador = new ValidadorCodigo(formulario);  // Cria a instância da classe

    return validador.processarCodigo(event);  // Chama o método de validação e redirecionamento
}
