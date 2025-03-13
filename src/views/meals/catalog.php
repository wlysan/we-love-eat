<?php $title = 'Catálogo de Refeições - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Catálogo de Refeições</h2>
            <p class="text-muted">Explore as refeições disponíveis nos restaurantes parceiros e monte seu pedido.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cartModal">
                <i class="fas fa-shopping-cart me-2"></i> Meu Pedido <span id="cartCount" class="badge bg-light text-dark ms-1">0</span>
            </button>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="GET" action="/meals/catalog" id="filterForm">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label for="restaurant" class="form-label">Restaurante</label>
                                <select class="form-select" id="restaurant" name="restaurant_id">
                                    <option value="">Todos os restaurantes</option>
                                    <?php foreach ($restaurants as $restaurant): ?>
                                        <option value="<?= $restaurant['id'] ?>" <?= isset($_GET['restaurant_id']) && $_GET['restaurant_id'] == $restaurant['id'] ? 'selected' : '' ?>>
                                            <?= $restaurant['name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-2">
                                <label for="meal_type" class="form-label">Tipo</label>
                                <select class="form-select" id="meal_type" name="meal_type">
                                    <option value="">Todos os tipos</option>
                                    <option value="breakfast" <?= isset($_GET['meal_type']) && $_GET['meal_type'] == 'breakfast' ? 'selected' : '' ?>>Café da Manhã</option>
                                    <option value="lunch" <?= isset($_GET['meal_type']) && $_GET['meal_type'] == 'lunch' ? 'selected' : '' ?>>Almoço</option>
                                    <option value="dinner" <?= isset($_GET['meal_type']) && $_GET['meal_type'] == 'dinner' ? 'selected' : '' ?>>Jantar</option>
                                    <option value="snack" <?= isset($_GET['meal_type']) && $_GET['meal_type'] == 'snack' ? 'selected' : '' ?>>Lanche</option>
                                </select>
                            </div>
                            
                            <div class="col-md-2">
                                <label for="max_calories" class="form-label">Calorias Máx.</label>
                                <input type="number" class="form-control" id="max_calories" name="max_calories" value="<?= $_GET['max_calories'] ?? '' ?>" min="0" placeholder="Qualquer">
                            </div>
                            
                            <div class="col-md-2">
                                <label for="min_protein" class="form-label">Proteína Mín.</label>
                                <input type="number" class="form-control" id="min_protein" name="min_protein" value="<?= $_GET['min_protein'] ?? '' ?>" min="0" placeholder="Qualquer">
                            </div>
                            
                            <div class="col-md-3">
                                <div class="d-flex">
                                    <button type="submit" class="btn btn-primary flex-grow-1 me-2">
                                        <i class="fas fa-filter me-1"></i> Filtrar
                                    </button>
                                    <a href="/meals/catalog" class="btn btn-outline-secondary">
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
    
    <!-- Meals Grid -->
    <div class="row mb-4">
        <?php if (empty($meals)): ?>
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Nenhuma refeição encontrada com os filtros selecionados.
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($meals as $meal): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm" id="meal-<?= $meal['id'] ?>">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0 meal-name"><?= $meal['name'] ?></h5>
                                <span class="badge bg-<?= getMealTypeBadgeColor($meal['meal_type']) ?>">
                                    <?= Formatter::getMealTypeName($meal['meal_type']) ?>
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-2">Por: <?= $meal['restaurant_name'] ?></p>
                            
                            <?php if (!empty($meal['description'])): ?>
                                <p class="meal-description"><?= $meal['description'] ?></p>
                            <?php endif; ?>
                            
                            <div class="d-flex justify-content-between mb-3">
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-fire text-danger me-1"></i> <?= $meal['nutrition']['calories'] ?? '?' ?> kcal
                                </span>
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-drumstick-bite text-success me-1"></i> <?= $meal['nutrition']['protein'] ?? '?' ?>g
                                </span>
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-bread-slice text-warning me-1"></i> <?= $meal['nutrition']['carbs'] ?? '?' ?>g
                                </span>
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-cheese text-primary me-1"></i> <?= $meal['nutrition']['fat'] ?? '?' ?>g
                                </span>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-0">
                                <h5 class="text-primary mb-0 meal-price"><?= Formatter::formatCurrency($meal['price']) ?></h5>
                                <button type="button" class="btn btn-outline-primary btn-sm add-to-cart-btn" 
                                        data-meal-id="<?= $meal['id'] ?>"
                                        data-meal-name="<?= htmlspecialchars($meal['name']) ?>"
                                        data-meal-restaurant="<?= htmlspecialchars($meal['restaurant_name']) ?>"
                                        data-meal-price="<?= $meal['price'] ?>"
                                        data-meal-type="<?= $meal['meal_type'] ?>">
                                    <i class="fas fa-plus me-1"></i> Adicionar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="row">
            <div class="col-12">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= $currentPage == 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= buildPaginationUrl($currentPage - 1) ?>" tabindex="-1" aria-disabled="<?= $currentPage == 1 ? 'true' : 'false' ?>">Anterior</a>
                        </li>
                        
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                <a class="page-link" href="<?= buildPaginationUrl($i) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <li class="page-item <?= $currentPage == $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= buildPaginationUrl($currentPage + 1) ?>">Próxima</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Cart Modal -->
<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cartModalLabel">Meu Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="emptyCartMessage">
                    <div class="text-center py-4">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <h5>Seu carrinho está vazio</h5>
                        <p class="text-muted">Adicione refeições para montar seu pedido.</p>
                    </div>
                </div>
                
                <div id="cartContent" style="display: none;">
                    <div class="table-responsive">
                        <table class="table table-hover" id="cartTable">
                            <thead>
                                <tr>
                                    <th>Refeição</th>
                                    <th>Tipo</th>
                                    <th>Restaurante</th>
                                    <th class="text-end">Preço</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="cartItems">
                                <!-- Cart items will be inserted here -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3">Total</th>
                                    <th class="text-end" id="cartTotal">R$ 0,00</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Continuar Comprando</button>
                <button type="button" class="btn btn-outline-danger me-auto" id="clearCartBtn">Limpar Carrinho</button>
                <button type="button" class="btn btn-primary" id="checkoutBtn" disabled>Finalizar Pedido</button>
            </div>
        </div>
    </div>
</div>

<!-- Order Confirmation Modal -->
<div class="modal fade" id="orderConfirmationModal" tabindex="-1" aria-labelledby="orderConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderConfirmationModalLabel">Confirmar Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="orderForm" method="POST" action="/meals/order">
                    <input type="hidden" name="cart_items" id="cartItemsInput">
                    
                    <div class="mb-3">
                        <label for="delivery_address" class="form-label">Endereço de Entrega</label>
                        <textarea class="form-control" id="delivery_address" name="delivery_address" rows="3" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="delivery_date" class="form-label">Data de Entrega</label>
                        <input type="date" class="form-control" id="delivery_date" name="delivery_date" min="<?= date('Y-m-d') ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Forma de Pagamento</label>
                        <select class="form-select" id="payment_method" name="payment_method" required>
                            <option value="">Selecione...</option>
                            <option value="credit">Cartão de Crédito</option>
                            <option value="debit">Cartão de Débito</option>
                            <option value="pix">PIX</option>
                            <option value="money">Dinheiro</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Observações</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#cartModal">Voltar</button>
                <button type="button" class="btn btn-primary" id="placeOrderBtn">Confirmar Pedido</button>
            </div>
        </div>
    </div>
</div>

<!-- Order Success Modal -->
<div class="modal fade" id="orderSuccessModal" tabindex="-1" aria-labelledby="orderSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="orderSuccessModalLabel">Pedido Realizado com Sucesso!</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-check-circle fa-5x text-success mb-3"></i>
                <h4>Pedido #<span id="orderNumber">12345</span> Confirmado</h4>
                <p>Seu pedido foi recebido e está sendo processado.</p>
                <p>Você receberá uma confirmação por e-mail em breve.</p>
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle me-2"></i> Você pode acompanhar o status do seu pedido na página <strong>Meus Pedidos</strong>.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Continuar Comprando</button>
                <a href="/meals/orders" class="btn btn-primary">Ver Meus Pedidos</a>
            </div>
        </div>
    </div>
</div>

<?php
// Helper function to get badge color based on meal type
function getMealTypeBadgeColor($mealType) {
    $colors = [
        'breakfast' => 'warning',
        'lunch' => 'danger',
        'dinner' => 'info',
        'snack' => 'success'
    ];
    return $colors[$mealType] ?? 'secondary';
}

// Helper function to build pagination URLs
function buildPaginationUrl($page) {
    $params = $_GET;
    $params['page'] = $page;
    return '/meals/catalog?' . http_build_query($params);
}
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cart functionality
    let cart = JSON.parse(localStorage.getItem('mealCart')) || [];
    updateCartDisplay();
    
    // Add to cart buttons
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const mealId = this.dataset.mealId;
            const mealName = this.dataset.mealName;
            const mealRestaurant = this.dataset.mealRestaurant;
            const mealPrice = parseFloat(this.dataset.mealPrice);
            const mealType = this.dataset.mealType;
            
            // Add to cart
            cart.push({
                id: mealId,
                name: mealName,
                restaurant: mealRestaurant,
                price: mealPrice,
                type: mealType
            });
            
            // Save to localStorage
            localStorage.setItem('mealCart', JSON.stringify(cart));
            
            // Update display
            updateCartDisplay();
            
            // Show notification
            const toastHtml = `
                <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fas fa-check-circle me-2"></i> Refeição adicionada ao carrinho!
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;
            
            const toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            toastContainer.innerHTML = toastHtml;
            document.body.appendChild(toastContainer);
            
            const toastElement = toastContainer.querySelector('.toast');
            const toast = new bootstrap.Toast(toastElement, { autohide: true, delay: 3000 });
            toast.show();
            
            toastElement.addEventListener('hidden.bs.toast', function() {
                document.body.removeChild(toastContainer);
            });
        });
    });
    
    // Clear cart button
    document.getElementById('clearCartBtn').addEventListener('click', function() {
        cart = [];
        localStorage.removeItem('mealCart');
        updateCartDisplay();
    });
    
    // Checkout button
    document.getElementById('checkoutBtn').addEventListener('click', function() {
        // Populate order form with cart data
        document.getElementById('cartItemsInput').value = JSON.stringify(cart);
        
        // Hide cart modal and show order confirmation modal
        const cartModal = bootstrap.Modal.getInstance(document.getElementById('cartModal'));
        cartModal.hide();
        
        const orderConfirmationModal = new bootstrap.Modal(document.getElementById('orderConfirmationModal'));
        orderConfirmationModal.show();
    });
    
    // Place order button
    document.getElementById('placeOrderBtn').addEventListener('click', function() {
        if (document.getElementById('orderForm').checkValidity()) {
            // Hide order confirmation modal
            const orderConfirmationModal = bootstrap.Modal.getInstance(document.getElementById('orderConfirmationModal'));
            orderConfirmationModal.hide();
            
            // Generate random order number for demo
            document.getElementById('orderNumber').textContent = Math.floor(10000 + Math.random() * 90000);
            
            // Show success modal
            const orderSuccessModal = new bootstrap.Modal(document.getElementById('orderSuccessModal'));
            orderSuccessModal.show();
            
            // Clear cart
            cart = [];
            localStorage.removeItem('mealCart');
            updateCartDisplay();
        } else {
            document.getElementById('orderForm').reportValidity();
        }
    });
    
    // Update cart display function
    function updateCartDisplay() {
        const cartCountElement = document.getElementById('cartCount');
        const emptyCartMessage = document.getElementById('emptyCartMessage');
        const cartContent = document.getElementById('cartContent');
        const cartItems = document.getElementById('cartItems');
        const cartTotal = document.getElementById('cartTotal');
        const checkoutBtn = document.getElementById('checkoutBtn');
        
        // Update cart count
        cartCountElement.textContent = cart.length;
        
        if (cart.length === 0) {
            // Show empty cart message
            emptyCartMessage.style.display = 'block';
            cartContent.style.display = 'none';
            checkoutBtn.disabled = true;
        } else {
            // Hide empty cart message and show cart content
            emptyCartMessage.style.display = 'none';
            cartContent.style.display = 'block';
            checkoutBtn.disabled = false;
            
            // Clear current items
            cartItems.innerHTML = '';
            
            // Calculate total
            let total = 0;
            
            // Add cart items
            cart.forEach((item, index) => {
                total += item.price;
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.name}</td>
                    <td>${formatMealType(item.type)}</td>
                    <td>${item.restaurant}</td>
                    <td class="text-end">${formatCurrency(item.price)}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-item" data-index="${index}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                
                cartItems.appendChild(row);
            });
            
            // Update total
            cartTotal.textContent = formatCurrency(total);
            
            // Add event listeners to remove buttons
            document.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', function() {
                    const index = parseInt(this.dataset.index);
                    cart.splice(index, 1);
                    localStorage.setItem('mealCart', JSON.stringify(cart));
                    updateCartDisplay();
                });
            });
        }
    }
    
    // Helper format functions
    function formatMealType(type) {
        const types = {
            'breakfast': 'Café da Manhã',
            'lunch': 'Almoço',
            'dinner': 'Jantar',
            'snack': 'Lanche'
        };
        return types[type] || type;
    }
    
    function formatCurrency(value) {
        return 'R$ ' + value.toFixed(2).replace('.', ',');
    }
});
</script>