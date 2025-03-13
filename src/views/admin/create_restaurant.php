<?php $title = 'Criar Restaurante - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col">
            <h2>Criar Novo Restaurante</h2>
            <p class="text-muted">Adicione um novo restaurante parceiro à plataforma.</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success">
                            <?= $success ?>
                            <div class="mt-2">
                                <a href="/admin/restaurants" class="btn btn-sm btn-success">Voltar para a lista de restaurantes</a>
                                <a href="/admin/restaurants/create" class="btn btn-sm btn-outline-success">Criar outro restaurante</a>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger">
                            <?= $error ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="/admin/restaurants/create">
                        <h5 class="mb-3">Informações da Conta</h5>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome do Restaurante</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="form-text">A senha deve ter pelo menos 6 caracteres.</div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h5 class="mb-3">Informações do Estabelecimento</h5>
                        
                        <div class="mb-3">
                            <label for="cnpj" class="form-label">CNPJ</label>
                            <input type="text" class="form-control" id="cnpj" name="cnpj" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Endereço Completo</label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Telefone</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Descrição</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="delivery_areas" class="form-label">Áreas de Entrega</label>
                            <textarea class="form-control" id="delivery_areas" name="delivery_areas" rows="2"></textarea>
                            <div class="form-text">Separe as áreas por vírgulas (ex: Centro, Zona Sul, Barra Funda).</div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="/admin/restaurants" class="btn btn-outline-secondary me-md-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Criar Restaurante</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>