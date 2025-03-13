<?php $title = 'Editar Refeição - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col">
            <h2>Editar Refeição</h2>
            <p class="text-muted">Editar informações da refeição <?= $meal['name'] ?>.</p>
        </div>
    </div>
    
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
                    
                    <form method="POST" action="/restaurant/meals/edit?id=<?= $meal['id'] ?>">
                        <h5 class="mb-3">Informações Básicas</h5>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome da Refeição</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= $meal['name'] ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Descrição</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?= $meal['description'] ?></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Preço (R$)</label>
                                <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" value="<?= $meal['price'] ?>" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="meal_type" class="form-label">Tipo de Refeição</label>
                                <select class="form-select" id="meal_type" name="meal_type" required>
                                    <option value="">Selecione...</option>
                                    <option value="breakfast" <?= $meal['meal_type'] === 'breakfast' ? 'selected' : '' ?>>Café da Manhã</option>
                                    <option value="lunch" <?= $meal['meal_type'] === 'lunch' ? 'selected' : '' ?>>Almoço</option>
                                    <option value="dinner" <?= $meal['meal_type'] === 'dinner' ? 'selected' : '' ?>>Jantar</option>
                                    <option value="snack" <?= $meal['meal_type'] === 'snack' ? 'selected' : '' ?>>Lanche</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="available" name="available" <?= $meal['available'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="available">Disponível para pedidos</label>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h5 class="mb-3">Ingredientes</h5>
                        
                        <div id="ingredientsContainer">
                            <?php if (empty($mealIngredients)): ?>
                                <div class="row ingredient-row mb-3">
                                    <div class="col-md-6">
                                        <select class="form-select" name="ingredient_id[]" required>
                                            <option value="">Selecione um ingrediente</option>
                                            <?php foreach ($ingredients as $ingredient): ?>
                                                <option value="<?= $ingredient['id'] ?>"><?= $ingredient['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="amount[]" placeholder="Quantidade" min="1" required>
                                            <span class="input-group-text">g</span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <!-- No remove button for the first ingredient -->
                                    </div>
                                </div>
                            <?php else: ?>
                                <?php foreach ($mealIngredients as $index => $ingredient): ?>
                                    <div class="row ingredient-row mb-3">
                                        <div class="col-md-6">
                                            <select class="form-select" name="ingredient_id[]" required>
                                                <option value="">Selecione um ingrediente</option>
                                                <?php foreach ($ingredients as $ing): ?>
                                                    <option value="<?= $ing['id'] ?>" <?= $ing['id'] === $ingredient['ingredient_id'] ? 'selected' : '' ?>><?= $ing['name'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <input type="number" class="form-control" name="amount[]" placeholder="Quantidade" min="1" value="<?= $ingredient['amount'] ?>" required>
                                                <span class="input-group-text">g</span>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <?php if ($index > 0): ?>
                                                <button type="button" class="btn btn-outline-danger remove-ingredient-btn w-100">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <button type="button" class="btn btn-outline-secondary" id="addIngredientBtn">
                                <i class="fas fa-plus-circle me-2"></i> Adicionar Ingrediente
                            </button>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> A informação nutricional será calculada automaticamente com base nos ingredientes adicionados.
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="/restaurant/meals" class="btn btn-outline-secondary me-md-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>