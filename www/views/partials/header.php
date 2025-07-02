<header>
    <section class="search">

        <li>
            <a href="./main.php">
                <img src="../public/imagens/logo_branca.png" alt="Polvo escrevendo" class="logo">
            </a>
        </li>

        <div class="buscar">
            <input type="text" id="searchInput" placeholder=" 🔍︎ Buscar..." onkeyup="searchItems()" onkeydown="verificaEnter(event)">
        </div>

        <div class="light" id="lighting">
            <img class="sun" src="../../public/imagens/icons8-sun-50.png" alt="sun">
        </div>

        <div style="position: relative; display: inline-block;">
            <a href="perfil.php" class="user-avatar" id="userAvatar">
                <img src="../public/imagens/astronauta-user.png" alt="user-astronaut" class="user">
            </a>
            <div class="profile-hover" id="profileHover">
                <div class="profile-hover-bg">
                    <img src="../public/imagens/astronauta-user.png" alt="Avatar" class="profile-hover-avatar">
                    <div class="profile-hover-info">
                        <strong><?= htmlspecialchars($_SESSION['nome'] ?? 'Seu Nome') ?></strong>
                        <span><?= htmlspecialchars($_SESSION['email'] ?? 'email@exemplo.com') ?></span>
                    </div>
                    <a href="perfil.php" class="profile-hover-btn">Configurações</a>
                    <a href="../controllers/logout.php" class="profile-hover-logout">Sair</a>
                </div>
            </div>
        </div>

    </section>
</header>