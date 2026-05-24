<?php

require_once "backend/config/database.php";
session_start();

// MINI SEGURANÇA PARA EVITAR QUE ALUNOS ENTREM

$id = $_SESSION['id'];

$query = $conn->prepare(
    "SELECT cargo
    FROM tbl_usuarios
    WHERE id = ?"
);
$query->bind_param("i", $id);
$query->execute();

$result = $query->get_result();

$row = $result->fetch_assoc();

if(!isset($_SESSION['auth'])){
    $_SESSION['error'] = "Você não está logado.";
    header("Location: login.php");
    exit;
}

if($row['cargo'] < 2){
    $_SESSION['error'] = "Você não tem permissão para essa página!";
    header("Location: login.php");
    exit;
}

$user_name = $_SESSION['username'];
$user_profile = $_SESSION['user'];
$user_photo = $_SESSION['photo'];
$cargo = $_SESSION['cargo'];

?>

<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BioAlumnus - Criar Artigo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="frontend/styles/index.css?">
    <link rel="icon" href="frontend/assets/icons/icon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-flower1"></i>
                BioAlumnus
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="index.php">Início</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Sobre</a></li>
                    <li class="nav-item"><a class="nav-link" href="lupa.html">Lupa</a></li>
                </ul>
                <form class="d-flex" role="search">
                    <div class="input-group">
                        <input class="form-control" type="search" placeholder="Buscar na wiki..." aria-label="Buscar">
                        <button class="btn btn-verde" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>
                <div class="dropdown ms-3">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?php echo $user_photo; ?>" alt="Usuário" class="rounded-circle me-2" style="width: 38px; height: 38px; object-fit: cover; border: 2px solid var(--verde-mato);">
                        <span class="d-none d-lg-inline" style="color: var(--texto-principal);"><?php echo $user_profile; ?></span>
                    </a>
                    <?php if(isset($_SESSION['auth'])): ?>
                        <ul class="dropdown-menu dropdown-menu-end" style="background-color: var(--fundo-card); border: 1px solid var(--borda-sutil);">
                            <li><a class="dropdown-item" href="profile.php?user=<?php echo $user_profile; ?>" style="color: var(--texto-principal);"><i class="bi bi-person me-2"></i>Meu Perfil</a></li>
                            <li><a class="dropdown-item" href="#" style="color: var(--texto-principal);"><i class="bi bi-gear me-2"></i>Configurações</a></li>
                            <?php if($cargo > 1): ?>
                                <li><a class="dropdown-item" href="criar-artigo.php" style="color: var(--texto-principal);"><i class="bi bi-newspaper"></i> Criar artigo</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider" style="border-color: var(--borda-sutil);"></li>
                            <li><a class="dropdown-item" href="backend/src/quit.php" style="color: #dc3545;"><i class="bi bi-box-arrow-right me-2"></i>Sair</a></li>
                        </ul>
                    <?php else: ?>
                        <ul class="dropdown-menu dropdown-menu-end" style="background-color: var(--fundo-card); border: 1px solid var(--borda-sutil);">
                            <li><a class="dropdown-item" href="login.php" style="color: var(--texto-principal);"><i class="bi bi-person me-2"></i>Fazer login</a></li>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title"><i class="bi bi-flower1 me-2"></i>BioAlumnus</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0">
            <div class="p-3">
                
                <div class="d-flex align-items-center mb-3 p-2 rounded" style="background-color: rgba(45, 90, 39, 0.15); border: 1px solid var(--borda-sutil);">
                    <img src="<?php echo $user_photo; ?>" alt="Usuário" class="rounded-circle me-2" style="width: 42px; height: 42px; object-fit: cover; border: 2px solid var(--verde-mato);">
                    <a style="text-decoration: none;" href="profile.php?user=<?php echo $user_profile; ?>">
                        <div>
                            <?php if(isset($_SESSION['auth'])): ?>
                                <span class="d-block fw-semibold" style="color: var(--texto-principal);"><?php echo $user_name; ?></span>
                                <small style="color: var(--texto-secundario);"><?php echo $user_profile; ?></small>
                            <?php else: ?>
                                <a href="login.php"><span class="d-block fw-semibold" style="color: var(--texto-principal);">Fazer login</span></a>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
                <a href="backend/src/quit.php">
                    <button class="btn btn-danger opacity-75 col-12"><i class="bi bi-box-arrow-right me-2"></i>Sair</button>  
                </a>
                <hr>
                <input class="form-control mb-3" type="search" placeholder="Buscar na wiki...">
            </div>
            <p class="sidebar-title">Categorias</p>
            <ul class="sidebar-nav">
                <li><a href="artigos.php?theme=ecologia"><i class="bi bi-tree"></i>Ecologia</a></li>
            </ul>
            <p class="sidebar-title mt-4">Recursos</p>
            <ul class="sidebar-nav">
                <li><a href="#"><i class="bi bi-journal-text"></i>Artigos Recentes</a></li>
                <li><a href="#"><i class="bi bi-bookmark"></i>Salvos</a></li>
                <li><a href="#"><i class="bi bi-clock-history"></i>Histórico</a></li>
            </ul>
        </div>
    </div>

    <div class="container-fluid" style="margin-top: 76px;">
        <div class="row">
            <div class="col-lg-3 col-xl-2 d-none d-lg-block p-0 sidebar-col">
                <div class="sidebar">
                    <p class="sidebar-title">Ações</p>
                    <ul class="sidebar-nav">
                        <li><a href="artigos.php"><i class="bi bi-arrow-left"></i>Voltar aos Artigos</a></li>
                        <li><a href="meus-artigos.php"><i class="bi bi-file-text"></i>Artigos</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-9 col-xl-10 article-main-col">
                <main class="main-content" style="padding: 2rem;">
                    <div class="section-card">
                        <div class="d-flex align-items-center mb-4">
                            <div>
                                <h1 style="color: var(--verde-destaque); font-weight: 700; margin: 0;">Criar Novo Artigo</h1>
                                <p style="color: var(--texto-secundario); margin: 0.5rem 0 0 0;">Preencha as informações abaixo para publicar seu artigo</p>
                            </div>
                        </div>

                        <form action="backend/src/createArticle.php" method="POST" id="articleForm">
                            <!-- Título do Artigo -->
                            <div class="form-floating-label">

                                <label for="articleTitle"><i class="bi bi-type"></i> Título do Artigo</label>
                                <input type="text" name="articleTitle" id="articleTitle" placeholder="Digite o título do seu artigo" maxlength="200" >
                                <div class="char-counter"><span id="titleCount">0</span>/200 caracteres</div>
                            </div>

                            <!-- Descrição Resumida -->
                            <div class="form-floating-label">
                                <label for="articleDescription"><i class="bi bi-chat-left-text"></i> Descrição Resumida</label>
                                <textarea id="articleDescription" name="articleDescription" placeholder="Escreva uma breve descrição que será exibida na listagem de artigos..." rows="3" maxlength="200" ></textarea>
                                <div class="char-counter"><span id="descCount">0</span>/200 caracteres</div>
                            </div>

                            <!-- Editor de Conteúdo -->
                            <div class="form-floating-label">
                                <label><i class="bi bi-pencil"></i> Conteúdo do Artigo</label>

                                <textarea name="contentArticle" class="editor-content" id="articleContent" contenteditable="true" required></textarea>
                            </div>

                            <div class="form-floating-label">
                                <label><i class="bi bi-pencil"></i> Seleção de tema</label>
                                <select name="selectTheme" required>
                                    <?php
                                    
                                    $query = $conn->prepare(
                                        "SELECT
                                        id,
                                        name
                                        FROM tbl_interesses"
                                    );
                                    $query->execute();

                                    $result = $query->get_result();
                                    
                                    ?>
                                    <?php while($row = $result->fetch_assoc()): ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                                <small style="color: var(--texto-secundario); display: block; margin-top: 0.5rem;"><i class="bi bi-info-circle"></i> Selecione um tópico acima para especificar sobre o que se trata seu artigo.</small>
                            </div>

                            <!-- Ações Finais -->
                            <div class="actions-footer">
                                <button type="submit" class="btn btn-action btn-publish">
                                    <i class="bi bi-cloud-arrow-up"></i> Publicar Artigo
                                </button>
                            </div>
                        </form>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="backend/assets/js/criarArtigo.js"></script>
</body>
</html>
