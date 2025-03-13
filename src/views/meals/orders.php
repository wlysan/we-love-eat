<?php $title = 'Meus Pedidos - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Meus Pedidos</h2>
            <p class="text-muted">Acompanhe e gerencie seus pedidos de refeições.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="/meals/catalog" class="btn btn-primary">
                <i class="fas fa-utensils me-2"></i> Catálogo de Refeições
            </a>
        </div>
    </div>
    
    <?php if (isset($success) && $success && isset($orderId)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> Pedido #<?= $orderId ?> realizado com sucesso! Você pode acompanhar o status abaixo.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['success']) && $_GET['success'] === 'canceled'): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i> Pedido cancelado com sucesso!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error']) && $_GET['error'] === 'cannot_cancel'): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> Não é possível cancelar este pedido, pois ele já está em processamento ou foi entregue.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <?php if (empty($orders)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                            <h5>Você ainda não fez nenhum pedido</h5>
                            <p class="text-muted">Explore o catálogo de refeições e faça seu primeiro pedido.</p>
                            <a href="/meals/catalog" class="btn btn-primary mt-2">
                                <i class="fas fa-utensils me-2"></i> Ver Catálogo
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Número</th>
                                        <th>Data</th>
                                        <th>Entrega</th>
                                        <th>Itens</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td>#<?= $order['id'] ?></td>
                                            <td><?= Formatter::formatDateTime($order['created_at']) ?></td>
                                            <td><?= Formatter::formatDate($order['delivery_date']) ?></td>
                                            <td><?= $order['item_count'] ?></td>
                                            <td><?= Formatter::formatCurrency($order['total']) ?></td>
                                            <td>
                                                <span class="badge bg-<?= getStatusBadgeColor($order['status']) ?>">
                                                    <?= formatOrderStatus($order['status']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="/meals/orders/view?id=<?= $order['id'] ?>" class="btn btn-outline-primary" title="Ver Detalhes">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php if ($order['status'] === 'pending'): ?>
                                                        <button type="button" class="btn btn-outline-danger" title="Cancelar Pedido" 
                                                                data-bs-toggle="modal" data-bs-target="#cancelOrderModal<?= $order['id'] ?>">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <!-- Cancel Order Modal -->
                                                <?php if ($order['status'] === 'pending'): ?>
                                                    <div class="modal fade" id="cancelOrderModal<?= $order['id'] ?>" tabindex="-1" 
                                                         aria-labelledby="cancelOrderModalLabel<?= $order['id'] ?>" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="cancelOrderModalLabel<?= $order['id'] ?>">Confirmar Cancelamento</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Tem certeza que deseja cancelar o pedido #<?= $order['id'] ?>?</p>
                                                                    <p class="text-danger">Esta ação não pode ser desfeita.</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Voltar</button>
                                                                    <form method="POST" action="/meals/orders/cancel">
                                                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                                                        <button type="submit" class="btn btn-danger">Cancelar Pedido</button>
                                                                    </form>
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