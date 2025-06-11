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
            <a href="./admin_view.php">
                <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M32.696 17.494C31.3002 18.8941 29.5203 19.8484 27.5816 20.2361C25.643 20.6238 23.633 20.4275 21.806 19.672L9.90403 34.016C7.72603 36.194 4.20603 36.194 2.02803 34.016C-0.149969 31.838 -0.149969 28.318 2.02803 26.162L16.372 14.238C14.876 10.608 15.602 6.29604 18.55 3.34804C21.366 0.532036 25.414 -0.215964 28.934 1.01604L22.576 7.37404L28.78 13.578L35.072 7.26404C36.238 10.74 35.468 14.722 32.696 17.494ZM4.38203 31.662C5.26203 32.52 6.67003 32.52 7.52803 31.662C8.40803 30.782 8.40803 29.374 7.52803 28.516C6.67003 27.636 5.26203 27.636 4.38203 28.516C3.96955 28.9357 3.73841 29.5006 3.73841 30.089C3.73841 30.6775 3.96955 31.2424 4.38203 31.662Z" fill="black" />
                </svg>
                <span>Administrador</span>
            </a>
        </li>
    </ul>
</section>