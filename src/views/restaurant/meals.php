<?php $title = 'Gerenciar Refeições - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Gerenciar Refeições</h2>
            <p class="text-muted">Cadastre e gerencie as refeições disponíveis em seu restaurante.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="/restaurant/meals/create" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i> Nova Refeição
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">Filtrar</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="/restaurant/meals">
                        <div class="mb-3">
                            <label for="search" class="form-label">Buscar</label>
                            <input type="text" class="form-control" id="search" name="search" value="<?= $filter['search'] ?? '' ?>" placeholder="Nome da refeição...">
                        </div>
                        
                        <div class="mb-3">
                            <label for="meal_type" class="form-label">Tipo de Refeição</label>
                            <select class="form-select" id="meal_type" name="meal_type">
                                <option value="">Todos</option>
                                <option value="breakfast" <?= ($filter['meal_type'] ?? '') === 'breakfast' ? 'selected' : '' ?>>Café da Manhã</option>
                                <option value="lunch" <?= ($filter['meal_type'] ?? '') === 'lunch' ? 'selected' : '' ?>>Almoço</option>
                                <option value="dinner" <?= ($filter['meal_type'] ?? '') === 'dinner' ? 'selected' : '' ?>>Jantar</option>
                                <option value="snack" <?= ($filter['meal_type'] ?? '') === 'snack' ? 'selected' : '' ?>>Lanche</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="available" class="form-label">Disponibilidade</label>
                            <select class="form-select" id="available" name="available">
                                <option value="">Todos</option>
                                <option value="1" <?= isset($filter['available']) && $filter['available'] === true ? 'selected' : '' ?>>Disponível</option>
                                <option value="0" <?= isset($filter['available']) && $filter['available'] === false ? 'selected' : '' ?>>Indisponível</option>
                            </select>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-body">
                    <?php if (empty($meals)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-utensils fa-3x text-muted mb-3"></i>
                            <h5>Nenhuma refeição encontrada</h5>
                            <p class="text-muted">Comece adicionando uma nova refeição ao seu cardápio.</p>
                            <a href="/restaurant/meals/create" class="btn btn-primary mt-2">
                                <i class="fas fa-plus-circle me-2"></i> Nova Refeição
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Tipo</th>
                                        <th>Preço</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($meals as $meal): ?>
                                        <tr>
                                            <td><?= $meal['name'] ?></td>
                                            <td><?= Formatter::getMealTypeName($meal['meal_type']) ?></td>
                                            <td><?= Formatter::formatCurrency($meal['price']) ?></td>
                                            <td>
                                                <span class="badge <?= $meal['available'] ? 'bg-success' : 'bg-danger' ?>">
                                                    <?= $meal['available'] ? 'Disponível' : 'Indisponível' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="/restaurant/meals/view?id=<?= $meal['id'] ?>" class="btn btn-outline-primary" title="Ver Detalhes">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="/restaurant/meals/edit?id=<?= $meal['id'] ?>" class="btn btn-outline-primary" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
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
</div>