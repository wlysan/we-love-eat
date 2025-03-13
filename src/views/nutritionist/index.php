<?php $title = 'Nutricionistas - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col">
            <h2>Encontre um Nutricionista</h2>
            <p class="text-muted">Conecte-se com nutricionistas qualificados para receber orientação nutricional personalizada.</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">Filtrar</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="/nutritionists">
                        <div class="mb-3">
                            <label for="search" class="form-label">Buscar</label>
                            <input type="text" class="form-control" id="search" name="search" value="<?= $_GET['search'] ?? '' ?>" placeholder="Nome ou especialidade...">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Especialidades</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="spec_emagrecimento" name="specialty[]" value="emagrecimento" <?= isset($_GET['specialty']) && in_array('emagrecimento', $_GET['specialty']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="spec_emagrecimento">Emagrecimento</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="spec_esportiva" name="specialty[]" value="esportiva" <?= isset($_GET['specialty']) && in_array('esportiva', $_GET['specialty']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="spec_esportiva">Nutrição Esportiva</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="spec_clinica" name="specialty[]" value="clinica" <?= isset($_GET['specialty']) && in_array('clinica', $_GET['specialty']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="spec_clinica">Nutrição Clínica</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="spec_vegetariana" name="specialty[]" value="vegetariana" <?= isset($_GET['specialty']) && in_array('vegetariana', $_GET['specialty']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="spec_vegetariana">Alimentação Vegetariana</label>
                            </div>
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
                    <?php if (empty($nutritionists)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-user-md fa-3x text-muted mb-3"></i>
                            <h5>Nenhum nutricionista encontrado</h5>
                            <p class="text-muted">Tente ajustar seus filtros de busca.</p>
                        </div>
                    <?php else: ?>
                        <div class="row row-cols-1 row-cols-md-2 g-4">
                            <?php foreach ($nutritionists as $nutritionist): ?>
                                <div class="col">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                <?= $nutritionist['name'] ?>
                                                <span class="badge bg-primary ms-2">CRN: <?= $nutritionist['professional_id'] ?></span>
                                            </h5>
                                            
                                            <?php if (!empty($nutritionist['specialties'])): ?>
                                                <p class="card-text">
                                                    <strong>Especialidades:</strong> <?= $nutritionist['specialties'] ?>
                                                </p>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($nutritionist['bio'])): ?>
                                                <p class="card-text">
                                                    <?= nl2br(htmlspecialchars(substr($nutritionist['bio'], 0, 150))) ?>
                                                    <?= strlen($nutritionist['bio']) > 150 ? '...' : '' ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="card-footer bg-white">
                                            <div class="btn-group w-100">
                                                <a href="/nutritionists/view?id=<?= $nutritionist['id'] ?>" class="btn btn-outline-primary">
                                                    <i class="fas fa-info-circle me-1"></i> Ver Perfil
                                                </a>
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#chatModal<?= $nutritionist['id'] ?>">
                                                    <i class="fas fa-comments me-1"></i> Iniciar Conversa
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Chat Modal -->
                                <div class="modal fade" id="chatModal<?= $nutritionist['id'] ?>" tabindex="-1" aria-labelledby="chatModalLabel<?= $nutritionist['id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="/chats/create">
                                                <input type="hidden" name="nutritionist_id" value="<?= $nutritionist['id'] ?>">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="chatModalLabel<?= $nutritionist['id'] ?>">Iniciar Conversa com <?= $nutritionist['name'] ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="message<?= $nutritionist['id'] ?>" class="form-label">Mensagem Inicial (opcional)</label>
                                                        <textarea class="form-control" id="message<?= $nutritionist['id'] ?>" name="message" rows="4" placeholder="Escreva uma mensagem para o nutricionista..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-primary">Iniciar Conversa</button>
                                                </div>
                                            </form>
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