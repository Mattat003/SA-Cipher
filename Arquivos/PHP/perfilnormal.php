<?php
session_start();
require_once 'conexao.php'; // conexão PDO

$nome = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Usuário';
$foto_perfil_padrao = "https://cdn-icons-png.flaticon.com/512/847/847969.png";
$foto_perfil = $foto_perfil_padrao;

if (isset($_SESSION['usuario'])) {
    $stmt = $pdo->prepare("SELECT foto_perfil FROM usuario WHERE nome_user = :nome");
    $stmt->bindParam(":nome", $_SESSION['usuario']);
    $stmt->execute();
    $foto_bd = $stmt->fetchColumn();
    if (!empty($foto_bd)) {
        $foto_perfil = "../" . $foto_bd;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/perfil.css">
    <style>
          body {
            background: linear-gradient(135deg, #0f0c29, #302b63);
            min-height: 100vh;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 40px 20px;
        }
        .main-container {
            display: flex;
            flex-direction: row;
            min-height: 100vh;
            max-width: 1200px;
            margin: 0 auto;
            gap: 32px;
            padding: 30px 8px;
        }
        .conteudo {
            flex: 2;
            background: rgba(255,255,255,0.03);
            border-radius: 18px;
            box-shadow: 0 4px 16px rgba(81, 13, 150, 0.12);
            padding: 24px 18px 18px 18px;
            margin-bottom: 24px;
        }
        .lateral {
            flex: 1;
            min-width: 270px;
            max-width: 350px;
            background: rgba(81, 13, 150, 0.06);
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(81, 13, 150, 0.11);
            padding: 22px 16px 18px 16px;
            display: flex;
            flex-direction: column;
            height: fit-content;
            position: sticky;
            top: 30px;
        }

        .profile-picture-container {
            position: relative;
            width: 110px;
            height: 110px;
            margin: 0 auto 15px;
            cursor: pointer;
        }
        .profile-picture {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #f0e6ff;
            box-shadow: 0 4px 15px rgba(81, 13, 150, 0.2);
            transition: all 0.3s ease;
        }
        .profile-picture:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(81, 13, 150, 0.3);
        }
        .edit-overlay {
            position: absolute;
            bottom: 0;
            right: 0;
            background: #510d96;
            color: #f0e6ff;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            border: 2px solid #f0e6ff;
        }
        .profile-name {
            text-align: center;
            font-size: 1.3rem;
            color: #ffffff;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .photo-actions {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }
        .photo-btn {
            background: #510d96;
            color: #f0e6ff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s;
            min-width: 110px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            box-shadow: 0 2px 8px rgba(81, 13, 150, 0.2);
        }
        .photo-btn:hover {
            background: #7a5af5;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(81, 13, 150, 0.3);
        }
        .camera-preview {
            display: none;
            width: 100%;
            max-width: 300px;
            margin: 15px auto;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: 2px solid #f0e6ff;
        }
        .camera-controls {
            display: none;
            justify-content: center;
            gap: 10px;
            margin: 10px auto 20px;
        }
        .capture-btn {
            background: #510d96;
            color: #f0e6ff;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .capture-btn:hover {
            background: #7a5af5;
        }
        .voltar-btn {
            background: #510d96;
            color: #f0e6ff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 0.9rem;
            cursor: pointer;
            min-width: 110px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            box-shadow: 0 2px 8px rgba(81, 13, 150, 0.2);
            margin-bottom: 12px;
        }
        .voltar-btn:hover {
            background: #7a5af5;
            box-shadow: 0 4px 12px rgba(81, 13, 150, 0.3);
        }
        .voltar-btn a {
            text-decoration: none;
            color: inherit;
        }
        /* Histórico */
        #historico-jogos .jogo {
            background: rgba(93, 13, 173, 0.22);
            padding: 10px 14px;
            border-radius: 7px;
            margin-bottom: 12px;
            box-shadow: 0 1px 5px rgba(65, 9, 121, 0.08);
        }
        #historico-jogos h4 {
            margin: 0 0 3px 0;
            color: #e3d6ff;
            font-size: 1.05rem;
        }
        /* Lateral: Chat/Amigos */
        .amigos h3, .sair h3 {
            font-size: 1.15rem;
            margin-bottom: 8px;
            margin-top: 0;
        }
        .amigos a, .sair a {
            color: #510d96;
            text-decoration: none;
            transition: color 0.2s;
        }
        .amigos a:hover, .sair a:hover {
            color: #7a5af5;
            text-decoration: underline;
        }
        .amigos {
            margin-bottom: 30px;
        }
        .sair {
            margin-top: 36px;
            display: flex;
            margin-left: 42px;
        }
        .amigos .bi, .sair .bi {
            margin-right: 7px;
        }
        .lateral span {
            color: #f0e6ff;
            font-size:0.97rem;
            margin-top:8px;
            display:block;
            text-align:center;
        }
        /* Responsivo */
        @media (max-width: 990px) {
            .main-container {
                flex-direction: column;
                gap: 22px;
                padding: 18px 4px;
            }
            .lateral {
                position: static;
                top: unset;
                width: 100%;
                min-width: unset;
                max-width: unset;
            }
        }
        @media (max-width: 576px) {
            .main-container {
                padding: 6px 1px;
            }
            .conteudo {
                padding: 14px 5px 10px 5px;
            }
            .profile-picture-container {
                width: 90px;
                height: 90px;
            }
            .photo-actions {
                gap: 8px;
            }
            .photo-btn {
                min-width: 90px;
                padding: 6px 12px;
                font-size: 0.85rem;
            }
            .lateral {
                padding: 10px 3px 8px 3px;
            }
        }
    </style>
</head>
<body>
<div class="main-container">
    <div class="conteudo">
        <button class="voltar-btn" id="voltar"><a href="index.php">Voltar</a></button>
        <div class="profile-picture-container" onclick="document.getElementById('upload-foto').click()">
            <img id="imagem-perfil" class="profile-picture" src="<?php echo htmlspecialchars($foto_perfil); ?>" alt="Foto de Perfil">
            <div class="edit-overlay">✎</div>
            <input type="file" id="upload-foto" accept="image/*" hidden>
        </div>
        <div class="profile-name">
            <?php echo htmlspecialchars($_SESSION['usuario']); ?>
        </div>
        <div class="photo-actions">
            <button class="photo-btn" id="trocar-foto">
                <i class="bi bi-upload"></i> Trocar
            </button>
            <button class="photo-btn" id="tirar-foto">
                <i class="bi bi-camera"></i> Câmera
            </button>
            <button class="photo-btn" id="remover-foto">
                <i class="bi bi-trash"></i> Remover
            </button>
        </div>
        <video id="camera" class="camera-preview" autoplay playsinline></video>
        <div class="camera-controls">
            <button class="capture-btn" id="capturar-foto">Capturar</button>
            <button class="capture-btn" id="cancelar-camera" style="background: #dc3545;">Cancelar</button>
        </div>
        <h3 style="margin-top:30px;">Histórico de Jogos</h3>
        <div id="historico-jogos">
            <p>Carregando histórico...</p>
        </div>
    </div>
    <div class="lateral">
        <div class="amigos">
            <h3>
                <a href="chat.php">
                    <i class="bi bi-chat-dots"></i>
                    Chat
                </a>
            </h3>
            <span>
                Converse agora com seus amigos!
            </span>
        </div>
        <div class="sair">
            <h3>
                <a href="logout.php">
                    <i class="bi bi-box-arrow-right"></i>
                    Sair
                </a>
            </h3>
        </div>
    </div>
</div>
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // --- FOTO DE PERFIL ---
    const uploadFoto = document.getElementById('upload-foto');
    const imagemPerfil = document.getElementById('imagem-perfil');
    const trocarFoto = document.getElementById('trocar-foto');
    const removerFoto = document.getElementById('remover-foto');
    const tirarFoto = document.getElementById('tirar-foto');
    const camera = document.getElementById('camera');
    const fotoPadrao = "<?php echo $foto_perfil_padrao; ?>";

    trocarFoto.addEventListener('click', (e) => {
        e.preventDefault();
        uploadFoto.click();
    });

    uploadFoto.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const formData = new FormData();
            formData.append('foto', file);
            fetch('upload_foto.php', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(dados => {
                if (dados.ok) {
                    imagemPerfil.src = "../" + dados.caminho + '?' + new Date().getTime();
                } else {
                    alert(dados.erro || 'Erro ao fazer upload.');
                }
            })
            .catch(() => alert('Erro ao conectar ao servidor.'));
        }
    });

    removerFoto.addEventListener('click', (e) => {
        e.preventDefault();
        fetch('remover_foto.php', { method: 'POST' })
        .then(r => r.json())
        .then(dados => {
            if (dados.ok) {
                imagemPerfil.src = fotoPadrao;
            } else {
                alert(dados.erro || 'Erro ao remover foto.');
            }
        });
    });

    tirarFoto.addEventListener('click', async (e) => {
        e.preventDefault();
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: true });
            camera.srcObject = stream;
            camera.style.display = 'block';
            setTimeout(() => {
                const canvas = document.createElement('canvas');
                canvas.width = camera.videoWidth;
                canvas.height = camera.videoHeight;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(camera, 0, 0, canvas.width, canvas.height);
                imagemPerfil.src = canvas.toDataURL('image/png');
                stream.getTracks().forEach(track => track.stop());
                camera.style.display = 'none';
            }, 2000);
        } catch (error) {
            alert('Erro ao acessar a câmera: ' + error.message);
        }
    });

    // --- HISTÓRICO DE JOGOS ---
    function carregarHistorico() {
        fetch('listar_historico.php')
            .then(r => {
                if (!r.ok) throw new Error("Erro ao buscar histórico");
                return r.json();
            })
            .then(historico => {
                let html = '';
                if (!Array.isArray(historico) || historico.length === 0) {
                    html = '<p>Nenhum histórico encontrado.</p>';
                } else {
                    historico.forEach(item => {
                        let dataFormatada = '';
                        if (item.hora_entrada) {
                            let data = new Date(item.hora_entrada.replace(' ', 'T'));
                            dataFormatada = data.toLocaleString('pt-BR');
                        }
                        html += `
                            <div class="jogo">
                                <div class="info">
                                    <h4>${item.nome_jogo ? item.nome_jogo : 'Jogo desconhecido'}</h4>
                                    <p style="margin-bottom:0; color:#bbb; font-size:0.97rem;">
                                        Jogou em: <span style="color:#ddd;">${dataFormatada}</span>
                                    </p>
                                </div>
                            </div>`;
                    });
                }
                document.getElementById('historico-jogos').innerHTML = html;
            })
            .catch(() => {
                document.getElementById('historico-jogos').innerHTML = '<p>Erro ao carregar histórico.</p>';
            });
    }
    carregarHistorico();
</script>
</body>
</html>