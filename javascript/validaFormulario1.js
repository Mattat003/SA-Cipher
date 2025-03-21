class ValidadorFormularioFaleConosco {
    constructor(formulario) {
        this.formulario = formulario;
        this.nome = this.formulario.txtnome;
        this.telefone = this.formulario.txtfone;
        this.email = this.formulario.emlemail;
        this.motivo = this.formulario.selmotivo;
        this.comentario = this.formulario.txcomentario;
    }

    // Método para validar o campo de nome
    validarNome() {
        if (this.nome.value === "") {
            alert("Preencha o campo nome.");
            this.nome.focus();
            return false;
        }
        return true;
    }

    // Método para validar o campo de telefone
    validarTelefone() {
        if (this.telefone.value === "") {
            alert("Preencha o campo telefone.");
            this.telefone.focus();
            return false;
        }
        return true;
    }

    // Método para validar o campo de e-mail
    validarEmail() {
        if (this.email.value === "") {
            alert("Preencha o campo e-mail.");
            this.email.focus();
            return false;
        }
        return true;
    }

    // Método para validar o campo de motivo
    validarMotivo() {
        if (this.motivo.value === "") {
            alert("Preencha o campo motivo.");
            this.motivo.focus();
            return false;
        }
        return true;
    }

    // Método para validar o campo de comentário
    validarComentario() {
        if (this.comentario.value === "") {
            alert("Preencha o campo comentário.");
            this.comentario.focus();
            return false;
        }
        return true;
    }

    // Método para processar o envio do formulário
    processarFormulario() {
        if (
            !this.validarNome() ||
            !this.validarTelefone() ||
            !this.validarEmail() ||
            !this.validarMotivo() ||
            !this.validarComentario()
        ) {
            return false; // Se algum campo falhar na validação, não envia o formulário
        }
        return true; // Se todos os campos estiverem válidos, o formulário pode ser enviado
    }
}

// Função que será chamada ao submeter o formulário
function validaFormulario1() {
    const formulario = document.frmfaleconosco2;
    const validador = new ValidadorFormularioFaleConosco(formulario);  // Cria a instância da classe ValidadorFormularioFaleConosco

    return validador.processarFormulario();  // Chama o método para processar a validação
}
