<?php
session_start();
require 'conexao.php';

if (isset($_GET['sair']) && $_GET['sair'] === 'true') {
    session_destroy();
    header('Location: index.php');
    exit();
}

if (!isset($_SESSION['pk_usuario'])) {
    header('Location: login.php');
    exit();
}


$usuario_id = $_SESSION['pk_usuario'];
$usuario_nome = $_SESSION['usuario'];


$stmt = $pdo->prepare("
    SELECT u.pk_usuario AS id, u.nome_user AS nome 
    FROM usuario u
    JOIN amigos a ON a.amigo_id = u.pk_usuario
    WHERE a.usuario_id = :usuario_id
");
$stmt->execute(['usuario_id' => $usuario_id]);
$amigos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<title>Chat</title>

<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  body {
    background: linear-gradient(135deg, #0f0c29, #302b63);
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 100vh;
    padding: 40px 20px;
    color: white;
  }

  #amigos {
    width: 220px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 15px;
    padding: 25px 20px;
    margin-right: 30px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.36);
    display: flex;
    flex-direction: column;
    gap: 15px;
  }

  #amigos h3 {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    font-size: 22px;
    background: linear-gradient(to right, #ffffff, #e0c3fc);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    margin-bottom: 15px;
  }

  #amigos ul {
    list-style: none;
    padding-left: 0;
    max-height: 350px;
    overflow-y: auto;
  }

  .amigo {
    padding: 10px 15px;
    cursor: pointer;
    border-radius: 12px;
    color: #e0c3fc;
    transition: background 0.3s ease;
    user-select: none;
  }

  .amigo:hover {
    background: rgba(154, 102, 223, 0.3);
  }

  .amigo.selecionado {
    background: #9a66df;
    color: white;
    font-weight: 600;
  }

  #amigos a {
    text-align: center;
    padding: 10px 15px;
    background: linear-gradient(to right, #8639df, #6a0dad);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    margin-top: 10px;
    display: block;
    transition: background 0.3s ease;
  }

  #amigos a:hover {
    background: linear-gradient(to right, #6a0dad, #5b009d);
  }

  #chat-container {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 30px 25px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.36);
    max-width: 700px;
    height: 600px;
  }

  #chat-container h3 {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    font-size: 24px;
    margin-bottom: 20px;
    background: linear-gradient(to right, #ffffff, #e0c3fc);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
  }

  #chat-box {
    flex: 1;
    overflow-y: auto;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 15px;
    padding: 20px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    margin-bottom: 25px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    color: #ddd;
  }

  .mensagem {
    padding: 12px 18px;
    border-radius: 20px;
    max-width: 70%;
    line-height: 1.4;
    font-size: 16px;
    word-wrap: break-word;
  }

  .self {
    background: #6a0dad;
    color: white;
    align-self: flex-end;
    box-shadow: 0 4px 12px rgba(106, 13, 173, 0.5);
  }

  .other {
    background: rgba(255, 255, 255, 0.15);
    color: #eee;
    align-self: flex-start;
  }

  form {
    display: flex;
    gap: 15px;
  }

  textarea {
    flex: 1;
    resize: none;
    padding: 15px;
    border-radius: 15px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    background: rgba(255, 255, 255, 0.1);
    color: white;
    font-size: 16px;
    outline: none;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  textarea:focus {
    border-color: #9a66df;
    box-shadow: 0 0 8px rgba(154, 102, 223, 0.5);
  }

  textarea::placeholder {
    color: #cbbde2;
    opacity: 0.7;
  }

  button {
    padding: 15px 25px;
    background: linear-gradient(to right, #8639df, #6a0dad);
    color: white;
    border: none;
    border-radius: 15px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 6px 20px rgba(134, 57, 223, 0.3);
    transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
    
  }

  button:hover {
    background: linear-gradient(to right, #6a0dad, #5b009d);
   
    box-shadow: 0 8px 25px rgba(106, 13, 173, 0.5);
  }
</style>
</head>
<body>

<div id="amigos">
    <h3>Amigos</h3>
    <?php if (count($amigos) == 0) echo "<p>Você não tem amigos ainda.</p>"; ?>
    <ul>
    <?php foreach ($amigos as $amigo): ?>
        <li class="amigo" data-id="<?= $amigo['id'] ?>"><?= htmlspecialchars($amigo['nome']) ?></li>
    <?php endforeach; ?>
    </ul>

    <a href="adicionar_amigo.php">+ Adicionar amigo</a>
    <a href="pedidos_recebidos.php" style="margin-top: 10px;">Ver pedidos de amizade</a>
    <a href="index.php" style="margin-top: 10px;">Sair</a>

</div>

<div id="chat-container">
    <h3>Conversa com <span id="nome-amigo">Selecione um amigo</span></h3>
    <div id="chat-box"></div>

    <form id="form-mensagem" style="display:none;">
        <textarea name="mensagem" placeholder="Digite sua mensagem..." required></textarea>
        <button type="submit">Enviar</button>
    </form>
</div>

<script>
const usuarioId = <?= json_encode($usuario_id) ?>;
let amigoSelecionado = null;
const chatBox = document.getElementById('chat-box');
const form = document.getElementById('form-mensagem');
const nomeAmigoSpan = document.getElementById('nome-amigo');

document.querySelectorAll('.amigo').forEach(el => {
    el.addEventListener('click', () => {
        document.querySelectorAll('.amigo').forEach(a => a.classList.remove('selecionado'));
        el.classList.add('selecionado');
        amigoSelecionado = el.getAttribute('data-id');
        nomeAmigoSpan.textContent = el.textContent;
        form.style.display = 'flex';
        carregarMensagens();
    });
});

function carregarMensagens() {
    if (!amigoSelecionado) return;

    fetch(`mensagens.php?amigo_id=${amigoSelecionado}`)
        .then(res => res.json())
        .then(data => {
            if (!data.success || !Array.isArray(data.messages)) {
                chatBox.innerHTML = "<p>Erro ao carregar mensagens.</p>";
                return;
            }

            chatBox.innerHTML = '';
            data.messages.forEach(m => {
                const div = document.createElement('div');
                div.className = 'mensagem ' + (m.de_id == usuarioId ? 'self' : 'other');
                div.textContent = m.mensagem;
                chatBox.appendChild(div);
            });
            chatBox.scrollTop = chatBox.scrollHeight;
        })
        .catch(err => {
            console.error("Erro ao carregar mensagens:", err);
            chatBox.innerHTML = "<p>Erro ao carregar mensagens.</p>";
        });
}

form.addEventListener('submit', e => {
    e.preventDefault();
    const mensagem = form.mensagem.value.trim();
    if (!mensagem || !amigoSelecionado) return;

    const fd = new FormData();
    fd.append('para_id', amigoSelecionado);
    fd.append('mensagem', mensagem);

    fetch('enviar.php', {
        method: 'POST',
        body: fd
    }).then(async res => {
        const text = await res.text();
        try {
            const data = JSON.parse(text);
            if (data.success) {
                form.mensagem.value = '';
                carregarMensagens();
            } else {
                alert("Erro ao enviar: " + (data.error || "erro desconhecido"));
            }
        } catch (e) {
            alert("Resposta inválida do servidor: " + text);
            console.error("Erro ao analisar JSON:", e);
        }
    }).catch(err => {
        console.error("Erro na requisição:", err);
        alert("Erro ao enviar a mensagem.");
    });
});

setInterval(() => {
    if (amigoSelecionado) carregarMensagens();
}, 2000);

</script>


</body>
</html>
