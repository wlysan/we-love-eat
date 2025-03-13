<?php $title = 'Editar Perfil - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">Menu</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="/profile" class="list-group-item list-group-item-action">
                            <i class="fas fa-user me-2"></i> Perfil
                        </a>
                        <a href="/profile/edit" class="list-group-item list-group-item-action active">
                            <i class="fas fa-user-edit me-2"></i> Editar Perfil
                        </a>
                        <a href="/profile/measurements" class="list-group-item list-group-item-action">
                            <i class="fas fa-weight me-2"></i> Medidas
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h4 class="card-title mb-0">Editar Perfil</h4>
                </div>
                <div class="card-body">
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
                    
                    <form method="POST" action="/profile/edit">
                        <div class="mb-4">
                            <h5>Informações Básicas</h5>
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nome Completo</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?= $user['name'] ?? '' ?>" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" value="<?= $user['email'] ?? '' ?>" disabled>
                                    <div class="form-text">Para alterar seu email, entre em contato com o suporte.</div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="birth_date" class="form-label">Data de Nascimento</label>
                                    <input type="text" class="form-control" id="birth_date" name="birth_date" value="<?= !empty($profile['birth_date']) ? Formatter::formatDate($profile['birth_date']) : '' ?>" placeholder="DD/MM/AAAA">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="gender" class="form-label">Gênero</label>
                                    <select class="form-select" id="gender" name="gender">
                                        <option value="">Selecione...</option>
                                        <option value="male" <?= (!empty($profile['gender']) && $profile['gender'] === 'male') ? 'selected' : '' ?>>Masculino</option>
                                        <option value="female" <?= (!empty($profile['gender']) && $profile['gender'] === 'female') ? 'selected' : '' ?>>Feminino</option>
                                        <option value="other" <?= (!empty($profile['gender']) && $profile['gender'] === 'other') ? 'selected' : '' ?>>Outro</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5>Informações Físicas</h5>
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="height" class="form-label">Altura (cm)</label>
                                    <input type="number" class="form-control" id="height" name="height" value="<?= $profile['height'] ?? '' ?>" min="100" max="250" step="1">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="current_weight" class="form-label">Peso Atual (kg)</label>
                                    <input type="number" class="form-control" id="current_weight" name="current_weight" value="<?= $profile['current_weight'] ?? '' ?>" min="30" max="300" step="0.1">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="goal_weight" class="form-label">Peso Meta (kg)</label>
                                    <input type="number" class="form-control" id="goal_weight" name="goal_weight" value="<?= $profile['goal_weight'] ?? '' ?>" min="30" max="300" step="0.1">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="activity_level" class="form-label">Nível de Atividade Física</label>
                                    <select class="form-select" id="activity_level" name="activity_level">
                                        <option value="">Selecione...</option>
                                        <option value="sedentary" <?= (!empty($profile['activity_level']) && $profile['activity_level'] === 'sedentary') ? 'selected' : '' ?>>Sedentário (pouco ou nenhum exercício)</option>
                                        <option value="light" <?= (!empty($profile['activity_level']) && $profile['activity_level'] === 'light') ? 'selected' : '' ?>>Levemente ativo (exercício leve 1-3 dias/semana)</option>
                                        <option value="moderate" <?= (!empty($profile['activity_level']) && $profile['activity_level'] === 'moderate') ? 'selected' : '' ?>>Moderadamente ativo (exercício moderado 3-5 dias/semana)</option>
                                        <option value="active" <?= (!empty($profile['activity_level']) && $profile['activity_level'] === 'active') ? 'selected' : '' ?>>Ativo (exercício intenso 6-7 dias/semana)</option>
                                        <option value="very_active" <?= (!empty($profile['activity_level']) && $profile['activity_level'] === 'very_active') ? 'selected' : '' ?>>Muito ativo (exercício muito intenso, trabalho físico)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5>Informações de Saúde</h5>
                            <hr>
                            
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="health_conditions" class="form-label">Condições de Saúde</label>
                                    <textarea class="form-control" id="health_conditions" name="health_conditions" rows="3" placeholder="Ex: diabetes, hipertensão, etc."><?= $profile['health_conditions'] ?? '' ?></textarea>
                                </div>
                                
                                <div class="col-12 mb-3">
                                    <label for="dietary_restrictions" class="form-label">Restrições Alimentares</label>
                                    <textarea class="form-control" id="dietary_restrictions" name="dietary_restrictions" rows="3" placeholder="Ex: vegetariano, alergia a frutos do mar, intolerância a lactose, etc."><?= $profile['dietary_restrictions'] ?? '' ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="/profile" class="btn btn-outline-secondary me-md-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>