<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? APP_NAME ?></title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <div class="container">
            <a class="navbar-brand" href="/"><?= APP_NAME ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <?php if (isset($currentUser)): ?>
                    <?php if ($currentUser['role'] === 'user'): ?>
                        <!-- User Navigation -->
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link <?= getCurrentRoute() === '/dashboard' ? 'active' : '' ?>" href="/dashboard">
                                    Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= getCurrentRoute() === '/diets' ? 'active' : '' ?>" href="/diets">
                                    Minhas Dietas
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= getCurrentRoute() === '/nutritionists' ? 'active' : '' ?>" href="/nutritionists">
                                    Nutricionistas
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= getCurrentRoute() === '/chats' ? 'active' : '' ?>" href="/chats">
                                    Conversas
                                </a>
                            </li>
                        </ul>
                    <?php elseif ($currentUser['role'] === 'nutritionist'): ?>
                        <!-- Nutritionist Navigation -->
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link <?= getCurrentRoute() === '/nutritionist/dashboard' ? 'active' : '' ?>" href="/nutritionist/dashboard">
                                    Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= getCurrentRoute() === '/nutritionist/clients' ? 'active' : '' ?>" href="/nutritionist/clients">
                                    Clientes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= getCurrentRoute() === '/nutritionist/diets' ? 'active' : '' ?>" href="/nutritionist/diets">
                                    Dietas
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= getCurrentRoute() === '/chats' ? 'active' : '' ?>" href="/chats">
                                    Conversas
                                </a>
                            </li>
                        </ul>
                    <?php elseif ($currentUser['role'] === 'restaurant'): ?>
                        <!-- Restaurant Navigation -->
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link <?= getCurrentRoute() === '/restaurant/dashboard' ? 'active' : '' ?>" href="/restaurant/dashboard">
                                    Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= getCurrentRoute() === '/restaurant/meals' ? 'active' : '' ?>" href="/restaurant/meals">
                                    Refeições
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= getCurrentRoute() === '/restaurant/ingredients' ? 'active' : '' ?>" href="/restaurant/ingredients">
                                    Ingredientes
                                </a>
                            </li>
                        </ul>
                    <?php elseif ($currentUser['role'] === 'admin'): ?>
                        <!-- Admin Navigation -->
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link <?= getCurrentRoute() === '/admin/dashboard' ? 'active' : '' ?>" href="/admin/dashboard">
                                    Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= getCurrentRoute() === '/admin/users' ? 'active' : '' ?>" href="/admin/users">
                                    Usuários
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= getCurrentRoute() === '/admin/nutritionists' ? 'active' : '' ?>" href="/admin/nutritionists">
                                    Nutricionistas
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= getCurrentRoute() === '/admin/restaurants' ? 'active' : '' ?>" href="/admin/restaurants">
                                    Restaurantes
                                </a>
                            </li>
                        </ul>
                    <?php endif; ?>
                    
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i> <?= $currentUser['name'] ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="/profile"><i class="fas fa-user me-2"></i> Meu Perfil</a></li>
                                <li><a class="dropdown-item" href="/profile/measurements"><i class="fas fa-weight me-2"></i> Medidas</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/logout"><i class="fas fa-sign-out-alt me-2"></i> Sair</a></li>
                            </ul>
                        </li>
                    </ul>
                <?php else: ?>
                    <!-- Public Navigation -->
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link <?= getCurrentRoute() === '/' ? 'active' : '' ?>" href="/">Início</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link <?= getCurrentRoute() === '/login' ? 'active' : '' ?>" href="/login">Entrar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= getCurrentRoute() === '/register' ? 'active' : '' ?>" href="/register">Cadastrar</a>
                        </li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <?= $content ?>
    </div>

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><?= APP_NAME ?></h5>
                    <p>Soluções nutricionais personalizadas para você</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; <?= date('Y') ?> <?= APP_NAME ?>. Todos os direitos reservados.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="/js/main.js"></script>
</body>
</html>