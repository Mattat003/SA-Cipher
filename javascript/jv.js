class ValidadorFormulario {
    constructor(formulario) {
        this.formulario = formulario;
        this.usuario = this.formulario.txtusuario;
        this.senha = this.formulario.txtsenha;
    }

    // Método para validar o nome de usuário
    validarUsuario() {
        if (this.usuario.value === "") {
            alert("Preencha o nome de usuário!");
            return false;
        }
        return true;
    }

    // Método para validar a senha
    validarSenha() {
        if (this.senha.value === "") {
            alert("Preencha a senha!");
            return false;
        }
        return true;
    }

    // Método principal para validar o formulário
    validar() {
        if (!this.validarUsuario() || !this.validarSenha()) {
            return false;
        }
        return true;
    }
}

// Função para ser chamada no evento de submit
function validaFormulario() {
    const formulario = document.frmlogin;
    const validador = new ValidadorFormulario(formulario);

    return validador.validar();  // Valida o formulário e retorna o resultado
}
