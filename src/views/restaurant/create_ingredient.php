<?php $title = 'Criar Ingrediente - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col">
            <h2>Criar Novo Ingrediente</h2>
            <p class="text-muted">Adicione um novo ingrediente para utilizar nas refeições.</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success">
                            <?= $success ?>
                            <div class="mt-2">
                                <a href="/restaurant/ingredients" class="btn btn-sm btn-success">Voltar para a lista de ingredientes</a>
                                <a href="/restaurant/ingredients/create" class="btn btn-sm btn-outline-success">Criar outro ingrediente</a>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger">
                            <?= $error ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="/restaurant/ingredients/create">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome do Ingrediente</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> Os valores nutricionais abaixo devem ser informados para cada 100g do ingrediente.
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="calories" class="form-label">Calorias (kcal)</label>
                                <input type="number" class="form-control" id="calories" name="calories" step="0.1" min="0" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="protein" class="form-label">Proteínas (g)</label>
                                <input type="number" class="form-control" id="protein" name="protein" step="0.1" min="0" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="carbs" class="form-label">Carboidratos (g)</label>
                                <input type="number" class="form-control" id="carbs" name="carbs" step="0.1" min="0" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="fat" class="form-label">Gorduras (g)</label>
                                <input type="number" class="form-control" id="fat" name="fat" step="0.1" min="0" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="fiber" class="form-label">Fibras (g) <span class="text-muted">(opcional)</span></label>
                            <input type="number" class="form-control" id="fiber" name="fiber" step="0.1" min="0">
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="/restaurant/ingredients" class="btn btn-outline-secondary me-md-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Criar Ingrediente</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>