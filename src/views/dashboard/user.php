<?php $title = 'Dashboard - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col">
            <h2>Meu Dashboard</h2>
            <p class="text-muted">Bem-vindo(a) de volta, <?= $user['name'] ?>!</p>
        </div>
    </div>
    
    <?php if (empty($profile) || empty($profile['current_weight']) || empty($profile['height'])): ?>
        <div class="alert alert-warning">
            <h5><i class="fas fa-exclamation-triangle me-2"></i> Complete seu perfil</h5>
            <p class="mb-0">
                Para receber recomendações nutricionais adequadas, complete seu perfil com suas informações de saúde.
            </p>
            <a href="/profile/edit" class="btn btn-sm btn-warning mt-2">Completar Perfil</a>
        </div>
    <?php endif; ?>
    
    <!-- Health Metrics -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Visão Geral da Saúde</h5>
                        <a href="/profile/measurements/add" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus-circle me-1"></i> Nova Medida
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php if (!empty($profile) && !empty($profile['current_weight'])): ?>
                            <div class="col-md-3 mb-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted mb-2">Peso Atual</h6>
                                        <h4><?= Formatter::formatWeight($profile['current_weight']) ?></h4>
                                        
                                        <?php if (!empty($progress['weight_change'])): ?>
                                            <span class="badge <?= $progress['weight_change'] < 0 ? 'bg-success' : 'bg-danger' ?>">
                                                <?= $progress['weight_change'] < 0 ? '' : '+' ?><?= Formatter::formatWeight($progress['weight_change']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($profile) && !empty($profile['goal_weight'])): ?>
                            <div class="col-md-3 mb-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted mb-2">Peso Meta</h6>
                                        <h4><?= Formatter::formatWeight($profile['goal_weight']) ?></h4>
                                        
                                        <?php if (!empty($profile['current_weight'])): ?>
                                            <span class="badge bg-primary">
                                                <?= Formatter::formatWeight(abs($profile['goal_weight'] - $profile['current_weight'])) ?> restantes
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($measurements) && !empty($measurements[0]['body_fat_percentage'])): ?>
                            <div class="col-md-3 mb-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted mb-2">Gordura Corporal</h6>
                                        <h4><?= Formatter::formatPercentage($measurements[0]['body_fat_percentage']) ?></h4>
                                        
                                        <?php if (!empty($progress['body_fat_change'])): ?>
                                            <span class="badge <?= $progress['body_fat_change'] < 0 ? 'bg-success' : 'bg-danger' ?>">
                                                <?= $progress['body_fat_change'] < 0 ? '' : '+' ?><?= Formatter::formatPercentage($progress['body_fat_change']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($measurements) && !empty($measurements[0]['waist'])): ?>
                            <div class="col-md-3 mb-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted mb-2">Circunferência Abdominal</h6>
                                        <h4><?= Formatter::formatHeight($measurements[0]['waist']) ?></h4>
                                        
                                        <?php if (!empty($progress['waist_change'])): ?>
                                            <span class="badge <?= $progress['waist_change'] < 0 ? 'bg-success' : 'bg-danger' ?>">
                                                <?= $progress['waist_change'] < 0 ? '' : '+' ?><?= Formatter::formatHeight($progress['waist_change']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (!empty($measurements)): ?>
                        <div class="mt-4">
                            <h6>Histórico de Peso</h6>
                            <div style="height: 200px;">
                                <canvas id="weightChart"></canvas>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-white">
                    <a href="/profile/measurements" class="btn btn-sm btn-outline-primary">Ver Histórico Completo</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Diet Plans -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Meus Planos de Dieta</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($dietPlans)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-utensils fa-3x text-muted mb-3"></i>
                            <p>Você ainda não tem planos de dieta ativos.</p>
                            <a href="/nutritionists" class="btn btn-sm btn-primary">Encontrar Nutricionista</a>
                        </div>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($dietPlans as $plan): ?>
                                <a href="/diets/view?id=<?= $plan['id'] ?>" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?= $plan['name'] ?></h6>
                                        <small><?= Formatter::formatDate($plan['start_date']) ?> - <?= Formatter::formatDate($plan['end_date']) ?></small>
                                    </div>
                                    <p class="mb-1 text-muted small">Por: <?= $plan['nutritionist_name'] ?></p>
                                    <small>
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
                <div class="card-footer bg-white">
                    <a href="/diets" class="btn btn-sm btn-outline-primary">Ver Todos os Planos</a>
                </div>
            </div>
        </div>
        
        <!-- Today's Meals -->
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Refeições de Hoje (<?= Formatter::formatDate(date('Y-m-d')) ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($todaysMeals)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-day fa-3x text-muted mb-3"></i>
                            <p>Você ainda não selecionou refeições para hoje.</p>
                            <?php if (!empty($dietPlans)): ?>
                                <a href="/diets/meals?date=<?= date('Y-m-d') ?>" class="btn btn-sm btn-primary">Selecionar Refeições</a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($todaysMeals as $meal): ?>
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1"><?= Formatter::getMealTypeName($meal['meal_type']) ?> - <?= Formatter::formatTime($meal['time_of_day']) ?></h6>
                                            <p class="mb-1"><?= $meal['meal_name'] ?> <span class="text-muted small">(<?= $meal['restaurant_name'] ?>)</span></p>
                                        </div>
                                        <div>
                                            <?php if ($meal['status'] === 'consumed'): ?>
                                                <span class="badge bg-success"><i class="fas fa-check me-1"></i> Consumido</span>
                                            <?php elseif ($meal['status'] === 'skipped'): ?>
                                                <span class="badge bg-danger"><i class="fas fa-times me-1"></i> Pulado</span>
                                            <?php else: ?>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="/diets/update-status?id=<?= $meal['id'] ?>&status=consumed" class="btn btn-outline-success">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                    <a href="/diets/update-status?id=<?= $meal['id'] ?>&status=skipped" class="btn btn-outline-danger">
                                                        <i class="fas fa-times"></i>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-white">
                    <a href="/diets/meals?date=<?= date('Y-m-d') ?>" class="btn btn-sm btn-outline-primary">Gerenciar Refeições</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($measurements)): ?>
<!-- Chart.js for weight history -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Extract data for chart
    const dates = [
        <?php foreach (array_reverse(array_slice($measurements, 0, 10)) as $m): ?>
            '<?= Formatter::formatDate($m['date']) ?>',
        <?php endforeach; ?>
    ];
    
    const weights = [
        <?php foreach (array_reverse(array_slice($measurements, 0, 10)) as $m): ?>
            <?= $m['weight'] ?? 'null' ?>,
        <?php endforeach; ?>
    ];
    
    // Create chart
    const ctx = document.getElementById('weightChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Peso (kg)',
                data: weights,
                fill: false,
                borderColor: '#00a651',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: false
                }
            }
        }
    });
});
</script>
<?php endif; ?>