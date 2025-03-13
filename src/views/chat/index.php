<?php $title = 'Conversas - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col">
            <h2>Minhas Conversas</h2>
            <p class="text-muted">Comunique-se com <?= $userRole === 'nutritionist' ? 'seus clientes' : 'nutricionistas' ?> para tirar dúvidas e receber orientações.</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <?php if (empty($chats)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                            <h5>Nenhuma conversa iniciada</h5>
                            <?php if ($userRole !== 'nutritionist'): ?>
                                <p class="text-muted">Encontre um nutricionista e inicie uma conversa.</p>
                                <a href="/nutritionists" class="btn btn-primary mt-2">
                                    <i class="fas fa-search me-2"></i> Encontrar Nutricionistas
                                </a>
                            <?php else: ?>
                                <p class="text-muted">Aguarde que seus clientes iniciem uma conversa com você.</p>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($chats as $chat): ?>
                                <a href="/chats/view?id=<?= $chat['id'] ?>" class="list-group-item list-group-item-action position-relative">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-1">
                                                <?= $userRole === 'nutritionist' ? $chat['user_name'] : $chat['nutritionist_name'] ?>
                                                <?php if ($chat['status'] === 'closed'): ?>
                                                    <span class="badge bg-secondary ms-2">Fechada</span>
                                                <?php endif; ?>
                                                <?php if ($chat['share_progress']): ?>
                                                    <span class="badge bg-success ms-2" title="Compartilhamento de progresso ativado">
                                                        <i class="fas fa-chart-line"></i>
                                                    </span>
                                                <?php endif; ?>
                                            </h5>
                                            <p class="mb-1 text-muted">
                                                <i class="far fa-calendar-alt me-1"></i> Iniciada em: <?= Formatter::formatDate($chat['created_at']) ?>
                                            </p>
                                        </div>
                                        <div>
                                            <?php if ($chat['unread_count'] > 0): ?>
                                                <span class="badge bg-danger rounded-pill"><?= $chat['unread_count'] ?></span>
                                            <?php endif; ?>
                                            <i class="fas fa-chevron-right ms-3"></i>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>