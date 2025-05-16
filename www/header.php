<header>
    <section class="search">

        <li>
            <a href="main.php" onclick="return parallaxTransition('main.php')">
                <img src="./imagens/logo_branca.png" alt="Polvo escrevendo" class="logo transition-element">
            </a>
        </li>

        <div class="buscar transition-element">
            <input type="text" id="searchInput" placeholder=" 🔍︎ Buscar..." onkeyup="searchItems()" onkeydown="verificaEnter(event)">
        </div>

        <div class="light transition-element" id="lighting">
            <img class="sun" src="./imagens/icons8-sun-50.png" alt="sun">
        </div>

        <a href="#" class="transition-element">
            <img src="./imagens/astronauta-user.png" alt="user-astronaut" class="user">
        </a>

    </section>
</header>