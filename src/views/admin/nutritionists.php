<?php $title = 'Gerenciar Nutricionistas - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Gerenciar Nutricionistas</h2>
            <p class="text-muted">Gerencie os nutricionistas cadastrados na plataforma.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="/admin/nutritionists/create" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i> Novo Nutricionista
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <form class="row g-3" method="GET" action="/admin/nutritionists">
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" name="search" value="<?= $_GET['search'] ?? '' ?>" placeholder="Buscar por nome, email ou especialidade...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Buscar</button>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <?php if (empty($nutritionists)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-user-md fa-3x text-muted mb-3"></i>
                            <h5>Nenhum nutricionista encontrado</h5>
                            <p class="text-muted">Tente ajustar seus filtros de busca ou adicione um novo nutricionista.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>CRN</th>
                                        <th>Especialidades</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($nutritionists as $nutritionist): ?>
                                        <tr>
                                            <td><?= $nutritionist['id'] ?></td>
                                            <td><?= $nutritionist['name'] ?></td>
                                            <td><?= $nutritionist['email'] ?></td>
                                            <td><?= $nutritionist['professional_id'] ?></td>
                                            <td><?= $nutritionist['specialties'] ?? 'Não informado' ?></td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="/admin/nutritionists/edit?id=<?= $nutritionist['id'] ?>" class="btn btn-outline-primary" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger" title="Excluir" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $nutritionist['id'] ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                                
                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="deleteModal<?= $nutritionist['id'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $nutritionist['id'] ?>" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel<?= $nutritionist['id'] ?>">Confirmar Exclusão</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Tem certeza que deseja excluir o nutricionista <strong><?= $nutritionist['name'] ?></strong>?</p>
                                                                <p class="text-danger">Esta ação não pode ser desfeita e irá remover todos os planos de dieta associados a este nutricionista.</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                                <form method="POST" action="/admin/nutritionists/delete" class="d-inline">
                                                                    <input type="hidden" name="id" value="<?= $nutritionist['id'] ?>">
                                                                    <button type="submit" class="btn btn-danger">Excluir</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
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