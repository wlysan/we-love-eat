<?php $title = 'Admin Dashboard - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col">
            <h2>Painel Administrativo</h2>
            <p class="text-muted">Bem-vindo ao painel administrativo do <?= APP_NAME ?>.</p>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm h-100 card-dashboard">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Usuários</h6>
                            <h3 class="mb-0"><?= $userCount ?></h3>
                        </div>
                        <div class="bg-light p-3 rounded">
                            <i class="fas fa-users text-primary"></i>
                        </div>
                    </div>
                    <p class="mt-3 mb-0">Clientes cadastrados no sistema</p>
                </div>
                <div class="card-footer bg-white border-0">
                    <a href="/admin/users" class="btn btn-sm btn-outline-primary">Gerenciar Usuários</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm h-100 card-dashboard">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Nutricionistas</h6>
                            <h3 class="mb-0"><?= $nutritionistCount ?></h3>
                        </div>
                        <div class="bg-light p-3 rounded">
                            <i class="fas fa-user-md text-primary"></i>
                        </div>
                    </div>
                    <p class="mt-3 mb-0">Nutricionistas cadastrados no sistema</p>
                </div>
                <div class="card-footer bg-white border-0">
                    <a href="/admin/nutritionists" class="btn btn-sm btn-outline-primary">Gerenciar Nutricionistas</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm h-100 card-dashboard">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Restaurantes</h6>
                            <h3 class="mb-0"><?= $restaurantCount ?></h3>
                        </div>
                        <div class="bg-light p-3 rounded">
                            <i class="fas fa-utensils text-primary"></i>
                        </div>
                    </div>
                    <p class="mt-3 mb-0">Restaurantes parceiros cadastrados</p>
                </div>
                <div class="card-footer bg-white border-0">
                    <a href="/admin/restaurants" class="btn btn-sm btn-outline-primary">Gerenciar Restaurantes</a>
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
                            <a href="/admin/nutritionists/create" class="btn btn-primary w-100">
                                <i class="fas fa-plus-circle me-2"></i> Adicionar Nutricionista
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="/admin/restaurants/create" class="btn btn-primary w-100">
                                <i class="fas fa-plus-circle me-2"></i> Adicionar Restaurante
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="/admin/users/create" class="btn btn-primary w-100">
                                <i class="fas fa-plus-circle me-2"></i> Adicionar Usuário
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>