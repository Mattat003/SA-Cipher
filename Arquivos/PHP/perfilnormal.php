<?php
session_start();
require_once 'conexao.php';
echo "<!-- USUARIO DA SESSAO: " . $_SESSION['usuario'] . " -->";
$nome = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Usuário';
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
        /* Área da foto de perfil - Versão melhorada */
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

        /* Botões de ação - Versão melhorada */
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

        /* Câmera - Versão melhorada */
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

        /* Responsivo */
        @media (max-width: 576px) {
            .profile-picture-container {
                width: 95px;
                height: 95px;
            }
            
            .photo-actions {
                gap: 8px;
            }
            
            .photo-btn {
                min-width: 90px;
                padding: 6px 12px;
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>

    <div class="paginaperfil">
        <div class="conteudo">
            <div class="atividades">

                <!-- Área da foto - Versão melhorada -->
                <div class="profile-picture-container" onclick="document.getElementById('upload-foto').click()">
                    <img id="imagem-perfil" class="profile-picture" src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="Foto de Perfil">
                    <div class="edit-overlay">✎</div>
                    <input type="file" id="upload-foto" accept="image/*" hidden>
                </div>

                <div class="profile-name">
                <?php echo htmlspecialchars($_SESSION['usuario']); ?>
                </div>


                <!-- Botões de ação - Versão melhorada -->
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

                <!-- Câmera - Versão melhorada -->
                <video id="camera" class="camera-preview" autoplay playsinline></video>
                <div class="camera-controls">
                    <button class="capture-btn" id="capturar-foto">Capturar</button>
                    <button class="capture-btn" id="cancelar-camera" style="background: #dc3545;">Cancelar</button>
                </div>

                <!-- Resto do conteúdo -->
              

               
         <!-- NOVA SEÇÃO: Histórico de Jogos -->
         <h3 style="margin-top:30px;">Histórico de Jogos</h3>
                <div id="historico-jogos">
                  <p>Carregando histórico...</p>
                </div>
            </div>
        </div>

        <!-- Lateral -->
        <div class="lateral">
            <div class="amigos">
                <h3>
                <a href="chat.php">
                    <i class="bi bi-chat-dots"></i>
                    Chat
                </a>
                </h3>
                <span style="color:#f0e6ff; font-size:0.97rem; margin-top:8px; display:block; text-align:center;">
                Converse agora com seus amigos!
                </span>
            </div>
            </div>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // --- FOTO DE PERFIL ---
    const uploadFoto = document.getElementById('upload-foto');
    const imagemPerfil = document.getElementById('imagem-perfil');
    const trocarFoto = document.getElementById('trocar-foto');
    const removerFoto = document.getElementById('remover-foto');
    const tirarFoto = document.getElementById('tirar-foto');
    const camera = document.getElementById('camera');

    const fotoPadrao = "https://cdn-icons-png.flaticon.com/512/847/847969.png";

    trocarFoto.addEventListener('click', () => {
        uploadFoto.click();
    });

    uploadFoto.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                imagemPerfil.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });

    removerFoto.addEventListener('click', () => {
        imagemPerfil.src = fotoPadrao;
        uploadFoto.value = "";
    });

    tirarFoto.addEventListener('click', async () => {
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
            .then(r => r.json())
            .then(historico => {
                let html = '';
                if (historico.length === 0) {
                    html = '<p>Nenhum histórico encontrado.</p>';
                } else {
                    historico.forEach(item => {
                        const data = new Date(item.hora_entrada.replace(' ', 'T') + '-03:00'); // Horário de Brasília
                        const dataFormatada = data.toLocaleString('pt-BR');
                        html += `
                        <div class="jogo">
        
                            <div class="info">
                                <h4>${item.nome_jogo}</h4>
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
    // Chama a função ao abrir a página do perfil
    carregarHistorico();

    // Toast (opcional, não interfere no histórico)
    const toastTrigger = document.getElementById('liveToastBtn');
    const toastLiveExample = document.getElementById('liveToast');
    if (toastTrigger && toastLiveExample) {
        toastTrigger.addEventListener('click', () => {
            const toast = new bootstrap.Toast(toastLiveExample);
            toast.show();
        });
    }
</script>
</body>
</html>