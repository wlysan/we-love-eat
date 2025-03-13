<?php $title = 'Dashboard Restaurante - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col">
            <h2>Dashboard do Restaurante</h2>
            <p class="text-muted">Bem-vindo(a), <?= $restaurant['name'] ?>!</p>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm h-100 card-dashboard">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Refeições</h6>
                            <h3 class="mb-0"><?= count($meals) ?></h3>
                        </div>
                        <div class="bg-light p-3 rounded">
                            <i class="fas fa-utensils text-primary"></i>
                        </div>
                    </div>
                    <p class="mt-3 mb-0">Refeições disponíveis em seu cardápio</p>
                </div>
                <div class="card-footer bg-white border-0">
                    <a href="/restaurant/meals" class="btn btn-sm btn-outline-primary">Ver Refeições</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm h-100 card-dashboard">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Popularidade</h6>
                            <h3 class="mb-0">--</h3>
                        </div>
                        <div class="bg-light p-3 rounded">
                            <i class="fas fa-chart-line text-primary"></i>
                        </div>
                    </div>
                    <p class="mt-3 mb-0">Taxa de escolha de suas refeições</p>
                </div>
                <div class="card-footer bg-white border-0">
                    <button class="btn btn-sm btn-outline-primary" disabled>Em Breve</button>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm h-100 card-dashboard">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Nutricionistas</h6>
                            <h3 class="mb-0">--</h3>
                        </div>
                        <div class="bg-light p-3 rounded">
                            <i class="fas fa-user-md text-primary"></i>
                        </div>
                    </div>
                    <p class="mt-3 mb-0">Nutricionistas que recomendam seus pratos</p>
                </div>
                <div class="card-footer bg-white border-0">
                    <button class="btn btn-sm btn-outline-primary" disabled>Em Breve</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Refeições Recentes</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($meals)): ?>
                        <p class="text-center text-muted my-4">Nenhuma refeição cadastrada ainda.</p>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach (array_slice($meals, 0, 5) as $meal): ?>
                                <a href="/restaurant/meals/view?id=<?= $meal['id'] ?>" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?= $meal['name'] ?></h6>
                                        <small class="text-<?= $meal['available'] ? 'success' : 'danger' ?>">
                                            <?= $meal['available'] ? 'Disponível' : 'Indisponível' ?>
                                        </small>
                                    </div>
                                    <p class="mb-1 text-muted small"><?= Formatter::formatCurrency($meal['price']) ?> - <?= Formatter::getMealTypeName($meal['meal_type']) ?></p>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-white">
                    <a href="/restaurant/meals" class="btn btn-sm btn-outline-primary">Ver Todas as Refeições</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Refeições Populares</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($popularMeals)): ?>
                        <p class="text-center text-muted my-4">Dados de popularidade ainda não disponíveis.</p>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($popularMeals as $meal): ?>
                                <a href="/restaurant/meals/view?id=<?= $meal['id'] ?>" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?= $meal['name'] ?></h6>
                                        <span class="badge bg-primary"><?= $meal['selection_count'] ?> seleções</span>
                                    </div>
                                    <p class="mb-1 text-muted small"><?= Formatter::formatCurrency($meal['price']) ?> - <?= Formatter::getMealTypeName($meal['meal_type']) ?></p>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-white">
                    <button class="btn btn-sm btn-outline-primary" disabled>Mais Estatísticas em Breve</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Ações Rápidas</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <a href="/restaurant/meals/create" class="btn btn-primary w-100">
                                <i class="fas fa-plus-circle me-2"></i> Adicionar Nova Refeição
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="/restaurant/ingredients" class="btn btn-primary w-100">
                                <i class="fas fa-list me-2"></i> Gerenciar Ingredientes
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="/profile" class="btn btn-primary w-100">
                                <i class="fas fa-user-edit me-2"></i> Editar Perfil do Restaurante
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>