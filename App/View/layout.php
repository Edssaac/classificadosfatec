<!DOCTYPE html>

<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Site especializado em anúncios para alunos da Fatec. Descubra produtos e monitorias que com certeza você se interessará!">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/assets/img/favicon.ico">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />

    <!-- FontAwesome Icons CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Classificados Fatec CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/sidebar.css">
    <link rel="stylesheet" href="/assets/css/swiper.css">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>

    <!-- jquery.mask -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Classificados Fatec JS -->
    <script src="/assets/js/sliderbar.js" defer></script>
    <script src="/assets/js/swiper.js" defer></script>

    <title>Classificados Fatec</title>
</head>

<body>
    <header class="text-center bg-vermelho">
        <a href="/">
            <img class="banner" src="/assets/img/banner.png" alt="classificados" draggable="false">
        </a>
    </header>

    <nav class="container-fluid py-3">
        <div class="row align-items-center">
            <form action="/pesquisar" method="POST" class="d-flex me-auto mb-3 mb-lg-0 col-md-6">
                <input class="form-control me-2" type="search" placeholder="Pesquisar" id="pesquisar" name="pesquisar" aria-label="Search" value="<?= $this->view->pesquisar ?>">
                <button class="button-search" type="submit" title="pesquisar">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <?php if ($this->view->filtrar) { ?>
                    <div class="mx-1">
                        <button class="button button-input" type="button" data-bs-toggle="modal" data-bs-target="#modalFiltrar">
                            <i class="fa-solid fa-filter"></i>
                        </button>
                    </div>
                <?php } ?>
            </form>
            <div class="d-flex gap-3 col-md-6 justify-content-md-end">
                <button class="button btn-menu" id="btn-menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <?php if ($this->view->login && $this->view->admin) { ?>
                    <a href="/relatorio" target="_blank">
                        <button class="button" type="button" title="Relatório de usuários">
                            <i class="fa-regular fa-clipboard"></i>
                        </button>
                    </a>
                <?php } ?>
                <?php if ($this->view->login) { ?>
                    <a href="/perfil">
                        <button class="button" type="button">
                            <i class="fa-solid fa-user"></i> Meu Perfil
                        </button>
                    </a>
                    <a href="/sair">
                        <button class="button" type="button">
                            <i class="fa-solid fa-door-open"></i> Sair
                        </button>
                    </a>
                <?php } else { ?>
                    <a href="/cadastrar">
                        <button class="button" type="button">
                            <i class="fa-solid fa-user-plus"></i> Cadastrar
                        </button>
                    </a>
                    <a href="/entrar">
                        <button class="button" type="button">
                            <i class="fa-solid fa-arrow-right-to-bracket"></i> Entrar
                        </button>
                    </a>
                <?php } ?>
            </div>
        </div>
    </nav>

    <div class="nav" id="sidebar">
        <div class="btn-close" id="btn-close"></div>
        <ul class="nav-list">
            <li class="nav-item">
                <a href="/" class="nav-link flex">
                    <i class="fa-solid fa-house"></i> &nbsp; Home
                </a>
            </li>
            <li class="nav-item">
                <a class="collapsed nav-link-dropdown" href="#anunciar-collapse" data-bs-toggle="collapse" aria-expanded="false" aria-controls="anunciar-collapse">
                    <i class="fa-solid fa-plus"></i> &nbsp; Anunciar
                </a>
                <div id="anunciar-collapse" class="collapse">
                    <div class="collapse-inner">
                        <ul class="btn-toggle-nav list-unstyled fw-normal list-link">
                            <li><a class="collapse-item" href="/monitoria">Monitoria</a></li>
                            <li><a class="collapse-item" href="/produto">Produto</a></li>
                            <li><a class="collapse-item" href="/solicitar">Solicitar Anúncio</a></li>
                        </ul>
                    </div>
                </div>
            </li>
            <li class="nav-item">
                <a class="collapsed nav-link-dropdown" href="#anuncios-collapse" data-bs-toggle="collapse" aria-expanded="false" aria-controls="anuncios-collapse">
                    <i class="fa-solid fa-newspaper"></i> &nbsp; Anúncios
                </a>
                <div id="anuncios-collapse" class="collapse">
                    <div class="collapse-inner">
                        <ul class="btn-toggle-nav list-unstyled fw-normal list-link">
                            <li><a class="collapse-item" href="/monitorias">Monitorias</a></li>
                            <li><a class="collapse-item" href="/produtos">Produtos</a></li>
                            <li><a class="collapse-item" href="/solicitados">Solicitados</a></li>
                        </ul>
                    </div>
                </div>
            </li>
            <li class="nav-item">
                <a class="collapsed nav-link-dropdown" href="#sobre-collapse" data-bs-toggle="collapse" aria-expanded="false" aria-controls="sobre-collapse">
                    <i class="fa-solid fa-address-card"></i> &nbsp; Sobre
                </a>
                <div id="sobre-collapse" class="collapse">
                    <div class="collapse-inner">
                        <ul class="btn-toggle-nav list-unstyled fw-normal list-link">
                            <li><a class="collapse-item" href="/faleconosco">Fale Conosco</a></li>
                            <li><a class="collapse-item" href="/politicas">Políticas</a></li>
                            <?php if (false) { ?>
                                <li><a class="collapse-item" href="/equipe">Equipe</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
    </div>

    <main class="container-fluid">
        <?= $this->content() ?>
    </main>

    <footer class="fixed-bottom bg-vermelho">
        <p class="text-center mb-0 user-select-none">Classificados Fatec</p>
    </footer>

    <script>
        $(document).ready(function() {
            const page = window.location.pathname;

            if (page) {
                $(`li a[href="${page}"]`).addClass('active');
            }
        });
    </script>
</body>

</html>