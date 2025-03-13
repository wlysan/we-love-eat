<?php $title = 'Plano de Dieta - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><?= $plan['name'] ?></h2>
            <p class="text-muted">
                Plano elaborado por <strong><?= $plan['nutritionist_name'] ?></strong>
                de <?= Formatter::formatDate($plan['start_date']) ?> a <?= Formatter::formatDate($plan['end_date']) ?>
            </p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="/diets" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i> Voltar
            </a>
            <a href="/diets/meals?date=<?= date('Y-m-d') ?>" class="btn btn-primary">
                <i class="fas fa-utensils me-1"></i> Selecionar Refeições
            </a>
        </div>
    </div>
    
    <div class="row mb-4">
        <!-- Nutrition targets -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Metas Nutricionais Diárias</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <i class="fas fa-fire text-danger me-1"></i> Calorias
                        </div>
                        <div class="fw-bold"><?= Formatter::formatCalories($plan['daily_calories']) ?></div>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <i class="fas fa-drumstick-bite text-success me-1"></i> Proteínas
                        </div>
                        <div class="fw-bold"><?= Formatter::formatNutrient($plan['daily_protein']) ?></div>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <i class="fas fa-bread-slice text-warning me-1"></i> Carboidratos
                        </div>
                        <div class="fw-bold"><?= Formatter::formatNutrient($plan['daily_carbs']) ?></div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>
                            <i class="fas fa-cheese text-primary me-1"></i> Gorduras
                        </div>
                        <div class="fw-bold"><?= Formatter::formatNutrient($plan['daily_fat']) ?></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Progress -->
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Progresso</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Adesão ao Plano</label>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar" role="progressbar" style="width: <?= $compliance['compliance_rate'] ?>%;" aria-valuenow="<?= $compliance['compliance_rate'] ?>" aria-valuemin="0" aria-valuemax="100">
                                    <?= number_format($compliance['compliance_rate'], 1, ',', '.') ?>%
                                </div>
                            </div>
                            <div class="small text-muted mt-1">
                                <?= $compliance['consumed_meals'] ?> de <?= $compliance['total_meals'] ?> refeições consumidas
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Calorias</label>
                            <div class="progress" style="height: 25px;">
                                <?php 
                                $caloriesPercent = min(100, max(0, $compliance['calories_compliance']));
                                $caloriesClass = $caloriesPercent < 80 ? 'bg-warning' : 'bg-success';
                                if ($caloriesPercent > 110) $caloriesClass = 'bg-danger';
                                ?>
                                <div class="progress-bar <?= $caloriesClass ?>" role="progressbar" style="width: <?= $caloriesPercent ?>%;" aria-valuenow="<?= $caloriesPercent ?>" aria-valuemin="0" aria-valuemax="100">
                                    <?= number_format($compliance['calories_compliance'], 1, ',', '.') ?>%
                                </div>
                            </div>
                            <div class="small text-muted mt-1">
                                <?= Formatter::formatCalories($compliance['total_calories']) ?> de <?= Formatter::formatCalories($compliance['daily_calories_target'] * 30) ?> (mês)
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Proteínas</label>
                            <div class="progress" style="height: 20px;">
                                <?php 
                                $proteinPercent = min(100, max(0, $compliance['protein_compliance']));
                                $proteinClass = $proteinPercent < 80 ? 'bg-warning' : 'bg-success';
                                ?>
                                <div class="progress-bar <?= $proteinClass ?>" role="progressbar" style="width: <?= $proteinPercent ?>%;" aria-valuenow="<?= $proteinPercent ?>" aria-valuemin="0" aria-valuemax="100">
                                    <?= number_format($compliance['protein_compliance'], 1, ',', '.') ?>%
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Carboidratos</label>
                            <div class="progress" style="height: 20px;">
                                <?php 
                                $carbsPercent = min(100, max(0, $compliance['carbs_compliance']));
                                $carbsClass = $carbsPercent < 80 || $carbsPercent > 110 ? 'bg-warning' : 'bg-success';
                                if ($carbsPercent > 120) $carbsClass = 'bg-danger';
                                ?>
                                <div class="progress-bar <?= $carbsClass ?>" role="progressbar" style="width: <?= $carbsPercent ?>%;" aria-valuenow="<?= $carbsPercent ?>" aria-valuemin="0" aria-valuemax="100">
                                    <?= number_format($compliance['carbs_compliance'], 1, ',', '.') ?>%
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Gorduras</label>
                            <div class="progress" style="height: 20px;">
                                <?php 
                                $fatPercent = min(100, max(0, $compliance['fat_compliance']));
                                $fatClass = $fatPercent < 80 || $fatPercent > 110 ? 'bg-warning' : 'bg-success';
                                if ($fatPercent > 120) $fatClass = 'bg-danger';
                                ?>
                                <div class="progress-bar <?= $fatClass ?>" role="progressbar" style="width: <?= $fatPercent ?>%;" aria-valuenow="<?= $fatPercent ?>" aria-valuemin="0" aria-valuemax="100">
                                    <?= number_format($compliance['fat_compliance'], 1, ',', '.') ?>%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Meal plan details -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Detalhes do Plano</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($plan['notes'])): ?>
                        <div class="mb-4">
                            <h6>Observações do Nutricionista</h6>
                            <div class="p-3 bg-light rounded">
                                <?= nl2br(htmlspecialchars($plan['notes'])) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <h6>Refeições Recomendadas</h6>
                    
                    <div class="row">
                        <?php
                        $dayNames = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
                        $mealsByDay = [];
                        
                        // Group meals by day
                        foreach ($meals as $meal) {
                            $dayOfWeek = $meal['day_of_week'];
                            if (!isset($mealsByDay[$dayOfWeek])) {
                                $mealsByDay[$dayOfWeek] = [];
                            }
                            $mealsByDay[$dayOfWeek][] = $meal;
                        }
                        
                        // Sort by day of week and time of day
                        ksort($mealsByDay);
                        foreach ($mealsByDay as &$dayMeals) {
                            usort($dayMeals, function($a, $b) {
                                return strtotime($a['time_of_day']) - strtotime($b['time_of_day']);
                            });
                        }
                        ?>
                        
                        <div class="col-12">
                            <ul class="nav nav-tabs" id="daysTab" role="tablist">
                                <?php foreach ($mealsByDay as $day => $dayMeals): ?>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link <?= $day == date('w') ? 'active' : '' ?>" id="day-<?= $day ?>-tab" data-bs-toggle="tab" data-bs-target="#day-<?= $day ?>" type="button" role="tab" aria-controls="day-<?= $day ?>" aria-selected="<?= $day == date('w') ? 'true' : 'false' ?>">
                                            <?= $dayNames[$day] ?>
                                        </button>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            
                            <div class="tab-content mt-3" id="daysTabContent">
                                <?php foreach ($mealsByDay as $day => $dayMeals): ?>
                                    <div class="tab-pane fade <?= $day == date('w') ? 'show active' : '' ?>" id="day-<?= $day ?>" role="tabpanel" aria-labelledby="day-<?= $day ?>-tab">
                                        <div class="list-group">
                                            <?php foreach ($dayMeals as $meal): ?>
                                                <div class="list-group-item list-group-item-action meal-item <?= strtolower($meal['meal_type']) ?>">
                                                    <div class="row align-items-center">
                                                        <div class="col-md-3">
                                                            <h6 class="mb-0"><?= Formatter::getMealTypeName($meal['meal_type']) ?></h6>
                                                            <p class="text-muted mb-0 small"><?= Formatter::formatTime($meal['time_of_day']) ?></p>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <p class="mb-1"><strong>Calorias:</strong> <?= $meal['calories_target'] ? Formatter::formatCalories($meal['calories_target']) : '-' ?></p>
                                                                    <p class="mb-1"><strong>Proteínas:</strong> <?= $meal['protein_target'] ? Formatter::formatNutrient($meal['protein_target']) : '-' ?></p>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <p class="mb-1"><strong>Carboidratos:</strong> <?= $meal['carbs_target'] ? Formatter::formatNutrient($meal['carbs_target']) : '-' ?></p>
                                                                    <p class="mb-1"><strong>Gorduras:</strong> <?= $meal['fat_target'] ? Formatter::formatNutrient($meal['fat_target']) : '-' ?></p>
                                                                </div>
                                                                <?php if (!empty($meal['notes'])): ?>
                                                                    <div class="col-12 mt-2">
                                                                        <p class="mb-0 small fst-italic"><?= $meal['notes'] ?></p>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <a href="/diets/meals?date=<?= date('Y-m-d') ?>" class="btn btn-primary">
                        <i class="fas fa-utensils me-1"></i> Selecionar Refeições de Hoje
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>