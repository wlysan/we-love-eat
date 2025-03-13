<?php $title = 'Planos de Dieta - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Planos de Dieta</h2>
            <p class="text-muted">Gerencie os planos de dieta que você criou para seus clientes.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="/nutritionist/diets/create" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i> Novo Plano de Dieta
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <ul class="nav nav-tabs card-header-tabs" id="dietTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab" aria-controls="active" aria-selected="true">
                                Ativos
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab" aria-controls="completed" aria-selected="false">
                                Concluídos
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="canceled-tab" data-bs-toggle="tab" data-bs-target="#canceled" type="button" role="tab" aria-controls="canceled" aria-selected="false">
                                Cancelados
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="dietTabsContent">
                        <!-- Active Plans Tab -->
                        <div class="tab-pane fade show active" id="active" role="tabpanel" aria-labelledby="active-tab">
                            <?php 
                            $activePlans = array_filter($dietPlans, function($plan) {
                                return $plan['status'] === 'active';
                            });
                            ?>
                            
                            <?php if (empty($activePlans)): ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                    <h5>Nenhum plano de dieta ativo</h5>
                                    <p class="text-muted">Crie um novo plano de dieta para seus clientes.</p>
                                    <a href="/nutritionist/diets/create" class="btn btn-primary mt-2">
                                        <i class="fas fa-plus-circle me-2"></i> Novo Plano de Dieta
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Nome do Plano</th>
                                                <th>Cliente</th>
                                                <th>Período</th>
                                                <th>Calorias</th>
                                                <th>Criado em</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($activePlans as $plan): ?>
                                                <tr>
                                                    <td><?= $plan['name'] ?></td>
                                                    <td><?= $plan['client_name'] ?></td>
                                                    <td><?= Formatter::formatDate($plan['start_date']) ?> - <?= Formatter::formatDate($plan['end_date']) ?></td>
                                                    <td><?= $plan['daily_calories'] ? Formatter::formatCalories($plan['daily_calories']) : '-' ?></td>
                                                    <td><?= Formatter::formatDate($plan['created_at']) ?></td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <a href="/nutritionist/diets/view?id=<?= $plan['id'] ?>" class="btn btn-outline-primary" title="Ver Detalhes">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="/nutritionist/diets/edit?id=<?= $plan['id'] ?>" class="btn btn-outline-primary" title="Editar">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Completed Plans Tab -->
                        <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                            <?php 
                            $completedPlans = array_filter($dietPlans, function($plan) {
                                return $plan['status'] === 'completed';
                            });
                            ?>
                            
                            <?php if (empty($completedPlans)): ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                                    <h5>Nenhum plano de dieta concluído</h5>
                                    <p class="text-muted">Os planos concluídos aparecerão aqui.</p>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Nome do Plano</th>
                                                <th>Cliente</th>
                                                <th>Período</th>
                                                <th>Concluído em</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($completedPlans as $plan): ?>
                                                <tr>
                                                    <td><?= $plan['name'] ?></td>
                                                    <td><?= $plan['client_name'] ?></td>
                                                    <td><?= Formatter::formatDate($plan['start_date']) ?> - <?= Formatter::formatDate($plan['end_date']) ?></td>
                                                    <td><?= Formatter::formatDate($plan['updated_at']) ?></td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <a href="/nutritionist/diets/view?id=<?= $plan['id'] ?>" class="btn btn-outline-primary" title="Ver Detalhes">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Canceled Plans Tab -->
                        <div class="tab-pane fade" id="canceled" role="tabpanel" aria-labelledby="canceled-tab">
                            <?php 
                            $canceledPlans = array_filter($dietPlans, function($plan) {
                                return $plan['status'] === 'canceled';
                            });
                            ?>
                            
                            <?php if (empty($canceledPlans)): ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-times-circle fa-3x text-muted mb-3"></i>
                                    <h5>Nenhum plano de dieta cancelado</h5>
                                    <p class="text-muted">Os planos cancelados aparecerão aqui.</p>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Nome do Plano</th>
                                                <th>Cliente</th>
                                                <th>Criado em</th>
                                                <th>Cancelado em</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($canceledPlans as $plan): ?>
                                                <tr>
                                                    <td><?= $plan['name'] ?></td>
                                                    <td><?= $plan['client_name'] ?></td>
                                                    <td><?= Formatter::formatDate($plan['created_at']) ?></td>
                                                    <td><?= Formatter::formatDate($plan['updated_at']) ?></td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <a href="/nutritionist/diets/view?id=<?= $plan['id'] ?>" class="btn btn-outline-primary" title="Ver Detalhes">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>