class FormularioLogin {
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
        // Valida os campos e retorna false se algum não for preenchido corretamente
        if (!this.validarUsuario() || !this.validarSenha()) {
            return false;
        }
        return true;
    }
}

// Função para ser chamada ao submeter o formulário
function validaFormulario() {
    const formulario = document.frmlogin;
    const formularioLogin = new FormularioLogin(formulario);  // Cria a instância da classe

    return formularioLogin.validar();  // Chama o método de validação
}
