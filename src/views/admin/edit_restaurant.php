<?php $title = 'Editar Restaurante - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col">
            <h2>Editar Restaurante</h2>
            <p class="text-muted">Editar informações do restaurante <?= $user['name'] ?>.</p>
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
                    
                    <form method="POST" action="/admin/restaurants/edit?id=<?= $restaurant['id'] ?>">
                        <h5 class="mb-3">Informações da Conta</h5>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome do Restaurante</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= $user['name'] ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= $user['email'] ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Nova Senha (deixe em branco para manter a atual)</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <div class="form-text">A senha deve ter pelo menos 6 caracteres.</div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h5 class="mb-3">Informações do Estabelecimento</h5>
                        
                        <div class="mb-3">
                            <label for="cnpj" class="form-label">CNPJ</label>
                            <input type="text" class="form-control" id="cnpj" name="cnpj" value="<?= $restaurant['cnpj'] ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Endereço Completo</label>
                            <input type="text" class="form-control" id="address" name="address" value="<?= $restaurant['address'] ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Telefone</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="<?= $restaurant['phone'] ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Descrição</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?= $restaurant['description'] ?? '' ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="delivery_areas" class="form-label">Áreas de Entrega</label>
                            <textarea class="form-control" id="delivery_areas" name="delivery_areas" rows="2"><?= $restaurant['delivery_areas'] ?? '' ?></textarea>
                            <div class="form-text">Separe as áreas por vírgulas (ex: Centro, Zona Sul, Barra Funda).</div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h5 class="mb-3">Estatísticas</h5>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center py-3">
                                        <h6 class="card-title text-muted mb-1">Total de Refeições</h6>
                                        <h3 class="card-text">
                                            <?php
                                            // This would typically come from the controller
                                            echo isset($stats['mealCount']) ? $stats['mealCount'] : '0';
                                            ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center py-3">
                                        <h6 class="card-title text-muted mb-1">Refeições Ativas</h6>
                                        <h3 class="card-text">
                                            <?php
                                            // This would typically come from the controller
                                            echo isset($stats['activeMealCount']) ? $stats['activeMealCount'] : '0';
                                            ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center py-3">
                                        <h6 class="card-title text-muted mb-1">Seleções</h6>
                                        <h3 class="card-text">
                                            <?php
                                            // This would typically come from the controller
                                            echo isset($stats['selectionCount']) ? $stats['selectionCount'] : '0';
                                            ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="/admin/restaurants" class="btn btn-outline-secondary me-md-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>