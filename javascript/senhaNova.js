class ValidadorFormulario {
    constructor(formulario) {
        this.formulario = formulario;
        this.senha = this.formulario.txtsenha;
        this.senha2 = this.formulario.txtsenha2;
    }

    // Método para validar o campo de senha
    validarSenha() {
        if (this.senha.value === "") {
            alert("Preencha a senha!");
            return false;
        }
        return true;
    }

    // Método para validar o campo de confirmação de senha
    validarSenha2() {
        if (this.senha2.value === "") {
            alert("Preencha a confirmação da senha!");
            return false;
        }
        return true;
    }

    // Método para verificar se as senhas coincidem
    validarSenhasIguais() {
        if (this.senha.value !== this.senha2.value) {
            alert("As senhas não coincidem. Tente novamente.");
            return false;
        }
        return true;
    }

    // Método para processar a validação do formulário
    processarFormulario() {
        if (!this.validarSenha() || !this.validarSenha2() || !this.validarSenhasIguais()) {
            return false;
        }
        window.location.href = "login.html";  // Redireciona para a página de login
        return false;
    }
}

// Função que será chamada ao submeter o formulário
function validaFormulario() {
    const formulario = document.frmlogin;
    const validador = new ValidadorFormulario(formulario);  // Cria a instância da classe ValidadorFormulario

    return validador.processarFormulario();  // Chama o método para processar a validação
}
