<?php $title = 'Meu Perfil - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h4 class="card-title mb-0">Meu Perfil</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-circle fa-5x text-primary"></i>
                        <h5 class="mt-3"><?= $user['name'] ?></h5>
                        <p class="text-muted"><?= $user['email'] ?></p>
                    </div>
                    
                    <div class="list-group list-group-flush">
                        <a href="/profile/edit" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-user-edit me-2"></i> Editar Perfil</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        <a href="/profile/measurements" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-weight me-2"></i> Medidas</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        <a href="/diets" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-utensils me-2"></i> Minhas Dietas</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        <a href="/chats" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-comments me-2"></i> Conversas</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h4 class="card-title mb-0">Informações Pessoais</h4>
                </div>
                <div class="card-body">
                    <?php if (empty($profile) || empty($profile['birth_date']) || empty($profile['gender']) || empty($profile['height']) || empty($profile['current_weight'])): ?>
                        <div class="alert alert-warning">
                            <h5><i class="fas fa-exclamation-triangle me-2"></i> Complete seu perfil</h5>
                            <p class="mb-0">
                                Para receber recomendações nutricionais adequadas, complete seu perfil com suas informações de saúde.
                            </p>
                            <a href="/profile/edit" class="btn btn-sm btn-warning mt-2">Completar Perfil</a>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Data de Nascimento</h6>
                                <p><?= Formatter::formatDate($profile['birth_date']) ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Gênero</h6>
                                <p><?= Formatter::getGenderName($profile['gender']) ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Altura</h6>
                                <p><?= Formatter::formatHeight($profile['height']) ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Peso Atual</h6>
                                <p><?= Formatter::formatWeight($profile['current_weight']) ?></p>
                            </div>
                            
                            <?php if (!empty($profile['goal_weight'])): ?>
                                <div class="col-md-6 mb-3">
                                    <h6 class="text-muted">Peso Meta</h6>
                                    <p><?= Formatter::formatWeight($profile['goal_weight']) ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($profile['activity_level'])): ?>
                                <div class="col-md-6 mb-3">
                                    <h6 class="text-muted">Nível de Atividade</h6>
                                    <p><?= Formatter::getActivityLevelName($profile['activity_level']) ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($profile['health_conditions'])): ?>
                                <div class="col-12 mb-3">
                                    <h6 class="text-muted">Condições de Saúde</h6>
                                    <p><?= $profile['health_conditions'] ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($profile['dietary_restrictions'])): ?>
                                <div class="col-12 mb-3">
                                    <h6 class="text-muted">Restrições Alimentares</h6>
                                    <p><?= $profile['dietary_restrictions'] ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mt-3">
                            <a href="/profile/edit" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i> Editar Informações
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>