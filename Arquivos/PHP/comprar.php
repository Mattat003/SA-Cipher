<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Comprar</title>
    <link rel="stylesheet" href="../css/comprar.css">
    <style>
        .pagamento-opcoes {
            margin-top: 16px;
            display: flex;
            gap: 18px;
        }
        .pagamento-opcoes label {
            font-weight: 500;
            color: #e0c3fc;
            margin: 0;
        }
        .pagamento-detalhes {
            margin-top: 16px;
            padding: 13px 10px;
            background: rgba(255,255,255,0.04);
            border-radius: 8px;
            display: none;
        }
        .pagamento-detalhes.active {
            display: block;
        }
        .qr-pix {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }
        .qr-pix img {
            width: 140px;
            height: 140px;
        }
        .qr-pix small {
            color: #e0c3fc;
            font-size: 14px;
        }
        .pix-chave {
            margin-top: 12px;
            text-align: center;
        }
        .pix-chave input {
            background: rgba(255,255,255,0.08);
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 8px 12px;
            text-align: center;
            width: 220px;
            font-size: 15px;
        }
        .pix-chave label {
            color: #e0c3fc;
            font-size: 15px;
        }
    </style>
</head>
<body>
    <h1> Quer garantir o seu jogo?</h1>
    <p>Para comprar o jogo, por favor, bote suas informações abaixo e o modo de pagamento.</p>
    <form id="form-compra" action="processar_compra.php" method="POST" autocomplete="off">
        <label for="nome">Nome Completo:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>

        <div class="pagamento-opcoes">
            <span>Método de pagamento:</span>
            <label><input type="radio" name="metodo_pagamento" value="cartao" checked> Cartão</label>
            <label><input type="radio" name="metodo_pagamento" value="pix"> Pix</label>
        </div>
        <!-- Cartão -->
        <div class="pagamento-detalhes active" id="cartao-fields">
        <label for="cartao">Número do Cartão de Crédito:</label>
        <input 
            type="text" 
            id="cartao" 
            name="cartao" 
            inputmode="numeric" 
            pattern="[0-9]{8}" 
            maxlength="8" 
            required
            title="Digite exatamente 8 números"
        />
            <label for="data_validade">Data de Validade (MM/AA):</label>
            <input 
                type="text" 
                id="data_validade" 
                name="data_validade" 
                inputmode="numeric" 
                pattern="^(0[1-9]|1[0-2])\/[0-9]{2}$" 
                maxlength="5" 
                placeholder="MM/AA"
                required
                title="Formato MM/AA, exemplo: 11/11"
            />
            <label for="cvv">CVV:</label>
            <input 
                type="text" 
                id="cvv" 
                name="cvv" 
                inputmode="numeric" 
                pattern="[0-9]{3}" 
                maxlength="3" 
                required 
                title="Informe os 3 dígitos do CVV"
            />
        </div>
        <!-- Pix -->
        <div class="pagamento-detalhes" id="pix-fields">
            <div class="qr-pix">
                <img src="../img/qr.png" alt="QR Code Pix">
                <small>Escaneie o QR Code acima para pagar via Pix</small>
            </div>
            <div class="pix-chave">
                <label for="chave_pix">Chave Pix:</label><br>
                <input type="text" id="chave_pix" readonly value="12.345.678/0001-89">
            </div>
        </div>

        <button type="submit">Comprar</button>
    </form>
    <p>Após a compra, vá a nossa unidade e resgate seu jogo físico!</p> 
    <script>
    // Alternar campos de acordo com método de pagamento
    const cartaoFields = document.getElementById('cartao-fields');
    const pixFields = document.getElementById('pix-fields');
    function atualizarPagamento() {
        cartaoFields.classList.remove('active');
        pixFields.classList.remove('active');
        const metodo = document.querySelector('input[name="metodo_pagamento"]:checked').value;
        if(metodo === 'cartao') cartaoFields.classList.add('active');
        if(metodo === 'pix') pixFields.classList.add('active');
    }
    document.querySelectorAll('input[name="metodo_pagamento"]').forEach(radio => {
        radio.addEventListener('change', atualizarPagamento);
    });
    atualizarPagamento();

    // Validação do formulário para cartão
    document.getElementById('form-compra').addEventListener('submit', function(e) {
        const metodo = document.querySelector('input[name="metodo_pagamento"]:checked').value;
        if(metodo === 'cartao') {
            const numeroCartao = document.getElementById('cartao').value.trim();
            const validade = document.getElementById('data_validade').value.trim();
            const cvv = document.getElementById('cvv').value.trim();
            let falta = [];
            if(!/^[0-9]{8}$/.test(numeroCartao)) falta.push("Número do Cartão (8 dígitos)");
            if(!/^(0[1-9]|1[0-2])\/[0-9]{2}$/.test(validade)) falta.push("Data de Validade (MM/AA, ex: 11/11)");
            if(!/^[0-9]{3}$/.test(cvv)) falta.push("CVV (3 dígitos)");
            if(falta.length > 0) {
                alert("Por favor, corrija: " + falta.join(", "));
                e.preventDefault();
            }
        }
    });

    // Barrinha automática no campo de validade
    const dataValidade = document.getElementById('data_validade');
    dataValidade.addEventListener('input', function(e) {
        let v = this.value.replace(/\D/g, ''); // remove tudo que não for número
        if (v.length > 2) v = v.slice(0,2) + '/' + v.slice(2,4);
        this.value = v;
    });
    document.getElementById('cartao').addEventListener('input', function(e) {
        // Remove tudo que não for número
        this.value = this.value.replace(/\D/g, '').slice(0, 8);
    });
    document.getElementById('cvv').addEventListener('input', function(e) {
        this.value = this.value.replace(/\D/g, '').slice(0, 3);
    });
    </script>
</body>
</html>