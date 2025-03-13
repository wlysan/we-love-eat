<?php $title = 'Meus Pacotes de Refeições - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Planejar Pacotes de Refeições</h2>
            <p class="text-muted">Monte seus pacotes de refeições personalizados para diferentes períodos.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="/meals/catalog" class="btn btn-outline-secondary me-2">
                <i class="fas fa-utensils me-1"></i> Catálogo
            </a>
            <a href="/meals/orders" class="btn btn-primary">
                <i class="fas fa-shopping-cart me-1"></i> Meus Pedidos
            </a>
        </div>
    </div>

    <!-- Package Type Selection -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <ul class="nav nav-pills mb-3" id="package-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="day-tab" data-bs-toggle="pill" data-bs-target="#day-package" type="button" role="tab" aria-controls="day-package" aria-selected="true">
                                <i class="fas fa-calendar-day me-1"></i> Pacote Diário
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="week-tab" data-bs-toggle="pill" data-bs-target="#week-package" type="button" role="tab" aria-controls="week-package" aria-selected="false">
                                <i class="fas fa-calendar-week me-1"></i> Pacote Semanal
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="month-tab" data-bs-toggle="pill" data-bs-target="#month-package" type="button" role="tab" aria-controls="month-package" aria-selected="false">
                                <i class="fas fa-calendar-alt me-1"></i> Pacote Mensal
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="package-tabContent">
                        <!-- Day Package -->
                        <div class="tab-pane fade show active" id="day-package" role="tabpanel" aria-labelledby="day-tab">
                            <form method="POST" action="/meals/packages/create" id="day-package-form">
                                <input type="hidden" name="package_type" value="day">
                                
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="day-date" class="form-label">Data de Entrega</label>
                                        <input type="date" class="form-control" id="day-date" name="delivery_date" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                                    </div>
                                    <div class="col-md-8">
                                        <label for="day-name" class="form-label">Nome do Pacote</label>
                                        <input type="text" class="form-control" id="day-name" name="package_name" placeholder="Ex: Meu Dia Saudável" required>
                                    </div>
                                </div>

                                <h5 class="mt-4 mb-3">Selecione as refeições para seu pacote diário</h5>
                                
                                <div class="meal-slots">
                                    <!-- Breakfast -->
                                    <div class="card mb-3 meal-slot-card">
                                        <div class="card-header bg-warning bg-opacity-25">
                                            <div class="form-check">
                                                <input class="form-check-input meal-type-checkbox" type="checkbox" id="include-breakfast" name="meal_types[]" value="breakfast" checked>
                                                <label class="form-check-label" for="include-breakfast">
                                                    <strong>Café da Manhã</strong>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="card-body meal-selection-area">
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <select class="form-select meal-select" name="meals[breakfast]" data-meal-type="breakfast" required>
                                                        <option value="">Selecione uma opção de café da manhã</option>
                                                        <?php foreach ($breakfastMeals as $meal): ?>
                                                            <option value="<?= $meal['id'] ?>" data-price="<?= $meal['price'] ?>"><?= $meal['name'] ?> - <?= $meal['restaurant_name'] ?> (<?= Formatter::formatCurrency($meal['price']) ?>)</option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Lunch -->
                                    <div class="card mb-3 meal-slot-card">
                                        <div class="card-header bg-danger bg-opacity-25">
                                            <div class="form-check">
                                                <input class="form-check-input meal-type-checkbox" type="checkbox" id="include-lunch" name="meal_types[]" value="lunch" checked>
                                                <label class="form-check-label" for="include-lunch">
                                                    <strong>Almoço</strong>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="card-body meal-selection-area">
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <select class="form-select meal-select" name="meals[lunch]" data-meal-type="lunch" required>
                                                        <option value="">Selecione uma opção de almoço</option>
                                                        <?php foreach ($lunchMeals as $meal): ?>
                                                            <option value="<?= $meal['id'] ?>" data-price="<?= $meal['price'] ?>"><?= $meal['name'] ?> - <?= $meal['restaurant_name'] ?> (<?= Formatter::formatCurrency($meal['price']) ?>)</option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Dinner -->
                                    <div class="card mb-3 meal-slot-card">
                                        <div class="card-header bg-info bg-opacity-25">
                                            <div class="form-check">
                                                <input class="form-check-input meal-type-checkbox" type="checkbox" id="include-dinner" name="meal_types[]" value="dinner" checked>
                                                <label class="form-check-label" for="include-dinner">
                                                    <strong>Jantar</strong>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="card-body meal-selection-area">
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <select class="form-select meal-select" name="meals[dinner]" data-meal-type="dinner" required>
                                                        <option value="">Selecione uma opção de jantar</option>
                                                        <?php foreach ($dinnerMeals as $meal): ?>
                                                            <option value="<?= $meal['id'] ?>" data-price="<?= $meal['price'] ?>"><?= $meal['name'] ?> - <?= $meal['restaurant_name'] ?> (<?= Formatter::formatCurrency($meal['price']) ?>)</option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Snack -->
                                    <div class="card mb-3 meal-slot-card">
                                        <div class="card-header bg-success bg-opacity-25">
                                            <div class="form-check">
                                                <input class="form-check-input meal-type-checkbox" type="checkbox" id="include-snack" name="meal_types[]" value="snack">
                                                <label class="form-check-label" for="include-snack">
                                                    <strong>Lanche</strong>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="card-body meal-selection-area">
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <select class="form-select meal-select" name="meals[snack]" data-meal-type="snack" disabled>
                                                        <option value="">Selecione uma opção de lanche</option>
                                                        <?php foreach ($snackMeals as $meal): ?>
                                                            <option value="<?= $meal['id'] ?>" data-price="<?= $meal['price'] ?>"><?= $meal['name'] ?> - <?= $meal['restaurant_name'] ?> (<?= Formatter::formatCurrency($meal['price']) ?>)</option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Summary and Checkout -->
                                <div class="card mt-4">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Resumo do Pacote Diário</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Refeições Selecionadas: <span id="day-selected-count">3</span></h6>
                                                <ul id="day-selected-meals" class="list-unstyled">
                                                    <li>Café da Manhã: <span id="breakfast-selected-name">-</span></li>
                                                    <li>Almoço: <span id="lunch-selected-name">-</span></li>
                                                    <li>Jantar: <span id="dinner-selected-name">-</span></li>
                                                    <li class="d-none" id="snack-selected-item">Lanche: <span id="snack-selected-name">-</span></li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6 text-end">
                                                <h6>Total do Pacote:</h6>
                                                <h3 class="text-primary" id="day-package-total">R$ 0,00</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-check-circle me-1"></i> Confirmar Pacote Diário
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Week Package -->
                        <div class="tab-pane fade" id="week-package" role="tabpanel" aria-labelledby="week-tab">
                            <form method="POST" action="/meals/packages/create" id="week-package-form">
                                <input type="hidden" name="package_type" value="week">

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="week-start-date" class="form-label">Data de Início</label>
                                        <input type="date" class="form-control" id="week-start-date" name="start_date" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                                    </div>
                                    <div class="col-md-8">
                                        <label for="week-name" class="form-label">Nome do Pacote</label>
                                        <input type="text" class="form-control" id="week-name" name="package_name" placeholder="Ex: Minha Semana Fitness" required>
                                    </div>
                                </div>

                                <h5 class="mt-4 mb-3">Selecione quais dias da semana você deseja incluir</h5>
                                
                                <div class="week-day-selection mb-4">
                                    <div class="row g-3">
                                        <?php
                                        $daysOfWeek = [
                                            1 => 'Segunda-feira',
                                            2 => 'Terça-feira',
                                            3 => 'Quarta-feira',
                                            4 => 'Quinta-feira',
                                            5 => 'Sexta-feira',
                                            6 => 'Sábado',
                                            0 => 'Domingo'
                                        ];
                                        ?>
                                        
                                        <?php foreach ($daysOfWeek as $dayNum => $dayName): ?>
                                            <div class="col-md-3 col-6">
                                                <div class="form-check">
                                                    <input class="form-check-input week-day-checkbox" type="checkbox" id="day-<?= $dayNum ?>" name="days[]" value="<?= $dayNum ?>" <?= ($dayNum >= 1 && $dayNum <= 5) ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="day-<?= $dayNum ?>">
                                                        <?= $dayName ?>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <h5 class="mt-4 mb-3">Selecione quais refeições você deseja incluir em cada dia</h5>
                                
                                <div class="week-meal-types mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-3 col-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input week-meal-type-checkbox" type="checkbox" id="week-breakfast" name="meal_types[]" value="breakfast" checked>
                                                        <label class="form-check-label" for="week-breakfast">
                                                            Café da Manhã
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input week-meal-type-checkbox" type="checkbox" id="week-lunch" name="meal_types[]" value="lunch" checked>
                                                        <label class="form-check-label" for="week-lunch">
                                                            Almoço
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input week-meal-type-checkbox" type="checkbox" id="week-dinner" name="meal_types[]" value="dinner" checked>
                                                        <label class="form-check-label" for="week-dinner">
                                                            Jantar
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input week-meal-type-checkbox" type="checkbox" id="week-snack" name="meal_types[]" value="snack">
                                                        <label class="form-check-label" for="week-snack">
                                                            Lanche
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h5 class="mt-4 mb-3">Preferências de Refeição</h5>
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i> Para o pacote semanal, você escolhe suas preferências e nós selecionamos as melhores refeições para você a cada dia.
                                </div>
                                
                                <div class="row g-3 mb-4">
                                    <div class="col-md-4">
                                        <label for="max-calories" class="form-label">Calorias Máximas por Dia</label>
                                        <input type="number" class="form-control" id="max-calories" name="max_calories" min="1000" max="3500" value="2000">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="min-protein" class="form-label">Proteína Mínima por Dia (g)</label>
                                        <input type="number" class="form-control" id="min-protein" name="min_protein" min="30" max="200" value="75">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="preferred-restaurant" class="form-label">Restaurante Preferido</label>
                                        <select class="form-select" id="preferred-restaurant" name="preferred_restaurant">
                                            <option value="">Sem preferência</option>
                                            <?php foreach ($restaurants as $restaurant): ?>
                                                <option value="<?= $restaurant['id'] ?>"><?= $restaurant['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Summary and Checkout -->
                                <div class="card mt-4">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Resumo do Pacote Semanal</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Dias Selecionados: <span id="week-days-count">5</span></h6>
                                                <h6>Refeições por Dia: <span id="week-meals-per-day">3</span></h6>
                                                <h6>Total de Refeições: <span id="week-total-meals">15</span></h6>
                                            </div>
                                            <div class="col-md-6 text-end">
                                                <h6>Estimativa do Pacote:</h6>
                                                <h3 class="text-primary" id="week-package-estimate">R$ 450,00 - R$ 600,00</h3>
                                                <p class="text-muted small">O valor final será calculado com base nas refeições selecionadas</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-check-circle me-1"></i> Confirmar Pacote Semanal
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Month Package -->
                        <div class="tab-pane fade" id="month-package" role="tabpanel" aria-labelledby="month-tab">
                            <form method="POST" action="/meals/packages/create" id="month-package-form">
                                <input type="hidden" name="package_type" value="month">

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="month-start-date" class="form-label">Mês de Início</label>
                                        <input type="month" class="form-control" id="month-start-date" name="start_month" min="<?= date('Y-m') ?>" required>
                                    </div>
                                    <div class="col-md-8">
                                        <label for="month-name" class="form-label">Nome do Pacote</label>
                                        <input type="text" class="form-control" id="month-name" name="package_name" placeholder="Ex: Meu Mês Saudável" required>
                                    </div>
                                </div>

                                <h5 class="mt-4 mb-3">Selecione em quais dias da semana você deseja receber refeições</h5>

                                <div class="week-day-selection mb-4">
                                    <div class="row g-3">
                                        <?php foreach ($daysOfWeek as $dayNum => $dayName): ?>
                                            <div class="col-md-3 col-6">
                                                <div class="form-check">
                                                    <input class="form-check-input month-day-checkbox" type="checkbox" id="month-day-<?= $dayNum ?>" name="days[]" value="<?= $dayNum ?>" <?= ($dayNum >= 1 && $dayNum <= 5) ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="month-day-<?= $dayNum ?>">
                                                        <?= $dayName ?>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <h5 class="mt-4 mb-3">Selecione quais refeições você deseja incluir</h5>
                                
                                <div class="month-meal-types mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-3 col-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input month-meal-type-checkbox" type="checkbox" id="month-breakfast" name="meal_types[]" value="breakfast" checked>
                                                        <label class="form-check-label" for="month-breakfast">
                                                            Café da Manhã
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input month-meal-type-checkbox" type="checkbox" id="month-lunch" name="meal_types[]" value="lunch" checked>
                                                        <label class="form-check-label" for="month-lunch">
                                                            Almoço
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input month-meal-type-checkbox" type="checkbox" id="month-dinner" name="meal_types[]" value="dinner" checked>
                                                        <label class="form-check-label" for="month-dinner">
                                                            Jantar
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input month-meal-type-checkbox" type="checkbox" id="month-snack" name="meal_types[]" value="snack">
                                                        <label class="form-check-label" for="month-snack">
                                                            Lanche
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h5 class="mt-4 mb-3">Plano Nutricional</h5>
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i> Para o pacote mensal, nossos nutricionistas criarão um plano personalizado com base nas suas preferências.
                                </div>
                                
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="nutrition-plan" class="form-label">Objetivo Nutricional</label>
                                        <select class="form-select" id="nutrition-plan" name="nutrition_plan">
                                            <option value="balance">Alimentação Balanceada</option>
                                            <option value="weight_loss">Perda de Peso</option>
                                            <option value="muscle_gain">Ganho de Massa Muscular</option>
                                            <option value="low_carb">Baixo Carboidrato</option>
                                            <option value="vegetarian">Vegetariano</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="dietary-restrictions" class="form-label">Restrições Alimentares</label>
                                        <select class="form-select" id="dietary-restrictions" name="dietary_restrictions">
                                            <option value="">Nenhuma</option>
                                            <option value="gluten">Sem Glúten</option>
                                            <option value="lactose">Sem Lactose</option>
                                            <option value="seafood">Sem Frutos do Mar</option>
                                            <option value="nuts">Sem Oleaginosas</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-12">
                                        <label for="additional-notes" class="form-label">Observações Adicionais</label>
                                        <textarea class="form-control" id="additional-notes" name="additional_notes" rows="3" placeholder="Informe outras preferências, alergias ou restrições alimentares..."></textarea>
                                    </div>
                                </div>

                                <!-- Summary and Checkout -->
                                <div class="card mt-4">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Resumo do Pacote Mensal</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Dias por Semana: <span id="month-days-per-week">5</span></h6>
                                                <h6>Refeições por Dia: <span id="month-meals-per-day">3</span></h6>
                                                <h6>Semanas no Mês: <span>4</span></h6>
                                                <h6>Total de Refeições: <span id="month-total-meals">60</span></h6>
                                            </div>
                                            <div class="col-md-6 text-end">
                                                <h6>Desconto Mensal: <span class="text-success">10%</span></h6>
                                                <h6>Estimativa do Pacote:</h6>
                                                <h3 class="text-primary" id="month-package-estimate">R$ 1.620,00 - R$ 2.160,00</h3>
                                                <p class="text-muted small">O valor final será calculado após a aprovação do plano</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-check-circle me-1"></i> Solicitar Pacote Mensal
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // *** Daily Package Functionality ***
    
    // Toggle meal selections based on checkbox state
    const mealTypeCheckboxes = document.querySelectorAll('.meal-type-checkbox');
    mealTypeCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const mealType = this.value;
            const selectElement = document.querySelector(`.meal-select[data-meal-type="${mealType}"]`);
            const mealItemDisplay = document.getElementById(`${mealType}-selected-item`);
            
            if (this.checked) {
                selectElement.disabled = false;
                selectElement.required = true;
                if (mealItemDisplay) mealItemDisplay.classList.remove('d-none');
            } else {
                selectElement.disabled = true;
                selectElement.required = false;
                selectElement.selectedIndex = 0;
                if (mealItemDisplay) mealItemDisplay.classList.add('d-none');
            }
            
            updateDayPackageSummary();
        });
    });
    
    // Update meal selection display and total price
    const mealSelects = document.querySelectorAll('.meal-select');
    mealSelects.forEach(select => {
        select.addEventListener('change', function() {
            const mealType = this.dataset.mealType;
            const selectedOption = this.options[this.selectedIndex];
            const nameDisplay = document.getElementById(`${mealType}-selected-name`);
            
            if (this.value) {
                nameDisplay.textContent = selectedOption.text.split(' (')[0]; // Remove price part
            } else {
                nameDisplay.textContent = '-';
            }
            
            updateDayPackageSummary();
        });
    });
    
    // Update day package summary
    function updateDayPackageSummary() {
        let totalPrice = 0;
        let selectedCount = 0;
        
        mealTypeCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                selectedCount++;
                const mealType = checkbox.value;
                const selectElement = document.querySelector(`.meal-select[data-meal-type="${mealType}"]`);
                
                if (selectElement.value) {
                    const selectedOption = selectElement.options[selectElement.selectedIndex];
                    const price = parseFloat(selectedOption.dataset.price || 0);
                    totalPrice += price;
                }
            }
        });
        
        document.getElementById('day-selected-count').textContent = selectedCount;
        document.getElementById('day-package-total').textContent = formatCurrency(totalPrice);
    }
    
    // *** Weekly Package Functionality ***
    
    // Update weekly package summary when checkboxes change
    const weekDayCheckboxes = document.querySelectorAll('.week-day-checkbox');
    const weekMealTypeCheckboxes = document.querySelectorAll('.week-meal-type-checkbox');
    
    function updateWeekPackageSummary() {
        const selectedDays = Array.from(weekDayCheckboxes).filter(cb => cb.checked).length;
        const selectedMealTypes = Array.from(weekMealTypeCheckboxes).filter(cb => cb.checked).length;
        const totalMeals = selectedDays * selectedMealTypes;
        
        // Update counts
        document.getElementById('week-days-count').textContent = selectedDays;
        document.getElementById('week-meals-per-day').textContent = selectedMealTypes;
        document.getElementById('week-total-meals').textContent = totalMeals;
        
        // Estimate price range (average meal price between R$30-40)
        const minEstimate = totalMeals * 30;
        const maxEstimate = totalMeals * 40;
        document.getElementById('week-package-estimate').textContent = `${formatCurrency(minEstimate)} - ${formatCurrency(maxEstimate)}`;
    }
    
    weekDayCheckboxes.forEach(cb => cb.addEventListener('change', updateWeekPackageSummary));
    weekMealTypeCheckboxes.forEach(cb => cb.addEventListener('change', updateWeekPackageSummary));
    
    // *** Monthly Package Functionality ***
    
    // Update monthly package summary when checkboxes change
    const monthDayCheckboxes = document.querySelectorAll('.month-day-checkbox');
    const monthMealTypeCheckboxes = document.querySelectorAll('.month-meal-type-checkbox');
    
    function updateMonthPackageSummary() {
        const selectedDays = Array.from(monthDayCheckboxes).filter(cb => cb.checked).length;
        const selectedMealTypes = Array.from(monthMealTypeCheckboxes).filter(cb => cb.checked).length;
        const totalMeals = selectedDays * selectedMealTypes * 4; // 4 weeks in a month
        
        // Update counts
        document.getElementById('month-days-per-week').textContent = selectedDays;
        document.getElementById('month-meals-per-day').textContent = selectedMealTypes;
        document.getElementById('month-total-meals').textContent = totalMeals;
        
        // Estimate price range with 10% discount (average meal price between R$30-40)
        const minEstimate = totalMeals * 30 * 0.9;
        const maxEstimate = totalMeals * 40 * 0.9;
        document.getElementById('month-package-estimate').textContent = `${formatCurrency(minEstimate)} - ${formatCurrency(maxEstimate)}`;
    }
    
    monthDayCheckboxes.forEach(cb => cb.addEventListener('change', updateMonthPackageSummary));
    monthMealTypeCheckboxes.forEach(cb => cb.addEventListener('change', updateMonthPackageSummary));
    
    // Initialize all summaries
    updateDayPackageSummary();
    updateWeekPackageSummary();
    updateMonthPackageSummary();
    
    // Helper format function
    function formatCurrency(value) {
        return 'R$ ' + value.toFixed(2).replace('.', ',');
    }
});