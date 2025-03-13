<?php $title = 'Detalhes da Refeição - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><?= $meal['name'] ?></h2>
            <p class="text-muted">
                <?= Formatter::getMealTypeName($meal['meal_type']) ?> | 
                <?= Formatter::formatCurrency($meal['price']) ?> | 
                <span class="badge <?= $meal['available'] ? 'bg-success' : 'bg-danger' ?>">
                    <?= $meal['available'] ? 'Disponível' : 'Indisponível' ?>
                </span>
            </p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="/restaurant/meals" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i> Voltar
            </a>
            <a href="/restaurant/meals/edit?id=<?= $meal['id'] ?>" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i> Editar
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Detalhes da Refeição</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6>Descrição</h6>
                        <p><?= $meal['description'] ?: 'Sem descrição' ?></p>
                    </div>
                    
                    <div class="mb-4">
                        <h6>Informação Nutricional (Total)</h6>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center py-3">
                                        <h6 class="text-muted mb-1">Calorias</h6>
                                        <h4><?= Formatter::formatCalories($nutritionFacts['calories']) ?></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center py-3">
                                        <h6 class="text-muted mb-1">Proteínas</h6>
                                        <h4><?= Formatter::formatNutrient($nutritionFacts['protein']) ?></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center py-3">
                                        <h6 class="text-muted mb-1">Carboidratos</h6>
                                        <h4><?= Formatter::formatNutrient($nutritionFacts['carbs']) ?></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center py-3">
                                        <h6 class="text-muted mb-1">Gorduras</h6>
                                        <h4><?= Formatter::formatNutrient($nutritionFacts['fat']) ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if (!empty($nutritionFacts['fiber'])): ?>
                            <div class="mt-2">
                                <p><strong>Fibras:</strong> <?= Formatter::formatNutrient($nutritionFacts['fiber']) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div>
                        <h6>Ingredientes</h6>
                        <?php if (empty($nutritionFacts['ingredients'])): ?>
                            <p class="text-muted">Nenhum ingrediente cadastrado.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Ingrediente</th>
                                            <th>Quantidade</th>
                                            <th>Calorias</th>
                                            <th>Proteínas</th>
                                            <th>Carboidratos</th>
                                            <th>Gorduras</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($nutritionFacts['ingredients'] as $ingredient): ?>
                                            <?php
                                            $factor = $ingredient['amount'] / 100;
                                            $calories = $ingredient['calories'] * $factor;
                                            $protein = $ingredient['protein'] * $factor;
                                            $carbs = $ingredient['carbs'] * $factor;
                                            $fat = $ingredient['fat'] * $factor;
                                            ?>
                                            <tr>
                                                <td><?= $ingredient['name'] ?></td>
                                                <td><?= $ingredient['amount'] ?>g</td>
                                                <td><?= number_format($calories, 1) ?> kcal</td>
                                                <td><?= number_format($protein, 1) ?>g</td>
                                                <td><?= number_format($carbs, 1) ?>g</td>
                                                <td><?= number_format($fat, 1) ?>g</td>
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
        
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Adequação Nutricional</h5>
                </div>
                <div class="card-body">
                    <p class="mb-3 small">Adequação a um plano de dieta típico com base nas necessidades diárias:</p>
                    
                    <div class="mb-3">
                        <label class="form-label d-flex justify-content-between">
                            <span>Calorias (2000 kcal/dia)</span>
                            <span><?= number_format(($nutritionFacts['calories'] / 2000) * 100, 1) ?>%</span>
                        </label>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: <?= min(100, ($nutritionFacts['calories'] / 2000) * 100) ?>%" aria-valuenow="<?= ($nutritionFacts['calories'] / 2000) * 100 ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label d-flex justify-content-between">
                            <span>Proteínas (75g/dia)</span>
                            <span><?= number_format(($nutritionFacts['protein'] / 75) * 100, 1) ?>%</span>
                        </label>
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" style="width: <?= min(100, ($nutritionFacts['protein'] / 75) * 100) ?>%" aria-valuenow="<?= ($nutritionFacts['protein'] / 75) * 100 ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label d-flex justify-content-between">
                            <span>Carboidratos (250g/dia)</span>
                            <span><?= number_format(($nutritionFacts['carbs'] / 250) * 100, 1) ?>%</span>
                        </label>
                        <div class="progress">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: <?= min(100, ($nutritionFacts['carbs'] / 250) * 100) ?>%" aria-valuenow="<?= ($nutritionFacts['carbs'] / 250) * 100 ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label d-flex justify-content-between">
                            <span>Gorduras (65g/dia)</span>
                            <span><?= number_format(($nutritionFacts['fat'] / 65) * 100, 1) ?>%</span>
                        </label>
                        <div class="progress">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: <?= min(100, ($nutritionFacts['fat'] / 65) * 100) ?>%" aria-valuenow="<?= ($nutritionFacts['fat'] / 65) * 100 ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info small mt-4 mb-0">
                        <i class="fas fa-info-circle me-1"></i> Estes valores são aproximados e podem variar dependendo das necessidades individuais de cada cliente.
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Estatísticas</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Estas estatísticas serão disponibilizadas após a refeição ser selecionada por usuários.</p>
                    
                    <!-- This would be populated with actual statistics in a real implementation -->
                    <p class="text-center mt-4 mb-0">
                        <i class="fas fa-chart-bar fa-2x text-muted"></i>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>