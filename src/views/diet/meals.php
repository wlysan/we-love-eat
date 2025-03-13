<?php $title = 'Selecionar Refeições - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Selecionar Refeições</h2>
            <p class="text-muted">
                Selecione as refeições para <?= Formatter::formatDate($date) ?> (<?= Formatter::getDayOfWeekName($dayOfWeek) ?>)
            </p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="/diets" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i> Voltar
            </a>
            <div class="btn-group">
                <a href="/diets/meals?date=<?= date('Y-m-d', strtotime('-1 day', strtotime($date))) ?>" class="btn btn-outline-primary">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <button type="button" class="btn btn-outline-primary" id="datepicker" data-date="<?= $date ?>">
                    <?= Formatter::formatDate($date) ?>
                </button>
                <a href="/diets/meals?date=<?= date('Y-m-d', strtotime('+1 day', strtotime($date))) ?>" class="btn btn-outline-primary">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <?php if (empty($dietMeals)): ?>
                        <div class="alert alert-info">
                            <h5><i class="fas fa-info-circle me-2"></i> Nenhuma refeição programada</h5>
                            <p class="mb-0">
                                Não há refeições programadas para este dia no seu plano de dieta atual.
                                Por favor, selecione outro dia ou entre em contato com seu nutricionista para ajustar seu plano.
                            </p>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($dietMeals as $dietMeal): ?>
                                <?php
                                $meal = $dietMeal['diet_meal'];
                                $plan = $dietMeal['plan'];
                                
                                // Find selected meal for this diet meal and date, if any
                                $selectedMeal = null;
                                foreach ($selections as $selection) {
                                    if ($selection['diet_meal_id'] == $meal['id']) {
                                        $selectedMeal = $selection;
                                        break;
                                    }
                                }
                                ?>
                                
                                <div class="col-md-6 mb-4">
                                    <div class="card meal-item <?= strtolower($meal['meal_type']) ?>">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">
                                                <?= Formatter::getMealTypeName($meal['meal_type']) ?>
                                                <span class="text-muted ms-2 small"><?= Formatter::formatTime($meal['time_of_day']) ?></span>
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-3">
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
                                                        <div class="p-2 bg-light rounded small">
                                                            <?= $meal['notes'] ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <?php if ($selectedMeal): ?>
                                                <div class="alert alert-success mb-3">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h6 class="mb-0"><?= $selectedMeal['meal_name'] ?></h6>
                                                            <p class="mb-0 small"><?= $selectedMeal['restaurant_name'] ?></p>
                                                        </div>
                                                        <div>
                                                            <?php if ($selectedMeal['status'] === 'consumed'): ?>
                                                                <span class="badge bg-success"><i class="fas fa-check me-1"></i> Consumido</span>
                                                            <?php elseif ($selectedMeal['status'] === 'skipped'): ?>
                                                                <span class="badge bg-danger"><i class="fas fa-times me-1"></i> Pulado</span>
                                                            <?php else: ?>
                                                                <div class="btn-group btn-group-sm">
                                                                    <a href="/diets/update-status?id=<?= $selectedMeal['id'] ?>&status=consumed&date=<?= $date ?>" class="btn btn-success">
                                                                        <i class="fas fa-check"></i> Consumido
                                                                    </a>
                                                                    <a href="/diets/update-status?id=<?= $selectedMeal['id'] ?>&status=skipped&date=<?= $date ?>" class="btn btn-danger">
                                                                        <i class="fas fa-times"></i> Pular
                                                                    </a>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <form method="POST" action="/diets/select-meal">
                                                <input type="hidden" name="diet_meal_id" value="<?= $meal['id'] ?>">
                                                <input type="hidden" name="date" value="<?= $date ?>">
                                                
                                                <div class="mb-3">
                                                    <label for="meal_<?= $meal['id'] ?>" class="form-label">Selecione uma refeição</label>
                                                    <select class="form-select meal-select" id="meal_<?= $meal['id'] ?>" name="meal_id" data-diet-meal-id="<?= $meal['id'] ?>" data-date="<?= $date ?>">
                                                        <option value="">-- Selecione uma refeição --</option>
                                                        <?php foreach ($availableMeals as $availableMeal): ?>
                                                            <?php if ($availableMeal['meal_type'] === $meal['meal_type']): ?>
                                                                <option value="<?= $availableMeal['id'] ?>" <?= ($selectedMeal && $selectedMeal['meal_id'] == $availableMeal['id']) ? 'selected' : '' ?>>
                                                                    <?= $availableMeal['name'] ?> - <?= $availableMeal['restaurant_name'] ?>
                                                                </option>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                
                                                <div id="nutrition-<?= $meal['id'] ?>">
                                                    <?php if ($selectedMeal): ?>
                                                        <!-- This would be populated dynamically with JavaScript -->
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <?php if (!$selectedMeal): ?>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-check me-1"></i> Selecionar Refeição
                                                    </button>
                                                <?php else: ?>
                                                    <button type="submit" class="btn btn-outline-primary">
                                                        <i class="fas fa-exchange-alt me-1"></i> Trocar Refeição
                                                    </button>
                                                <?php endif; ?>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Date picker modal -->
<div class="modal fade" id="datePickerModal" tabindex="-1" aria-labelledby="datePickerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="datePickerModalLabel">Selecionar Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="date" class="form-control" id="datepickerInput" value="<?= $date ?>">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="goToDate">Ir para Data</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Date picker functionality
    const datepicker = document.getElementById('datepicker');
    const datePickerModal = new bootstrap.Modal(document.getElementById('datePickerModal'));
    const datepickerInput = document.getElementById('datepickerInput');
    const goToDateBtn = document.getElementById('goToDate');
    
    datepicker.addEventListener('click', function() {
        datePickerModal.show();
    });
    
    goToDateBtn.addEventListener('click', function() {
        const selectedDate = datepickerInput.value;
        if (selectedDate) {
            window.location.href = `/diets/meals?date=${selectedDate}`;
        }
        datePickerModal.hide();
    });
    
    // Meal selection functionality - will be handled by main.js
});
</script>