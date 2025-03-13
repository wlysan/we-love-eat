<?php $title = 'Gerenciar Pedidos - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Gerenciar Pedidos</h2>
            <p class="text-muted">Visualize e gerencie os pedidos recebidos para seu restaurante.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="/restaurant/dashboard" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Voltar ao Dashboard
            </a>
        </div>
    </div>
    
    <?php if (isset($_GET['success']) && $_GET['success'] === 'updated'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> Status do pedido atualizado com sucesso!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> 
            <?php if ($_GET['error'] === 'invalid_status'): ?>
                Status inválido. Por favor tente novamente.
            <?php elseif ($_GET['error'] === 'not_found'): ?>
                Pedido não encontrado ou não pertence ao seu restaurante.
            <?php else: ?>
                Ocorreu um erro. Por favor tente novamente.
            <?php endif; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="GET" action="/restaurant/orders">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label for="status" class="form-label">Filtrar por Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">Todos os status</option>
                                    <option value="pending" <?= $statusFilter === 'pending' ? 'selected' : '' ?>>Pendente</option>
                                    <option value="processing" <?= $statusFilter === 'processing' ? 'selected' : '' ?>>Em Processamento</option>
                                    <option value="shipped" <?= $statusFilter === 'shipped' ? 'selected' : '' ?>>Enviado</option>
                                    <option value="delivered" <?= $statusFilter === 'delivered' ? 'selected' : '' ?>>Entregue</option>
                                    <option value="canceled" <?= $statusFilter === 'canceled' ? 'selected' : '' ?>>Cancelado</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <div class="d-flex">
                                    <button type="submit" class="btn btn-primary flex-grow-1 me-2">
                                        <i class="fas fa-filter me-1"></i> Filtrar
                                    </button>
                                    <a href="/restaurant/orders" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <?php if (empty($orders)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                            <h5>Nenhum pedido encontrado</h5>
                            <p class="text-muted">Não há pedidos que correspondam aos critérios de filtro selecionados.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Número</th>
                                        <th>Cliente</th>
                                        <th>Data</th>
                                        <th>Entrega</th>
                                        <th>Itens</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td>#<?= $order['id'] ?></td>
                                            <td><?= $order['user_name'] ?></td>
                                            <td><?= Formatter::formatDateTime($order['created_at']) ?></td>
                                            <td><?= Formatter::formatDate($order['delivery_date']) ?></td>
                                            <td><?= $order['item_count'] ?></td>
                                            <td>
                                                <span class="badge bg-<?= getStatusBadgeColor($order['status']) ?>">
                                                    <?= formatOrderStatus($order['status']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="/restaurant/orders/view?id=<?= $order['id'] ?>" class="btn btn-outline-primary" title="Ver Detalhes">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php if ($order['status'] === 'pending'): ?>
                                                        <button type="button" class="btn btn-outline-success" title="Iniciar Processamento" 
                                                                data-bs-toggle="modal" data-bs-target="#updateStatusModal<?= $order['id'] ?>">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <!-- Update Status Modal -->
                                                <?php if ($order['status'] === 'pending'): ?>
                                                    <div class="modal fade" id="updateStatusModal<?= $order['id'] ?>" tabindex="-1" 
                                                         aria-labelledby="updateStatusModalLabel<?= $order['id'] ?>" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="updateStatusModalLabel<?= $order['id'] ?>">Atualizar Status do Pedido</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form method="POST" action="/restaurant/orders/update-status" id="updateStatusForm<?= $order['id'] ?>">
                                                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                                                        <input type="hidden" name="status" value="processing">
                                                                        
                                                                        <p>Deseja iniciar o processamento do pedido #<?= $order['id'] ?>?</p>
                                                                        
                                                                        <div class="mb-3">
                                                                            <label for="notes<?= $order['id'] ?>" class="form-label">Observações (opcional)</label>
                                                                            <textarea class="form-control" id="notes<?= $order['id'] ?>" name="notes" rows="2"></textarea>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                                    <button type="submit" form="updateStatusForm<?= $order['id'] ?>" class="btn btn-success">Iniciar Processamento</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
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

<?php
// Helper function to get badge color based on order status
function getStatusBadgeColor($status) {
    $colors = [
        'pending' => 'warning',
        'processing' => 'info',
        'shipped' => 'primary',
        'delivered' => 'success',
        'canceled' => 'danger'
    ];
    return $colors[$status] ?? 'secondary';
}

// Helper function to format order status
function formatOrderStatus($status) {
    $statuses = [
        'pending' => 'Pendente',
        'processing' => 'Processando',
        'shipped' => 'Enviado',
        'delivered' => 'Entregue',
        'canceled' => 'Cancelado'
    ];
    return $statuses[$status] ?? $status;
}
?>