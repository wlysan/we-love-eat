<?php $title = 'Editar Plano de Dieta - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Editar Plano de Dieta</h2>
            <p class="text-muted">Editar plano de dieta: <?= $plan['name'] ?> para <?= $plan['client_name'] ?></p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="/nutritionist/diets" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Voltar para Dietas
            </a>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-12">
            <ul class="nav nav-tabs" id="dietTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab" aria-controls="details" aria-selected="true">
                        Detalhes do Plano
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="meals-tab" data-bs-toggle="tab" data-bs-target="#meals" type="button" role="tab" aria-controls="meals" aria-selected="false">
                        Refeições
                    </button>
                </li>
            </ul>
        </div>
    </div>
    
    <div class="tab-content" id="dietTabsContent">
        <!-- Details Tab -->
        <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <?php if (!empty($success)): ?>
                                <div class="alert alert-success">
                                    <?= $success ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger">
                                    <?= $error ?>
                                </div>
                            <?php endif; ?>
                            
                            <form method="POST" action="/nutritionist/diets/edit?id=<?= $plan['id'] ?>">
                                <h5 class="mb-3">Informações Básicas</h5>
                                
                                <div class="mb-3">
                                    <label for="client_name" class="form-label">Cliente</label>
                                    <input type="text" class="form-control" id="client_name" value="<?= $plan['client_name'] ?>" disabled>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nome do Plano</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?= $plan['name'] ?>" required>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="start_date" class="form-label">Data Inicial</label>
                                        <input type="text" class="form-control" id="start_date" name="start_date" value="<?= Formatter::formatDate($plan['start_date']) ?>" required>
                                        <div class="form-text">Formato: dd/mm/aaaa</div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="end_date" class="form-label">Data Final</label>
                                        <input type="text" class="form-control" id="end_date" name="end_date" value="<?= Formatter::formatDate($plan['end_date']) ?>" required>
                                        <div class="form-text">Formato: dd/mm/aaaa</div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="active" <?= $plan['status'] === 'active' ? 'selected' : '' ?>>Ativo</option>
                                        <option value="completed" <?= $plan['status'] === 'completed' ? 'selected' : '' ?>>Concluído</option>
                                        <option value="canceled" <?= $plan['status'] === 'canceled' ? 'selected' : '' ?>>Cancelado</option>
                                    </select>
                                </div>
                                
                                <hr class="my-4">
                                
                                <h5 class="mb-3">Objetivos Nutricionais Diários</h5>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="daily_calories" class="form-label">Calorias (kcal)</label>
                                        <input type="number" class="form-control" id="daily_calories" name="daily_calories" step="1" min="0" value="<?= $plan['daily_calories'] ?>">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="daily_protein" class="form-label">Proteínas (g)</label>
                                        <input type="number" class="form-control" id="daily_protein" name="daily_protein" step="0.1" min="0" value="<?= $plan['daily_protein'] ?>">
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="daily_carbs" class="form-label">Carboidratos (g)</label>
                                        <input type="number" class="form-control" id="daily_carbs" name="daily_carbs" step="0.1" min="0" value="<?= $plan['daily_carbs'] ?>">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="daily_fat" class="form-label">Gorduras (g)</label>
                                        <input type="number" class="form-control" id="daily_fat" name="daily_fat" step="0.1" min="0" value="<?= $plan['daily_fat'] ?>">
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Observações e Recomendações</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="4"><?= $plan['notes'] ?></textarea>
                                </div>
                                
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="/nutritionist/diets" class="btn btn-outline-secondary me-md-2">Cancelar</a>
                                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Meals Tab -->
        <div class="tab-pane fade" id="meals" role="tabpanel" aria-labelledby="meals-tab">
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Refeições Programadas</h5>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMealModal">
                                <i class="fas fa-plus-circle me-1"></i> Adicionar Refeição
                            </button>
                        </div>
                        <div class="card-body">
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
                            
                            <?php if (empty($mealsByDay)): ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i> Nenhuma refeição programada para este plano.
                                    Adicione refeições para que o cliente possa selecionar suas opções.
                                </div>
                            <?php else: ?>
                                <div class="accordion" id="daysAccordion">
                                    <?php foreach ($mealsByDay as $day => $dayMeals): ?>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading<?= $day ?>">
                                                <button class="accordion-button <?= $day !== 1 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $day ?>" aria-expanded="<?= $day === 1 ? 'true' : 'false' ?>" aria-controls="collapse<?= $day ?>">
                                                    <strong><?= $dayNames[$day] ?></strong>
                                                    <span class="badge bg-primary ms-2"><?= count($dayMeals) ?> refeições</span>
                                                </button>
                                            </h2>
                                            <div id="collapse<?= $day ?>" class="accordion-collapse collapse <?= $day === 1 ? 'show' : '' ?>" aria-labelledby="heading<?= $day ?>" data-bs-parent="#daysAccordion">
                                                <div class="accordion-body">
                                                    <div class="list-group">
                                                        <?php foreach ($dayMeals as $meal): ?>
                                                            <div class="list-group-item meal-item <?= strtolower($meal['meal_type']) ?>">
                                                                <div class="row align-items-center">
                                                                    <div class="col-md-4">
                                                                        <h6 class="mb-0"><?= Formatter::getMealTypeName($meal['meal_type']) ?></h6>
                                                                        <p class="text-muted mb-0 small"><?= Formatter::formatTime($meal['time_of_day']) ?></p>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="small">
                                                                            <?php if (!empty($meal['calories_target'])): ?>
                                                                                <span class="me-2"><i class="fas fa-fire text-danger me-1"></i> <?= Formatter::formatCalories($meal['calories_target']) ?></span>
                                                                            <?php endif; ?>
                                                                            
                                                                            <?php if (!empty($meal['protein_target'])): ?>
                                                                                <span class="me-2"><i class="fas fa-drumstick-bite text-success me-1"></i> <?= Formatter::formatNutrient($meal['protein_target']) ?></span>
                                                                            <?php endif; ?>
                                                                            
                                                                            <?php if (!empty($meal['carbs_target'])): ?>
                                                                                <span class="me-2"><i class="fas fa-bread-slice text-warning me-1"></i> <?= Formatter::formatNutrient($meal['carbs_target']) ?></span>
                                                                            <?php endif; ?>
                                                                            
                                                                            <?php if (!empty($meal['fat_target'])): ?>
                                                                                <span><i class="fas fa-cheese text-primary me-1"></i> <?= Formatter::formatNutrient($meal['fat_target']) ?></span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                        
                                                                        <?php if (!empty($meal['notes'])): ?>
                                                                            <div class="mt-1 small text-muted">
                                                                                <i class="fas fa-info-circle me-1"></i> <?= $meal['notes'] ?>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <div class="col-md-2 text-end">
                                                                        <button type="button" class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editMealModal<?= $meal['id'] ?>">
                                                                            <i class="fas fa-edit"></i>
                                                                        </button>
                                                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteMealModal<?= $meal['id'] ?>">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Edit Meal Modal -->
                                                            <div class="modal fade" id="editMealModal<?= $meal['id'] ?>" tabindex="-1" aria-labelledby="editMealModalLabel<?= $meal['id'] ?>" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <form method="POST" action="/nutritionist/diets/edit-meal">
                                                                            <input type="hidden" name="plan_id" value="<?= $plan['id'] ?>">
                                                                            <input type="hidden" name="meal_id" value="<?= $meal['id'] ?>">
                                                                            
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="editMealModalLabel<?= $meal['id'] ?>">Editar Refeição</h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="mb-3">
                                                                                    <label for="meal_type<?= $meal['id'] ?>" class="form-label">Tipo de Refeição</label>
                                                                                    <select class="form-select" id="meal_type<?= $meal['id'] ?>" name="meal_type" required>
                                                                                        <option value="breakfast" <?= $meal['meal_type'] === 'breakfast' ? 'selected' : '' ?>>Café da Manhã</option>
                                                                                        <option value="lunch" <?= $meal['meal_type'] === 'lunch' ? 'selected' : '' ?>>Almoço</option>
                                                                                        <option value="dinner" <?= $meal['meal_type'] === 'dinner' ? 'selected' : '' ?>>Jantar</option>
                                                                                        <option value="snack" <?= $meal['meal_type'] === 'snack' ? 'selected' : '' ?>>Lanche</option>
                                                                                    </select>
                                                                                </div>
                                                                                
                                                                                <div class="mb-3">
                                                                                    <label for="day_of_week<?= $meal['id'] ?>" class="form-label">Dia da Semana</label>
                                                                                    <select class="form-select" id="day_of_week<?= $meal['id'] ?>" name="day_of_week" required>
                                                                                        <?php for ($i = 0; $i < 7; $i++): ?>
                                                                                            <option value="<?= $i ?>" <?= $meal['day_of_week'] == $i ? 'selected' : '' ?>><?= $dayNames[$i] ?></option>
                                                                                        <?php endfor; ?>
                                                                                    </select>
                                                                                </div>
                                                                                
                                                                                <div class="mb-3">
                                                                                    <label for="time_of_day<?= $meal['id'] ?>" class="form-label">Horário</label>
                                                                                    <input type="time" class="form-control" id="time_of_day<?= $meal['id'] ?>" name="time_of_day" value="<?= $meal['time_of_day'] ?>" required>
                                                                                </div>
                                                                                
                                                                                <div class="row">
                                                                                    <div class="col-md-6 mb-3">
                                                                                        <label for="calories_target<?= $meal['id'] ?>" class="form-label">Calorias (kcal)</label>
                                                                                        <input type="number" class="form-control" id="calories_target<?= $meal['id'] ?>" name="calories_target" step="1" min="0" value="<?= $meal['calories_target'] ?>">
                                                                                    </div>
                                                                                    
                                                                                    <div class="col-md-6 mb-3">
                                                                                        <label for="protein_target<?= $meal['id'] ?>" class="form-label">Proteínas (g)</label>
                                                                                        <input type="number" class="form-control" id="protein_target<?= $meal['id'] ?>" name="protein_target" step="0.1" min="0" value="<?= $meal['protein_target'] ?>">
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <div class="row">
                                                                                    <div class="col-md-6 mb-3">
                                                                                        <label for="carbs_target<?= $meal['id'] ?>" class="form-label">Carboidratos (g)</label>
                                                                                        <input type="number" class="form-control" id="carbs_target<?= $meal['id'] ?>" name="carbs_target" step="0.1" min="0" value="<?= $meal['carbs_target'] ?>">
                                                                                    </div>
                                                                                    
                                                                                    <div class="col-md-6 mb-3">
                                                                                        <label for="fat_target<?= $meal['id'] ?>" class="form-label">Gorduras (g)</label>
                                                                                        <input type="number" class="form-control" id="fat_target<?= $meal['id'] ?>" name="fat_target" step="0.1" min="0" value="<?= $meal['fat_target'] ?>">
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <div class="mb-3">
                                                                                    <label for="notes<?= $meal['id'] ?>" class="form-label">Observações</label>
                                                                                    <textarea class="form-control" id="notes<?= $meal['id'] ?>" name="notes" rows="2"><?= $meal['notes'] ?></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                                                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Delete Meal Modal -->
                                                            <div class="modal fade" id="deleteMealModal<?= $meal['id'] ?>" tabindex="-1" aria-labelledby="deleteMealModalLabel<?= $meal['id'] ?>" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <form method="POST" action="/nutritionist/diets/edit-meal">
                                                                            <input type="hidden" name="plan_id" value="<?= $plan['id'] ?>">
                                                                            <input type="hidden" name="meal_id" value="<?= $meal['id'] ?>">
                                                                            <input type="hidden" name="delete" value="1">
                                                                            
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="deleteMealModalLabel<?= $meal['id'] ?>">Confirmar Exclusão</h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <p>Tem certeza que deseja excluir esta refeição?</p>
                                                                                <p class="text-danger">Esta ação não pode ser desfeita.</p>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                                                <button type="submit" class="btn btn-danger">Excluir Refeição</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
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
    </div>
</div>

<!-- Add Meal Modal -->
<div class="modal fade" id="addMealModal" tabindex="-1" aria-labelledby="addMealModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/nutritionist/diets/add-meal">
                <input type="hidden" name="plan_id" value="<?= $plan['id'] ?>">
                
                <div class="modal-header">
                    <h5 class="modal-title" id="addMealModalLabel">Adicionar Refeição</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="meal_type" class="form-label">Tipo de Refeição</label>
                        <select class="form-select" id="meal_type" name="meal_type" required>
                            <option value="">Selecione...</option>
                            <option value="breakfast">Café da Manhã</option>
                            <option value="lunch">Almoço</option>
                            <option value="dinner">Jantar</option>
                            <option value="snack">Lanche</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="day_of_week" class="form-label">Dia da Semana</label>
                        <select class="form-select" id="day_of_week" name="day_of_week" required>
                            <option value="">Selecione...</option>
                            <?php for ($i = 0; $i < 7; $i++): ?>
                                <option value="<?= $i ?>"><?= $dayNames[$i] ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="time_of_day" class="form-label">Horário</label>
                        <input type="time" class="form-control" id="time_of_day" name="time_of_day" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="calories_target" class="form-label">Calorias (kcal)</label>
                            <input type="number" class="form-control" id="calories_target" name="calories_target" step="1" min="0">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="protein_target" class="form-label">Proteínas (g)</label>
                            <input type="number" class="form-control" id="protein_target" name="protein_target" step="0.1" min="0">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="carbs_target" class="form-label">Carboidratos (g)</label>
                            <input type="number" class="form-control" id="carbs_target" name="carbs_target" step="0.1" min="0">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="fat_target" class="form-label">Gorduras (g)</label>
                            <input type="number" class="form-control" id="fat_target" name="fat_target" step="0.1" min="0">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Observações</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Adicionar Refeição</button>
                </div>
            </form>
        </div>
    </div>
</div>