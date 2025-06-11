<?php 
function tela_de_mensagem($mensagem){
  echo '


<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Alerta</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body, html {
      width: 100%;
      height: 100%;
      font-family: Arial, sans-serif;
      overflow: hidden;
    }

    body {
      background: url("../../public/imagens/fundowarning.gif") no-repeat center center fixed;
      background-size: cover;
    }

    .container {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background-color: rgba(50, 50, 50, 0.8);
      border-radius: 12px;
      padding: 40px;
      text-align: center;
      width: 500px;
      box-shadow: 0 0 20px rgba(0,0,0,0.6);
    }

    .logo {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      margin: 0 auto 30px;
      display: flex;
      align-items: center;
      justify-content: center;
      animation: swing 3s ease-in-out infinite alternate;
    }

    .logo img {
      width: 100%;
      height: 100%;
      border-radius: 50%;
    }

    @keyframes swing {
      0% { transform: rotate(-30deg); }
      100% { transform: rotate(30deg); }
    }

    .mensagem {
      color: #fff;
      margin-bottom: 25px;
      font-size: 20px;
    }

    .btn-login {
      background-color: black;
      color: white;
      border: none;
      padding: 12px 25px;
      font-size: 18px;
      border-radius: 8px;
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
    }

    .btn-login:hover {
      background-color: #222;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="logo">
      <img src="../../public/imagens/povin.png" alt="logo">
    </div>
    <div class="mensagem" id="mensagem">'. $mensagem . '</div>
    <a href="/views/auth/login.php" class="btn-login">Ir para Login</a>
  </div>

</body>
</html>';
}

