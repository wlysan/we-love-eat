<?php $title = 'Perfil do Nutricionista - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="mb-4">
                        <i class="fas fa-user-md fa-5x text-primary"></i>
                    </div>
                    <h3><?= $nutritionist['name'] ?></h3>
                    <p class="text-muted mb-3">Nutricionista - CRN: <?= $nutritionist['professional_id'] ?></p>
                    
                    <div class="mb-4">
                        <?php if (!empty($nutritionist['specialties'])): ?>
                            <p><strong>Especialidades:</strong> <?= $nutritionist['specialties'] ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#chatModal">
                        <i class="fas fa-comments me-2"></i> Iniciar Conversa
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h4 class="card-title mb-0">Sobre o Nutricionista</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($nutritionist['bio'])): ?>
                        <div class="mb-4">
                            <h5>Biografia</h5>
                            <p><?= nl2br(htmlspecialchars($nutritionist['bio'])) ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($nutritionist['education'])): ?>
                        <div class="mb-4">
                            <h5>Formação Acadêmica</h5>
                            <p><?= nl2br(htmlspecialchars($nutritionist['education'])) ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($nutritionist['experience'])): ?>
                        <div class="mb-4">
                            <h5>Experiência Profissional</h5>
                            <p><?= nl2br(htmlspecialchars($nutritionist['experience'])) ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (empty($nutritionist['bio']) && empty($nutritionist['education']) && empty($nutritionist['experience'])): ?>
                        <p class="text-muted">Informações detalhadas sobre este nutricionista não estão disponíveis no momento.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h4 class="card-title mb-0">Abordagem Nutricional</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-apple-alt"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5>Alimentação Personalizada</h5>
                                    <p class="text-muted">Planos personalizados de acordo com suas necessidades e objetivos específicos.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5>Acompanhamento Contínuo</h5>
                                    <p class="text-muted">Monitoramento regular do progresso e ajustes conforme necessário.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-heartbeat"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5>Abordagem de Saúde Integral</h5>
                                    <p class="text-muted">Foco na saúde geral, não apenas na perda de peso ou ganho muscular.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-utensils"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5>Orientação Prática</h5>
                                    <p class="text-muted">Dicas e estratégias práticas para implementar mudanças alimentares no dia a dia.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chat Modal -->
<div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/chats/create">
                <input type="hidden" name="nutritionist_id" value="<?= $nutritionist['id'] ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="chatModalLabel">Iniciar Conversa com <?= $nutritionist['name'] ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="message" class="form-label">Mensagem Inicial (opcional)</label>
                        <textarea class="form-control" id="message" name="message" rows="4" placeholder="Escreva uma mensagem para o(a) nutricionista..."></textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> Ao iniciar uma conversa, você poderá discutir seus objetivos, necessidades alimentares e solicitar um plano de dieta personalizado.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Iniciar Conversa</button>
                </div>
            </form>
        </div>
    </div>
</div>