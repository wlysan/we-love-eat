<?php $title = 'Detalhes do Pedido - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Pedido #<?= $order['id'] ?></h2>
            <p class="text-muted">
                Realizado em <?= Formatter::formatDateTime($order['created_at']) ?> | 
                Status: <span class="badge bg-<?= getStatusBadgeColor($order['status']) ?>"><?= formatOrderStatus($order['status']) ?></span>
            </p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="/meals/orders" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Voltar para Pedidos
            </a>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-8">
            <!-- Order Items -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Itens do Pedido</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Refeição</th>
                                    <th>Tipo</th>
                                    <th>Restaurante</th>
                                    <th class="text-center">Qtd</th>
                                    <th class="text-end">Preço</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $restaurantCounts = [];
                                foreach ($orderItems as $item): 
                                    // Count items per restaurant for summary
                                    if (!isset($restaurantCounts[$item['restaurant_id']])) {
                                        $restaurantCounts[$item['restaurant_id']] = [
                                            'name' => $item['restaurant_name'],
                                            'count' => 0
                                        ];
                                    }
                                    $restaurantCounts[$item['restaurant_id']]['count']++;
                                ?>
                                    <tr>
                                        <td><?= $item['meal_name'] ?></td>
                                        <td><?= Formatter::getMealTypeName($item['meal_type']) ?></td>
                                        <td><?= $item['restaurant_name'] ?></td>
                                        <td class="text-center"><?= $item['quantity'] ?></td>
                                        <td class="text-end"><?= Formatter::formatCurrency($item['price']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="4" class="text-end">Total</th>
                                    <th class="text-end"><?= Formatter::formatCurrency($order['total']) ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Order Timeline -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Status do Pedido</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item <?= in_array($order['status'], ['pending', 'processing', 'shipped', 'delivered']) ? 'completed' : '' ?>">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Pedido Recebido</h6>
                                <p class="text-muted small"><?= Formatter::formatDateTime($order['created_at']) ?></p>
                            </div>
                        </div>
                        
                        <div class="timeline-item <?= in_array($order['status'], ['processing', 'shipped', 'delivered']) ? 'completed' : '' ?>">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Em Preparação</h6>
                                <p class="text-muted small">Os restaurantes estão preparando seus pedidos</p>
                            </div>
                        </div>
                        
                        <div class="timeline-item <?= in_array($order['status'], ['shipped', 'delivered']) ? 'completed' : '' ?>">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Saiu para Entrega</h6>
                                <p class="text-muted small">Seu pedido está a caminho do endereço informado</p>
                            </div>
                        </div>
                        
                        <div class="timeline-item <?= $order['status'] === 'delivered' ? 'completed' : '' ?>">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Entregue</h6>
                                <p class="text-muted small">Previsto para <?= Formatter::formatDate($order['delivery_date']) ?></p>
                            </div>
                        </div>
                        
                        <?php if ($order['status'] === 'canceled'): ?>
                            <div class="timeline-item completed canceled">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6>Cancelado</h6>
                                    <p class="text-muted small">Este pedido foi cancelado</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Order Summary -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Resumo</h5>
                </div>
                <div class="card-body">
                    <p><strong>Método de Pagamento:</strong><br>
                        <?= formatPaymentMethod($order['payment_method']) ?>
                    </p>
                    
                    <p><strong>Endereço de Entrega:</strong><br>
                        <?= nl2br(htmlspecialchars($order['delivery_address'])) ?>
                    </p>
                    
                    <p><strong>Data de Entrega:</strong><br>
                        <?= Formatter::formatDate($order['delivery_date']) ?>
                    </p>
                    
                    <?php if (!empty($order['notes'])): ?>
                        <p><strong>Observações:</strong><br>
                            <?= nl2br(htmlspecialchars($order['notes'])) ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Restaurants Summary -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Restaurantes</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <?php foreach ($restaurantCounts as $restaurantId => $restaurant): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?= $restaurant['name'] ?>
                                <span class="badge bg-primary rounded-pill"><?= $restaurant['count'] ?> <?= $restaurant['count'] > 1 ? 'itens' : 'item' ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <a href="/meals/orders" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Voltar para Pedidos
                        </a>
                        
                        <?php if ($order['status'] === 'pending'): ?>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                                <i class="fas fa-times me-2"></i> Cancelar Pedido
                            </button>
                            
                            <!-- Cancel Order Modal -->
                            <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="cancelOrderModalLabel">Confirmar Cancelamento</h5>
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
                        
                        <?php if ($order['status'] === 'delivered'): ?>
                            <a href="#" class="btn btn-primary">
                                <i class="fas fa-star me-2"></i> Avaliar Pedido
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Timeline styles */
.timeline {
    position: relative;
    padding-left: 40px;
}

.timeline::before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 15px;
    width: 2px;
    background-color: #e0e0e0;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
    opacity: 0.5;
}

.timeline-item.completed {
    opacity: 1;
}

.timeline-item.canceled .timeline-marker {
    background-color: #dc3545;
}

.timeline-marker {
    position: absolute;
    top: 0;
    left: -40px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background-color: #e0e0e0;
    border: 4px solid #fff;
    z-index: 1;
}

.timeline-item.completed .timeline-marker {
    background-color: #28a745;
}

.timeline-content {
    margin-left: 10px;
}

.timeline-content h6 {
    margin-bottom: 5px;
}
</style>

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

// Helper function to format payment method
function formatPaymentMethod($method) {
    $methods = [
        'credit' => 'Cartão de Crédito',
        'debit' => 'Cartão de Débito',
        'pix' => 'PIX',
        'money' => 'Dinheiro'
    ];
    return $methods[$method] ?? $method;
}
?>