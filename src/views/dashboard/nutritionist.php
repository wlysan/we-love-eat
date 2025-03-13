<?php $title = 'Dashboard Nutricionista - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col">
            <h2>Dashboard do Nutricionista</h2>
            <p class="text-muted">Bem-vindo(a), <?= $nutritionist['name'] ?>!</p>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm h-100 card-dashboard">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Clientes</h6>
                            <h3 class="mb-0"><?= count($clients) ?></h3>
                        </div>
                        <div class="bg-light p-3 rounded">
                            <i class="fas fa-users text-primary"></i>
                        </div>
                    </div>
                    <p class="mt-3 mb-0">Clientes atendidos atualmente</p>
                </div>
                <div class="card-footer bg-white border-0">
                    <a href="/nutritionist/clients" class="btn btn-sm btn-outline-primary">Ver Clientes</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm h-100 card-dashboard">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Planos Ativos</h6>
                            <h3 class="mb-0"><?= count(array_filter($dietPlans, function($plan) { return $plan['status'] === 'active'; })) ?></h3>
                        </div>
                        <div class="bg-light p-3 rounded">
                            <i class="fas fa-clipboard-list text-primary"></i>
                        </div>
                    </div>
                    <p class="mt-3 mb-0">Planos de dieta atualmente ativos</p>
                </div>
                <div class="card-footer bg-white border-0">
                    <a href="/nutritionist/diets" class="btn btn-sm btn-outline-primary">Ver Planos</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm h-100 card-dashboard">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Mensagens</h6>
                            <h3 class="mb-0"><?= $unreadCount ?></h3>
                        </div>
                        <div class="bg-light p-3 rounded">
                            <i class="fas fa-comments text-primary"></i>
                        </div>
                    </div>
                    <p class="mt-3 mb-0">Mensagens não lidas de clientes</p>
                </div>
                <div class="card-footer bg-white border-0">
                    <a href="/chats" class="btn btn-sm btn-outline-primary">Ver Mensagens</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Clientes Recentes</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($clients)): ?>
                        <p class="text-center text-muted my-4">Nenhum cliente registrado ainda.</p>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach (array_slice($clients, 0, 5) as $client): ?>
                                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1"><?= $client['name'] ?></h6>
                                        <p class="text-muted mb-0 small"><?= $client['email'] ?></p>
                                    </div>
                                    <div class="btn-group btn-group-sm">
                                        <?php if (!empty($client['diet_plan_id'])): ?>
                                            <a href="/nutritionist/diets/view?id=<?= $client['diet_plan_id'] ?>" class="btn btn-outline-primary" title="Ver Dieta">
                                                <i class="fas fa-clipboard-list"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($client['chat_id'])): ?>
                                            <a href="/chats/view?id=<?= $client['chat_id'] ?>" class="btn btn-outline-primary" title="Ver Conversa">
                                                <i class="fas fa-comments"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-white">
                    <a href="/nutritionist/clients" class="btn btn-sm btn-outline-primary">Ver Todos os Clientes</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Planos de Dieta Recentes</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($dietPlans)): ?>
                        <p class="text-center text-muted my-4">Nenhum plano de dieta criado ainda.</p>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach (array_slice($dietPlans, 0, 5) as $plan): ?>
                                <a href="/nutritionist/diets/view?id=<?= $plan['id'] ?>" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?= $plan['name'] ?></h6>
                                        <small class="text-<?= $plan['status'] === 'active' ? 'success' : ($plan['status'] === 'completed' ? 'secondary' : 'danger') ?>">
                                            <?= $plan['status'] === 'active' ? 'Ativo' : ($plan['status'] === 'completed' ? 'Concluído' : 'Cancelado') ?>
                                        </small>
                                    </div>
                                    <p class="mb-1">Cliente: <?= $plan['client_name'] ?></p>
                                    <small class="text-muted">
                                        <?= Formatter::formatDate($plan['start_date']) ?> - <?= Formatter::formatDate($plan['end_date']) ?>
                                    </small>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-white">
                    <a href="/nutritionist/diets" class="btn btn-sm btn-outline-primary">Ver Todos os Planos</a>
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
                            <a href="/nutritionist/diets/create" class="btn btn-primary w-100">
                                <i class="fas fa-plus-circle me-2"></i> Criar Novo Plano de Dieta
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="/chats" class="btn btn-primary w-100">
                                <i class="fas fa-comments me-2"></i> Ver Mensagens
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="/profile" class="btn btn-primary w-100">
                                <i class="fas fa-user-edit me-2"></i> Editar Perfil Profissional
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>