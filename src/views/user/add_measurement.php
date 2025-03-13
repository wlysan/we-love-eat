<?php $title = 'Adicionar Medida - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col">
            <h2>Adicionar Nova Medida</h2>
            <p class="text-muted">Registre suas medidas corporais para acompanhar seu progresso.</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">Menu</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="/profile" class="list-group-item list-group-item-action">
                            <i class="fas fa-user me-2"></i> Perfil
                        </a>
                        <a href="/profile/edit" class="list-group-item list-group-item-action">
                            <i class="fas fa-user-edit me-2"></i> Editar Perfil
                        </a>
                        <a href="/profile/measurements" class="list-group-item list-group-item-action">
                            <i class="fas fa-weight me-2"></i> Medidas
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
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
                    
                    <form method="POST" action="/profile/measurements/add">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date" class="form-label">Data</label>
                                <input type="text" class="form-control" id="date" name="date" value="<?= date('d/m/Y') ?>" required>
                                <div class="form-text">Formato: dd/mm/aaaa</div>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="weight" class="form-label">Peso (kg)</label>
                                <input type="number" class="form-control" id="weight" name="weight" value="<?= $profile['current_weight'] ?? '' ?>" step="0.1" min="30" max="300">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="body_fat_percentage" class="form-label">Percentual de Gordura (%)</label>
                                <input type="number" class="form-control" id="body_fat_percentage" name="body_fat_percentage" step="0.1" min="1" max="60">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="waist" class="form-label">Circunferência da Cintura (cm)</label>
                                <input type="number" class="form-control" id="waist" name="waist" step="0.1" min="40" max="200">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="chest" class="form-label">Circunferência do Peito (cm)</label>
                                <input type="number" class="form-control" id="chest" name="chest" step="0.1" min="40" max="200">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="arms" class="form-label">Circunferência dos Braços (cm)</label>
                                <input type="number" class="form-control" id="arms" name="arms" step="0.1" min="10" max="100">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="legs" class="form-label">Circunferência das Pernas (cm)</label>
                                <input type="number" class="form-control" id="legs" name="legs" step="0.1" min="20" max="120">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Observações</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="/profile/measurements" class="btn btn-outline-secondary me-md-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Salvar Medida</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>