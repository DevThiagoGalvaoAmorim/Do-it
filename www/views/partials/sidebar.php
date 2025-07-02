<?php
require_once '../controllers/safe_page.php';
?>

<section class="sidebar">

    <button class="toggle-btn">
        <span>Menu</span>
    </button>

    <ul class="menu">
        <li>
            <a href="folder.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-folder-fill" viewBox="0 0 16 16">
                    <path
                        d="M9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.825a2 2 0 0 1-1.991-1.819l-.637-7a2 2 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3m-8.322.12q.322-.119.684-.12h5.396l-.707-.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981z" />
                </svg>
                <span>Pastas</span>
            </a>
        </li>
        <li>
            <a href="./notification.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-bell-fill" viewBox="0 0 16 16">
                    <path
                        d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901" />
                </svg>
                <span>Lembretes</span>
            </a>
        </li>
        <li>
            <a href="./archives.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-archive-fill" viewBox="0 0 16 16">
                    <path
                        d="M12.643 15C13.979 15 15 13.845 15 12.5V5H1v7.5C1 13.845 2.021 15 3.357 15zM5.5 7h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1M.8 1a.8.8 0 0 0-.8.8V3a.8.8 0 0 0 .8.8h14.4A.8.8 0 0 0 16 3V1.8a.8.8 0 0 0-.8-.8z" />
                </svg>
                <span>Arquivados</span>
            </a>
        </li>
        <li>
            <a href="./trash.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-trash-fill" viewBox="0 0 16 16">
                    <path
                        d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0" />
                </svg>
                <span>Lixeira</span>
            </a>
        </li>
        <li>
            <a href="./sac.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                    <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286m1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94" />
                </svg>
                <span>Sac</span>
            </a>
        </li>
            <!--Verificação de sessão para exibir o link do admin-->

        <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin'): ?>
            <li>
                <a href="/views/admin/admin_view.php">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15.6316 7.63137C15.2356 7.23535 15.0376 7.03735 14.9634 6.80902C14.8981 6.60817 14.8981 6.39183 14.9634 6.19098C15.0376 5.96265 15.2356 5.76465 15.6316 5.36863L18.47 2.53026C17.7168 2.18962 16.8806 2 16.0002 2C12.6865 2 10.0002 4.68629 10.0002 8C10.0002 8.49104 10.0592 8.9683 10.1705 9.42509C10.2896 9.91424 10.3492 10.1588 10.3387 10.3133C10.3276 10.4751 10.3035 10.5612 10.2289 10.7051C10.1576 10.8426 10.0211 10.9791 9.74804 11.2522L3.50023 17.5C2.6718 18.3284 2.6718 19.6716 3.50023 20.5C4.32865 21.3284 5.6718 21.3284 6.50023 20.5L12.748 14.2522C13.0211 13.9791 13.1576 13.8426 13.2951 13.7714C13.4391 13.6968 13.5251 13.6727 13.6869 13.6616C13.8414 13.651 14.086 13.7106 14.5751 13.8297C15.0319 13.941 15.5092 14 16.0002 14C19.3139 14 22.0002 11.3137 22.0002 8C22.0002 7.11959 21.8106 6.28347 21.47 5.53026L18.6316 8.36863C18.2356 8.76465 18.0376 8.96265 17.8092 9.03684C17.6084 9.1021 17.3921 9.1021 17.1912 9.03684C16.9629 8.96265 16.7649 8.76465 16.3689 8.36863L15.6316 7.63137Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                 </svg>
                 <span>Admin</span>
                </a>
        </li>
        <?php endif; ?>

    </ul>

</section>