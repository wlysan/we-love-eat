<?php $title = 'Editar Usuário - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col">
            <h2>Editar Usuário</h2>
            <p class="text-muted">Editar informações do usuário <?= $user['name'] ?>.</p>
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
                    
                    <form method="POST" action="/admin/users/edit?id=<?= $user['id'] ?>">
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
                        
                        <div class="mb-4">
                            <label class="form-label d-block">Informações do Perfil</label>
                            <?php if (!empty($profile)): ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Data de Nascimento:</strong> <?= !empty($profile['birth_date']) ? Formatter::formatDate($profile['birth_date']) : 'Não informado' ?></p>
                                        <p class="mb-1"><strong>Gênero:</strong> <?= !empty($profile['gender']) ? Formatter::getGenderName($profile['gender']) : 'Não informado' ?></p>
                                        <p class="mb-1"><strong>Altura:</strong> <?= !empty($profile['height']) ? Formatter::formatHeight($profile['height']) : 'Não informado' ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Peso Atual:</strong> <?= !empty($profile['current_weight']) ? Formatter::formatWeight($profile['current_weight']) : 'Não informado' ?></p>
                                        <p class="mb-1"><strong>Peso Meta:</strong> <?= !empty($profile['goal_weight']) ? Formatter::formatWeight($profile['goal_weight']) : 'Não informado' ?></p>
                                        <p class="mb-1"><strong>Nível de Atividade:</strong> <?= !empty($profile['activity_level']) ? Formatter::getActivityLevelName($profile['activity_level']) : 'Não informado' ?></p>
                                    </div>
                                </div>
                                <a href="/admin/users/profile?id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-primary mt-2">Editar Perfil</a>
                            <?php else: ?>
                                <p class="text-muted">Perfil não criado.</p>
                                <a href="/admin/users/profile?id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-primary">Criar Perfil</a>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="/admin/users" class="btn btn-outline-secondary me-md-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>