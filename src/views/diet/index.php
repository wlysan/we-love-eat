<?php $title = 'Minhas Dietas - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col">
            <h2>Meus Planos de Dieta</h2>
            <p class="text-muted">Acompanhe seus planos de dieta personalizados.</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <?php if (empty($dietPlans)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-utensils fa-3x text-muted mb-3"></i>
                            <h5>Nenhum plano de dieta encontrado</h5>
                            <p class="text-muted">Entre em contato com um nutricionista para criar seu plano de dieta personalizado.</p>
                            <a href="/nutritionists" class="btn btn-primary mt-2">
                                <i class="fas fa-search me-2"></i> Encontrar Nutricionistas
                            </a>
                        </div>
                    <?php else: ?>
                        <ul class="nav nav-tabs mb-4" id="dietTabs" role="tablist">
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
                        
                        <div class="tab-content" id="dietTabsContent">
                            <div class="tab-pane fade show active" id="active" role="tabpanel" aria-labelledby="active-tab">
                                <?php 
                                $activePlans = array_filter($dietPlans, function($plan) {
                                    return $plan['status'] === 'active';
                                });
                                ?>
                                
                                <?php if (empty($activePlans)): ?>
                                    <p class="text-center text-muted my-4">Você não possui planos de dieta ativos no momento.</p>
                                <?php else: ?>
                                    <div class="row row-cols-1 row-cols-md-2 g-4">
                                        <?php foreach ($activePlans as $plan): ?>
                                            <div class="col">
                                                <div class="card h-100">
                                                    <div class="card-body">
                                                        <h5 class="card-title"><?= $plan['name'] ?></h5>
                                                        <h6 class="card-subtitle mb-2 text-muted">Nutricionista: <?= $plan['nutritionist_name'] ?></h6>
                                                        <p class="card-text">
                                                            <i class="far fa-calendar-alt me-1"></i> <?= Formatter::formatDate($plan['start_date']) ?> - <?= Formatter::formatDate($plan['end_date']) ?>
                                                        </p>
                                                        <p class="card-text">
                                                            <i class="fas fa-fire me-1"></i> <?= Formatter::formatCalories($plan['daily_calories']) ?>
                                                            <i class="fas fa-drumstick-bite ms-2 me-1"></i> <?= Formatter::formatNutrient($plan['daily_protein']) ?>
                                                            <i class="fas fa-bread-slice ms-2 me-1"></i> <?= Formatter::formatNutrient($plan['daily_carbs']) ?>
                                                            <i class="fas fa-cheese ms-2 me-1"></i> <?= Formatter::formatNutrient($plan['daily_fat']) ?>
                                                        </p>
                                                        
                                                        <?php if (!empty($plan['notes'])): ?>
                                                            <div class="card-text small">
                                                                <strong>Observações:</strong> <?= nl2br(htmlspecialchars($plan['notes'])) ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="card-footer bg-white">
                                                        <div class="btn-group w-100">
                                                            <a href="/diets/view?id=<?= $plan['id'] ?>" class="btn btn-outline-primary">
                                                                <i class="fas fa-eye me-1"></i> Detalhes
                                                            </a>
                                                            <a href="/diets/meals?date=<?= date('Y-m-d') ?>" class="btn btn-primary">
                                                                <i class="fas fa-utensils me-1"></i> Selecionar Refeições
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                                <?php 
                                $completedPlans = array_filter($dietPlans, function($plan) {
                                    return $plan['status'] === 'completed';
                                });
                                ?>
                                
                                <?php if (empty($completedPlans)): ?>
                                    <p class="text-center text-muted my-4">Você não possui planos de dieta concluídos.</p>
                                <?php else: ?>
                                    <div class="list-group">
                                        <?php foreach ($completedPlans as $plan): ?>
                                            <a href="/diets/view?id=<?= $plan['id'] ?>" class="list-group-item list-group-item-action">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h5 class="mb-1"><?= $plan['name'] ?></h5>
                                                    <span class="badge bg-success">Concluído</span>
                                                </div>
                                                <p class="mb-1">Nutricionista: <?= $plan['nutritionist_name'] ?></p>
                                                <p class="mb-1">
                                                    <i class="far fa-calendar-alt me-1"></i> <?= Formatter::formatDate($plan['start_date']) ?> - <?= Formatter::formatDate($plan['end_date']) ?>
                                                </p>
                                                <small class="text-muted">
                                                    <i class="fas fa-fire me-1"></i> <?= Formatter::formatCalories($plan['daily_calories']) ?>
                                                    <i class="fas fa-drumstick-bite ms-2 me-1"></i> <?= Formatter::formatNutrient($plan['daily_protein']) ?>
                                                    <i class="fas fa-bread-slice ms-2 me-1"></i> <?= Formatter::formatNutrient($plan['daily_carbs']) ?>
                                                    <i class="fas fa-cheese ms-2 me-1"></i> <?= Formatter::formatNutrient($plan['daily_fat']) ?>
                                                </small>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="tab-pane fade" id="canceled" role="tabpanel" aria-labelledby="canceled-tab">
                                <?php 
                                $canceledPlans = array_filter($dietPlans, function($plan) {
                                    return $plan['status'] === 'canceled';
                                });
                                ?>
                                
                                <?php if (empty($canceledPlans)): ?>
                                    <p class="text-center text-muted my-4">Você não possui planos de dieta cancelados.</p>
                                <?php else: ?>
                                    <div class="list-group">
                                        <?php foreach ($canceledPlans as $plan): ?>
                                            <a href="/diets/view?id=<?= $plan['id'] ?>" class="list-group-item list-group-item-action">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h5 class="mb-1"><?= $plan['name'] ?></h5>
                                                    <span class="badge bg-danger">Cancelado</span>
                                                </div>
                                                <p class="mb-1">Nutricionista: <?= $plan['nutritionist_name'] ?></p>
                                                <p class="mb-1">
                                                    <i class="far fa-calendar-alt me-1"></i> <?= Formatter::formatDate($plan['start_date']) ?> - <?= Formatter::formatDate($plan['end_date']) ?>
                                                </p>
                                                <small class="text-muted">
                                                    <i class="fas fa-fire me-1"></i> <?= Formatter::formatCalories($plan['daily_calories']) ?>
                                                    <i class="fas fa-drumstick-bite ms-2 me-1"></i> <?= Formatter::formatNutrient($plan['daily_protein']) ?>
                                                    <i class="fas fa-bread-slice ms-2 me-1"></i> <?= Formatter::formatNutrient($plan['daily_carbs']) ?>
                                                    <i class="fas fa-cheese ms-2 me-1"></i> <?= Formatter::formatNutrient($plan['daily_fat']) ?>
                                                </small>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>