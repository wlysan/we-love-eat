<?php $title = 'Meus Pacotes - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Meus Pacotes de Refeições</h2>
            <p class="text-muted">Gerencie seus pacotes de refeições personalizados.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="/meals/packages" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i> Novo Pacote
            </a>
        </div>
    </div>
    
    <?php if (isset($_GET['success']) && $_GET['success'] === 'canceled'): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i> Pacote cancelado com sucesso!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error']) && $_GET['error'] === 'cannot_cancel'): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> Não é possível cancelar este pacote.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab" aria-controls="active" aria-selected="true">
                                Ativos
                                <?php if (!empty($activePackages)): ?>
                                    <span class="badge bg-primary ms-1"><?= count($activePackages) ?></span>
                                <?php endif; ?>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="false">
                                Pendentes
                                <?php if (!empty($pendingPackages)): ?>
                                    <span class="badge bg-warning ms-1"><?= count($pendingPackages) ?></span>
                                <?php endif; ?>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab" aria-controls="completed" aria-selected="false">
                                Concluídos
                                <?php if (!empty($completedPackages)): ?>
                                    <span class="badge bg-info ms-1"><?= count($completedPackages) ?></span>
                                <?php endif; ?>
                            </button>
                        </li>
                    </ul>
                </div>
                
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Active Packages -->
                        <div class="tab-pane fade show active" id="active" role="tabpanel" aria-labelledby="active-tab">
                            <?php if (empty($activePackages)): ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                    <h5>Você não possui pacotes ativos no momento</h5>
                                    <p class="text-muted">Crie um novo pacote de refeições ou verifique seus pacotes pendentes.</p>
                                    <a href="/meals/packages" class="btn btn-primary mt-2">
                                        <i class="fas fa-plus-circle me-2"></i> Criar Pacote
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th>Tipo</th>
                                                <th>Período</th>
                                                <th>Refeições</th>
                                                <th>Valor</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($activePackages as $package): ?>
                                                <tr>
                                                    <td><?= $package['name'] ?></td>
                                                    <td><?= ucfirst(getPackageTypeName($package['type'])) ?></td>
                                                    <td>
                                                        <?= Formatter::formatDate($package['start_date']) ?>
                                                        <?php if ($package['start_date'] !== $package['end_date']): ?>
                                                            <br>até <?= Formatter::formatDate($package['end_date']) ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= $package['meal_count'] ?></td>
                                                    <td><?= Formatter::formatCurrency($package['total_price']) ?></td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="/meals/packages/view?id=<?= $package['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-eye"></i> Ver
                                                            </a>
                                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal<?= $package['id'] ?>">
                                                                <i class="fas fa-times"></i> Cancelar
                                                            </button>
                                                        </div>
                                                        
                                                        <!-- Cancel Modal -->
                                                        <div class="modal fade" id="cancelModal<?= $package['id'] ?>" tabindex="-1" aria-labelledby="cancelModalLabel<?= $package['id'] ?>" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="cancelModalLabel<?= $package['id'] ?>">Confirmar Cancelamento</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p>Tem certeza que deseja cancelar o pacote <strong><?= $package['name'] ?></strong>?</p>
                                                                        <p class="text-danger">Esta ação não pode ser desfeita e também cancelará qualquer pedido pendente relacionado a este pacote.</p>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Voltar</button>
                                                                        <form method="POST" action="/meals/packages/cancel">
                                                                            <input type="hidden" name="package_id" value="<?= $package['id'] ?>">
                                                                            <button type="submit" class="btn btn-danger">Cancelar Pacote</button>
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
                        
                        <!-- Pending Packages -->
                        <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                            <?php if (empty($pendingPackages)): ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                                    <h5>Você não possui pacotes pendentes</h5>
                                    <p class="text-muted">Seus pacotes pendentes aparecerão aqui.</p>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th>Tipo</th>
                                                <th>Período</th>
                                                <th>Refeições</th>
                                                <th>Valor</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($pendingPackages as $package): ?>
                                                <tr>
                                                    <td><?= $package['name'] ?></td>
                                                    <td><?= ucfirst(getPackageTypeName($package['type'])) ?></td>
                                                    <td>
                                                        <?= Formatter::formatDate($package['start_date']) ?>
                                                        <?php if ($package['start_date'] !== $package['end_date']): ?>
                                                            <br>até <?= Formatter::formatDate($package['end_date']) ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= $package['meal_count'] ?></td>
                                                    <td><?= Formatter::formatCurrency($package['total_price']) ?></td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="/meals/packages/view?id=<?= $package['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-eye"></i> Ver
                                                            </a>
                                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal<?= $package['id'] ?>">
                                                                <i class="fas fa-times"></i> Cancelar
                                                            </button>
                                                        </div>
                                                        
                                                        <!-- Cancel Modal -->
                                                        <div class="modal fade" id="cancelModal<?= $package['id'] ?>" tabindex="-1" aria-labelledby="cancelModalLabel<?= $package['id'] ?>" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="cancelModalLabel<?= $package['id'] ?>">Confirmar Cancelamento</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p>Tem certeza que deseja cancelar o pacote <strong><?= $package['name'] ?></strong>?</p>
                                                                        <p class="text-danger">Esta ação não pode ser desfeita e também cancelará qualquer pedido pendente relacionado a este pacote.</p>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Voltar</button>
                                                                        <form method="POST" action="/meals/packages/cancel">
                                                                            <input type="hidden" name="package_id" value="<?= $package['id'] ?>">
                                                                            <button type="submit" class="btn btn-danger">Cancelar Pacote</button>
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
                        
                        <!-- Completed Packages -->
                        <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                            <?php if (empty($completedPackages)): ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                                    <h5>Você ainda não possui pacotes concluídos</h5>
                                    <p class="text-muted">Seus pacotes concluídos aparecerão aqui.</p>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th>Tipo</th>
                                                <th>Período</th>
                                                <th>Refeições</th>
                                                <th>Valor</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($completedPackages as $package): ?>
                                                <tr>
                                                    <td><?= $package['name'] ?></td>
                                                    <td><?= ucfirst(getPackageTypeName($package['type'])) ?></td>
                                                    <td>
                                                        <?= Formatter::formatDate($package['start_date']) ?>
                                                        <?php if ($package['start_date'] !== $package['end_date']): ?>
                                                            <br>até <?= Formatter::formatDate($package['end_date']) ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= $package['meal_count'] ?></td>
                                                    <td><?= Formatter::formatCurrency($package['total_price']) ?></td>
                                                    <td>
                                                        <a href="/meals/packages/view?id=<?= $package['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i> Ver
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
    </div>
</div>

<?php
// Helper function to get package type name
function getPackageTypeName($type) {
    $types = [
        'day' => 'diário',
        'week' => 'semanal',
        'month' => 'mensal'
    ];
    return $types[$type] ?? $type;
}
?>