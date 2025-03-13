<?php $title = 'Detalhes do Pedido - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Pedido #<?= $order['id'] ?></h2>
            <p class="text-muted">
                Realizado por <?= $order['user_name'] ?> em <?= Formatter::formatDateTime($order['created_at']) ?> | 
                Status: <span class="badge bg-<?= getStatusBadgeColor($order['status']) ?>"><?= formatOrderStatus($order['status']) ?></span>
            </p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="/restaurant/orders" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Voltar para Pedidos
            </a>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-8">
            <!-- Order Items -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Itens do Pedido</h5>
                    <?php if ($hasOtherRestaurants): ?>
                        <span class="badge bg-info">
                            <i class="fas fa-info-circle me-1"></i> Pedido compartilhado com outros restaurantes
                        </span>
                    <?php endif; ?>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Refeição</th>
                                    <th>Tipo</th>
                                    <th class="text-center">Qtd</th>
                                    <th class="text-end">Preço</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $totalPrice = 0;
                                foreach ($orderItems as $item): 
                                    $totalPrice += $item['price'] * $item['quantity'];
                                ?>
                                    <tr>
                                        <td><?= $item['meal_name'] ?></td>
                                        <td><?= Formatter::getMealTypeName($item['meal_type']) ?></td>
                                        <td class="text-center"><?= $item['quantity'] ?></td>
                                        <td class="text-end"><?= Formatter::formatCurrency($item['price']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="3" class="text-end">Subtotal (seus itens)</th>
                                    <th class="text-end"><?= Formatter::formatCurrency($totalPrice) ?></th>
                                </tr>
                                <?php if ($hasOtherRestaurants): ?>
                                <tr>
                                    <td colspan="4" class="text-muted small">
                                        <i class="fas fa-info-circle me-1"></i> Este pedido contém itens de outros restaurantes que não são exibidos aqui.
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Order Notes -->
            <?php if (!empty($order['notes'])): ?>
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Observações do Cliente</h5>
                </div>
                <div class="card-body">
                    <?= nl2br(htmlspecialchars($order['notes'])) ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Order Status Update -->
            <?php if (in_array($order['status'], ['pending', 'processing', 'shipped'])): ?>
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Atualizar Status</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/restaurant/orders/update-status">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Novo Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <?php if ($order['status'] === 'pending'): ?>
                                    <option value="processing">Em Processamento</option>
                                <?php elseif ($order['status'] === 'processing'): ?>
                                    <option value="shipped">Enviado para Entrega</option>
                                <?php elseif ($order['status'] === 'shipped'): ?>
                                    <option value="delivered">Entregue</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Observações (opcional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                            <div class="form-text">Adicione informações relevantes sobre a atualização do status.</div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Atualizar Status</button>
                    </form>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="col-md-4">
            <!-- Delivery Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Informações de Entrega</h5>
                </div>
                <div class="card-body">
                    <p><strong>Data de Entrega:</strong><br>
                        <?= Formatter::formatDate($order['delivery_date']) ?>
                    </p>
                    
                    <p><strong>Endereço:</strong><br>
                        <?= nl2br(htmlspecialchars($order['delivery_address'])) ?>
                    </p>
                    
                    <p><strong>Cliente:</strong><br>
                        <?= $order['user_name'] ?><br>
                        <?= $order['user_email'] ?>
                    </p>
                    
                    <p><strong>Método de Pagamento:</strong><br>
                        <?= formatPaymentMethod($order['payment_method']) ?>
                    </p>
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
                                <p class="text-muted small">Os itens estão sendo preparados</p>
                            </div>
                        </div>
                        
                        <div class="timeline-item <?= in_array($order['status'], ['shipped', 'delivered']) ? 'completed' : '' ?>">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Saiu para Entrega</h6>
                                <p class="text-muted small">Pedido enviado para o entregador</p>
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