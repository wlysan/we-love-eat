<?php $title = 'Conversa - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>
                <?= $userId == $chat['user_id'] ? $chat['nutritionist_name'] : $chat['user_name'] ?>
                <?php if ($chat['status'] === 'closed'): ?>
                    <span class="badge bg-secondary ms-2">Conversa Fechada</span>
                <?php endif; ?>
            </h2>
            <p class="text-muted">Conversa iniciada em <?= Formatter::formatDateTime($chat['created_at']) ?></p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="/chats" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Voltar para Conversas
            </a>
            
            <?php if ($userId == $chat['user_id']): ?>
                <form method="POST" action="/chats/progress" class="d-inline-block ms-2">
                    <input type="hidden" name="chat_id" value="<?= $chat['id'] ?>">
                    <input type="hidden" name="share_progress" value="<?= $chat['share_progress'] ? '0' : '1' ?>">
                    <button type="submit" class="btn btn-<?= $chat['share_progress'] ? 'danger' : 'success' ?>">
                        <i class="fas fa-chart-line me-2"></i> 
                        <?= $chat['share_progress'] ? 'Parar Compartilhamento' : 'Compartilhar Progresso' ?>
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="chat-container mb-4" id="chatContainer">
                        <?php if (empty($messages)): ?>
                            <div class="text-center my-5 py-5">
                                <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                <h5>Nenhuma mensagem enviada</h5>
                                <p class="text-muted">Envie a primeira mensagem para iniciar a conversa.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach (array_reverse($messages) as $message): ?>
                                <div class="chat-message <?= $message['sender_id'] == $userId ? 'sent' : 'received' ?>">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <span class="fw-bold"><?= $message['sender_name'] ?></span>
                                        <small class="text-muted"><?= Formatter::formatDateTime($message['created_at']) ?></small>
                                    </div>
                                    <p class="mb-0 mt-1"><?= nl2br(htmlspecialchars($message['message'])) ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($chat['status'] === 'active'): ?>
                        <form method="POST" action="/chats/send">
                            <input type="hidden" name="chat_id" value="<?= $chat['id'] ?>">
                            <div class="mb-3">
                                <textarea class="form-control" name="message" rows="3" placeholder="Digite sua mensagem aqui..." required></textarea>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i> Enviar Mensagem
                                </button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i> Esta conversa está fechada. Não é possível enviar novas mensagens.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Scroll to bottom of chat container
    const chatContainer = document.getElementById('chatContainer');
    chatContainer.scrollTop = chatContainer.scrollHeight;
});
</script>