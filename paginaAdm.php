<?php
session_start();
$nome = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Usuário';
?>




<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/perfil.css">
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
                <h3>Atividades Recentes</h3>
                <div class="jogo">
                    <img src="img/dgslife.jpg" alt="Dogs Life">
                    <div class="info">
                        <h4>Dogs Life</h4>
                        <p>Horas Jogadas</p>
                        <div class="barra">
                            <div class="progresso" style="width: 30%;"></div>
                        </div>
                        <p>Conquistas 30%</p>
                    </div>
                </div>

                <div class="jogo">
                    <img src="img/BaldS.png" alt="Bald Simulator">
                    <div class="info">
                        <h4>Bald Simulator</h4>
                        <p>Horas Jogadas</p>
                        <div class="barra">
                            <div class="progresso" style="width: 95%;"></div>
                        </div>
                        <p>Conquistas 95%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lateral -->
        <div class="lateral">
            <div class="amigos">
              <h3>Amizades:</h3>
              <ul>
                <li>Amanda 
                <button type="button" class="btn btn-primary" id="liveToastBtn"> Solicitar amizade</button></li>
                <li>Endryo <a href = "chat.php"><button>Conversar</button></a></li>
                <li>Neon <button>Conversar</button></li>
                <li>Pamella <button>Conversar</button></li>
              </ul>
            </div>
            <div class="grupos">
              <h3>Grupos</h3>
              <ul>
                <li>Time RPG <button>Entrar</button></li>
                <li>Senas/Senai <button>Entrar</button></li>
                <li>Rogério Fan Club <button>Entrar</button></li>
              </ul>
            </div>
        </div>
    </div>

    <!-- Menu lateral -->
    <div class="menu-toggle" onclick="toggleMenu()">☰</div>
    <aside class="side-menu" id="sideMenu">
        <div class="menu">
            <div class="menu-item">
                <a href="../SA-Cipher/endryo/biblioteca.html" class="play-btn">Biblioteca</a>
            </div>
            <div class="menu-item">Capturas</div>
            <div class="menu-item">Minhas Reviews</div>
        </div>
    </aside>

    <!-- Toast -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Notificação</strong>
                <small>Agora</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Solicitação enviada!
            </div>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
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

        const toastTrigger = document.getElementById('liveToastBtn');
        const toastLiveExample = document.getElementById('liveToast');

        if (toastTrigger) {
            toastTrigger.addEventListener('click', () => {
                const toast = new bootstrap.Toast(toastLiveExample);
                toast.show();
            });
        }
    </script>
    </body>
</html>