<?php $title = 'Gerenciar Restaurantes - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Gerenciar Restaurantes</h2>
            <p class="text-muted">Gerencie os restaurantes parceiros cadastrados na plataforma.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="/admin/restaurants/create" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i> Novo Restaurante
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <form class="row g-3" method="GET" action="/admin/restaurants">
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" name="search" value="<?= $_GET['search'] ?? '' ?>" placeholder="Buscar por nome ou endereço...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Buscar</button>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <?php if (empty($restaurants)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-utensils fa-3x text-muted mb-3"></i>
                            <h5>Nenhum restaurante encontrado</h5>
                            <p class="text-muted">Tente ajustar seus filtros de busca ou adicione um novo restaurante.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>CNPJ</th>
                                        <th>Endereço</th>
                                        <th>Telefone</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($restaurants as $restaurant): ?>
                                        <tr>
                                            <td><?= $restaurant['id'] ?></td>
                                            <td><?= $restaurant['name'] ?></td>
                                            <td><?= $restaurant['cnpj'] ?></td>
                                            <td><?= $restaurant['address'] ?></td>
                                            <td><?= $restaurant['phone'] ?></td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="/admin/restaurants/edit?id=<?= $restaurant['id'] ?>" class="btn btn-outline-primary" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger" title="Excluir" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $restaurant['id'] ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                                
                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="deleteModal<?= $restaurant['id'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $restaurant['id'] ?>" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel<?= $restaurant['id'] ?>">Confirmar Exclusão</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Tem certeza que deseja excluir o restaurante <strong><?= $restaurant['name'] ?></strong>?</p>
                                                                <p class="text-danger">Esta ação não pode ser desfeita e irá remover todas as refeições associadas a este restaurante.</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                                <form method="POST" action="/admin/restaurants/delete" class="d-inline">
                                                                    <input type="hidden" name="id" value="<?= $restaurant['id'] ?>">
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