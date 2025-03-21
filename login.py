from flask import Flask, request, render_template_string, redirect, url_for

app = Flask(__name__)

# Página inicial com o formulário
@app.route('/')
def index():
    return '''
    <!DOCTYPE html>
    <html lang="pt-br">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="css/login.css">
        </head>
        <body>
            <div class="right-login">
                <div class="card-login">
                    <h1> LOGIN </h1>
                    <!-- Form para login -->
                    <form action="/login" method="post">
                        <div class="textfield">
                            <label for="usuario">Usuário</label>
                            <input type="text" name="usuario" id="usuario" placeholder="Usuário" required>
                        </div>
                        <div class="textfield">
                            <label for="senha">Senha</label>
                            <input type="password" name="senha" id="senha" placeholder="Senha" required>
                        </div>
                        <div class="btn-container">
                            <button type="submit" class="btn-login">Login</button>
                            <a href="senha.html">
                                <button class="btn-login" type="button">Esqueceu a senha?</button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </body>
    </html>
    '''

# Processa o login
@app.route('/login', methods=['POST'])
def login():
    # Pega os dados do form
    usuario = request.form.get('usuario')
    senha = request.form.get('senha')

    # Lista de erros
    erros = []
    if not usuario:
        erros.append("O campo 'Usuário' é obrigatório.")
    if not senha:
        erros.append("O campo 'Senha' é obrigatório.")

    # Exibe erros, se houver
    if erros:
        return render_template_string('''
        <!DOCTYPE html>
        <html lang="pt-br">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Erro no Login</title>
            </head>
            <body>
                <h1>Erro no Login</h1>
                <ul>
                    {% for erro in erros %}
                    <li>{{ erro }}</li>
                    {% endfor %}
                </ul>
                <a href="/">Voltar</a>
            </body>
        </html>
        ''', erros=erros)

    # Se não houver erros, redireciona
    return redirect(url_for('index'))

# Inicia o servidor
if __name__ == '__main__':
    app.run(debug=True)
