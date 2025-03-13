<?php $title = 'Criar Plano de Dieta - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col">
            <h2>Criar Novo Plano de Dieta</h2>
            <p class="text-muted">Crie um plano de dieta personalizado para um cliente.</p>
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
                                <a href="/nutritionist/diets" class="btn btn-sm btn-success">Voltar para a lista de dietas</a>
                                <a href="/nutritionist/diets/create" class="btn btn-sm btn-outline-success">Criar outra dieta</a>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger">
                            <?= $error ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="/nutritionist/diets/create">
                        <h5 class="mb-3">Informações Básicas</h5>
                        
                        <div class="mb-3">
                            <label for="client_id" class="form-label">Cliente</label>
                            <select class="form-select" id="client_id" name="client_id" required>
                                <option value="">Selecione um cliente...</option>
                                <?php foreach ($clients as $client): ?>
                                    <option value="<?= $client['id'] ?>"><?= $client['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome do Plano</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="form-text">Ex: Plano de Emagrecimento, Dieta Hipertrofia, etc.</div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">Data Inicial</label>
                                <input type="text" class="form-control" id="start_date" name="start_date" value="<?= date('d/m/Y') ?>" required>
                                <div class="form-text">Formato: dd/mm/aaaa</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">Data Final</label>
                                <input type="text" class="form-control" id="end_date" name="end_date" value="<?= date('d/m/Y', strtotime('+30 days')) ?>" required>
                                <div class="form-text">Formato: dd/mm/aaaa</div>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h5 class="mb-3">Objetivos Nutricionais Diários</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="daily_calories" class="form-label">Calorias (kcal)</label>
                                <input type="number" class="form-control" id="daily_calories" name="daily_calories" step="1" min="0">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="daily_protein" class="form-label">Proteínas (g)</label>
                                <input type="number" class="form-control" id="daily_protein" name="daily_protein" step="0.1" min="0">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="daily_carbs" class="form-label">Carboidratos (g)</label>
                                <input type="number" class="form-control" id="daily_carbs" name="daily_carbs" step="0.1" min="0">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="daily_fat" class="form-label">Gorduras (g)</label>
                                <input type="number" class="form-control" id="daily_fat" name="daily_fat" step="0.1" min="0">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Observações e Recomendações</label>
                            <textarea class="form-control" id="notes" name="notes" rows="4"></textarea>
                            <div class="form-text">Inclua orientações gerais, restrições alimentares, etc.</div>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> Após criar o plano, você poderá adicionar refeições específicas para cada dia da semana.
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="/nutritionist/diets" class="btn btn-outline-secondary me-md-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Criar Plano de Dieta</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Calculate daily nutrition values based on client selection
    const clientSelect = document.getElementById('client_id');
    
    clientSelect.addEventListener('change', function() {
        const clientId = this.value;
        
        // This would be replaced with actual client data in a real implementation
        if (clientId) {
            // Example calculation based on a typical adult
            document.getElementById('daily_calories').value = 2000;
            document.getElementById('daily_protein').value = 75;
            document.getElementById('daily_carbs').value = 250;
            document.getElementById('daily_fat').value = 65;
        }
    });
    
    // Date picker functionality would be initialized here
    // Using the generic date functionality from main.js
});
</script>