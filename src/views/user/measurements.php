<?php $title = 'Minhas Medidas - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Minhas Medidas</h2>
            <p class="text-muted">Acompanhe o histórico das suas medidas corporais.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="/profile/measurements/add" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i> Nova Medida
            </a>
        </div>
    </div>
    
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
                        <a href="/profile/edit" class="list-group-item list-group-item-action">
                            <i class="fas fa-user-edit me-2"></i> Editar Perfil
                        </a>
                        <a href="/profile/measurements" class="list-group-item list-group-item-action active">
                            <i class="fas fa-weight me-2"></i> Medidas
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-body">
                    <?php if (empty($measurements)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-weight fa-3x text-muted mb-3"></i>
                            <h5>Nenhuma medida registrada</h5>
                            <p class="text-muted">Comece a registrar suas medidas corporais para acompanhar seu progresso.</p>
                            <a href="/profile/measurements/add" class="btn btn-primary mt-2">
                                <i class="fas fa-plus-circle me-2"></i> Registrar Medida
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="mb-4">
                            <h5>Evolução do Peso</h5>
                            <div style="height: 250px;">
                                <canvas id="weightChart"></canvas>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Data</th>
                                        <th>Peso (kg)</th>
                                        <th>% Gordura</th>
                                        <th>Cintura (cm)</th>
                                        <th>Peito (cm)</th>
                                        <th>Braços (cm)</th>
                                        <th>Pernas (cm)</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($measurements as $measurement): ?>
                                        <tr>
                                            <td><?= Formatter::formatDate($measurement['date']) ?></td>
                                            <td><?= $measurement['weight'] ? number_format($measurement['weight'], 1, ',', '.') : '-' ?></td>
                                            <td><?= $measurement['body_fat_percentage'] ? number_format($measurement['body_fat_percentage'], 1, ',', '.') . '%' : '-' ?></td>
                                            <td><?= $measurement['waist'] ? number_format($measurement['waist'], 1, ',', '.') : '-' ?></td>
                                            <td><?= $measurement['chest'] ? number_format($measurement['chest'], 1, ',', '.') : '-' ?></td>
                                            <td><?= $measurement['arms'] ? number_format($measurement['arms'], 1, ',', '.') : '-' ?></td>
                                            <td><?= $measurement['legs'] ? number_format($measurement['legs'], 1, ',', '.') : '-' ?></td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="/profile/measurements/edit?id=<?= $measurement['id'] ?>" class="btn btn-outline-primary" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
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

<?php if (!empty($measurements)): ?>
<!-- Chart.js for weight history -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Extract data for chart
    const dates = [
        <?php foreach (array_reverse(array_slice($measurements, 0, 10)) as $m): ?>
            '<?= Formatter::formatDate($m['date']) ?>',
        <?php endforeach; ?>
    ];
    
    const weights = [
        <?php foreach (array_reverse(array_slice($measurements, 0, 10)) as $m): ?>
            <?= $m['weight'] ?? 'null' ?>,
        <?php endforeach; ?>
    ];
    
    // Create chart
    const ctx = document.getElementById('weightChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Peso (kg)',
                data: weights,
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
});
</script>
<?php endif; ?>