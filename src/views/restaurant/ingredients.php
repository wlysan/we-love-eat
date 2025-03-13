<?php $title = 'Gerenciar Ingredientes - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Gerenciar Ingredientes</h2>
            <p class="text-muted">Cadastre e gerencie os ingredientes utilizados nas refeições do seu restaurante.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="/restaurant/ingredients/create" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i> Novo Ingrediente
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <form class="row g-3" method="GET" action="/restaurant/ingredients">
                        <div class="col-md-10">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" name="search" value="<?= $search ?? '' ?>" placeholder="Buscar ingredientes...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Buscar</button>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <?php if (empty($ingredients)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-carrot fa-3x text-muted mb-3"></i>
                            <h5>Nenhum ingrediente encontrado</h5>
                            <p class="text-muted">Comece adicionando ingredientes para criar suas refeições.</p>
                            <a href="/restaurant/ingredients/create" class="btn btn-primary mt-2">
                                <i class="fas fa-plus-circle me-2"></i> Novo Ingrediente
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Calorias (100g)</th>
                                        <th>Proteínas (g)</th>
                                        <th>Carboidratos (g)</th>
                                        <th>Gorduras (g)</th>
                                        <th>Fibras (g)</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ingredients as $ingredient): ?>
                                        <tr>
                                            <td><?= $ingredient['name'] ?></td>
                                            <td><?= $ingredient['calories'] ?></td>
                                            <td><?= $ingredient['protein'] ?></td>
                                            <td><?= $ingredient['carbs'] ?></td>
                                            <td><?= $ingredient['fat'] ?></td>
                                            <td><?= $ingredient['fiber'] !== null ? $ingredient['fiber'] : '-' ?></td>
                                            <td>
                                                <a href="/restaurant/ingredients/edit?id=<?= $ingredient['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i> Editar
                                                </a>
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