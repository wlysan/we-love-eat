<?php $title = 'Editar Nutricionista - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col">
            <h2>Editar Nutricionista</h2>
            <p class="text-muted">Editar informações do nutricionista <?= $user['name'] ?>.</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success">
                            <?= $success ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger">
                            <?= $error ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="/admin/nutritionists/edit?id=<?= $nutritionist['id'] ?>">
                        <h5 class="mb-3">Informações da Conta</h5>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome Completo</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= $user['name'] ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= $user['email'] ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Nova Senha (deixe em branco para manter a atual)</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <div class="form-text">A senha deve ter pelo menos 6 caracteres.</div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h5 class="mb-3">Informações Profissionais</h5>
                        
                        <div class="mb-3">
                            <label for="professional_id" class="form-label">Número CRN</label>
                            <input type="text" class="form-control" id="professional_id" name="professional_id" value="<?= $nutritionist['professional_id'] ?>" required>
                            <div class="form-text">Número do registro no Conselho Regional de Nutrição.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="specialties" class="form-label">Especialidades</label>
                            <input type="text" class="form-control" id="specialties" name="specialties" value="<?= $nutritionist['specialties'] ?? '' ?>">
                            <div class="form-text">Separe as especialidades por vírgulas (ex: Nutrição Esportiva, Emagrecimento).</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="bio" class="form-label">Biografia</label>
                            <textarea class="form-control" id="bio" name="bio" rows="3"><?= $nutritionist['bio'] ?? '' ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="education" class="form-label">Formação Acadêmica</label>
                            <textarea class="form-control" id="education" name="education" rows="2"><?= $nutritionist['education'] ?? '' ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="experience" class="form-label">Experiência Profissional</label>
                            <textarea class="form-control" id="experience" name="experience" rows="2"><?= $nutritionist['experience'] ?? '' ?></textarea>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="/admin/nutritionists" class="btn btn-outline-secondary me-md-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>