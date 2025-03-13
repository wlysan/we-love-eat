<?php $title = 'Meus Clientes - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col">
            <h2>Meus Clientes</h2>
            <p class="text-muted">Gerencie seus clientes e os planos de dieta associados a eles.</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <form class="row g-3" method="GET" action="/nutritionist/clients">
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" name="search" value="<?= $_GET['search'] ?? '' ?>" placeholder="Buscar por nome ou email...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Buscar</button>
                            <a href="/nutritionist/diets/create" class="btn btn-outline-primary ms-2">Criar Plano</a>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <?php if (empty($clients)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5>Você ainda não tem clientes</h5>
                            <p class="text-muted">Seus clientes aparecem aqui quando eles iniciam uma conversa com você ou quando você cria um plano de dieta para eles.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>Plano de Dieta</th>
                                        <th>Compartilhamento</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($clients as $client): ?>
                                        <tr>
                                            <td><?= $client['name'] ?></td>
                                            <td><?= $client['email'] ?></td>
                                            <td>
                                                <?php if (!empty($client['diet_plan_id'])): ?>
                                                    <a href="/nutritionist/diets/view?id=<?= $client['diet_plan_id'] ?>">
                                                        <i class="fas fa-clipboard-list me-1"></i> Ver Plano
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">Sem plano</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($client['chat_id'])): ?>
                                                    <?php if ($client['share_progress']): ?>
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check me-1"></i> Compartilhando Progresso
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">
                                                            <i class="fas fa-times me-1"></i> Não Compartilhado
                                                        </span>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Sem conversa</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <?php if (!empty($client['diet_plan_id'])): ?>
                                                        <a href="/nutritionist/diets/edit?id=<?= $client['diet_plan_id'] ?>" class="btn btn-outline-primary" title="Editar Dieta">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="/nutritionist/diets/create?client_id=<?= $client['id'] ?>" class="btn btn-outline-primary" title="Criar Dieta">
                                                            <i class="fas fa-plus"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (!empty($client['chat_id'])): ?>
                                                        <a href="/chats/view?id=<?= $client['chat_id'] ?>" class="btn btn-outline-primary" title="Ver Conversa">
                                                            <i class="fas fa-comments"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    
                                                    <button type="button" class="btn btn-outline-primary" title="Ver Progresso" data-bs-toggle="modal" data-bs-target="#progressModal<?= $client['id'] ?>">
                                                        <i class="fas fa-chart-line"></i>
                                                    </button>
                                                </div>
                                                
                                                <!-- Progress Modal -->
                                                <div class="modal fade" id="progressModal<?= $client['id'] ?>" tabindex="-1" aria-labelledby="progressModalLabel<?= $client['id'] ?>" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="progressModalLabel<?= $client['id'] ?>">Progresso de <?= $client['name'] ?></h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <?php if (!$client['share_progress']): ?>
                                                                    <div class="alert alert-warning">
                                                                        <i class="fas fa-exclamation-triangle me-2"></i> Este cliente não compartilhou o progresso com você.
                                                                        <div class="mt-2">
                                                                            <a href="/chats/view?id=<?= $client['chat_id'] ?>" class="btn btn-sm btn-warning">Solicitar Acesso</a>
                                                                        </div>
                                                                    </div>
                                                                <?php else: ?>
                                                                    <div class="mb-4">
                                                                        <h6>Evolução de Peso</h6>
                                                                        <div style="height: 250px;">
                                                                            <canvas id="weightChart<?= $client['id'] ?>"></canvas>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="mb-4">
                                                                        <h6>Aderência ao Plano de Dieta</h6>
                                                                        <div class="progress mb-2" style="height: 25px;">
                                                                            <div class="progress-bar" role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                                                                                75%
                                                                            </div>
                                                                        </div>
                                                                        <p class="text-muted small">A aderência é calculada com base nas refeições registradas em relação ao plano de dieta.</p>
                                                                    </div>
                                                                    
                                                                    <div class="mb-4">
                                                                        <h6>Estatísticas de Medidas</h6>
                                                                        <div class="table-responsive">
                                                                            <table class="table table-sm">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>Medida</th>
                                                                                        <th>Inicial</th>
                                                                                        <th>Atual</th>
                                                                                        <th>Mudança</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td>Peso</td>
                                                                                        <td>80.0 kg</td>
                                                                                        <td>75.5 kg</td>
                                                                                        <td class="text-success">-4.5 kg</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>% Gordura</td>
                                                                                        <td>25.0%</td>
                                                                                        <td>22.5%</td>
                                                                                        <td class="text-success">-2.5%</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>Cintura</td>
                                                                                        <td>90.0 cm</td>
                                                                                        <td>86.5 cm</td>
                                                                                        <td class="text-success">-3.5 cm</td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                                                <?php if ($client['share_progress']): ?>
                                                                    <a href="/nutritionist/clients/progress?id=<?= $client['id'] ?>" class="btn btn-primary">Ver Relatório Completo</a>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($clients)): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php foreach ($clients as $client): ?>
        <?php if ($client['share_progress']): ?>
            const ctxWeight<?= $client['id'] ?> = document.getElementById('weightChart<?= $client['id'] ?>');
            if (ctxWeight<?= $client['id'] ?>) {
                new Chart(ctxWeight<?= $client['id'] ?>, {
                    type: 'line',
                    data: {
                        labels: ['01/02', '08/02', '15/02', '22/02', '01/03', '08/03'],
                        datasets: [{
                            label: 'Peso (kg)',
                            data: [80.0, 78.5, 77.2, 76.8, 76.0, 75.5],
                            fill: false,
                            borderColor: '#00a651',
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: false
                            }
                        }
                    }
                });
            }
        <?php endif; ?>
    <?php endforeach; ?>
});
</script>
<?php endif; ?>