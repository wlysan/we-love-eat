<?php $title = APP_NAME . ' - Nutrição Personalizada'; ?>

<!-- Hero Section -->
<section class="hero py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Dietas personalizadas para seu estilo de vida</h1>
                <p class="lead mb-4">
                    Conectamos você a nutricionistas qualificados e restaurantes parceiros para tornar sua alimentação saudável mais fácil e conveniente.
                </p>
                <div class="mb-4">
                    <a href="/register" class="btn btn-primary btn-lg me-2">Começar Agora</a>
                    <a href="#how-it-works" class="btn btn-outline-secondary btn-lg">Como Funciona</a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="https://source.unsplash.com/random/600x400/?healthy,food" alt="Alimentação saudável" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5" id="how-it-works">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col">
                <h2 class="display-5 fw-bold">Como Funciona</h2>
                <p class="lead">Uma abordagem simplificada para uma alimentação saudável</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-user-md fa-2x"></i>
                        </div>
                        <h4>Conecte-se com Nutricionistas</h4>
                        <p class="text-muted">
                            Encontre nutricionistas especializados para criar um plano alimentar personalizado que atenda às suas necessidades e objetivos.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-utensils fa-2x"></i>
                        </div>
                        <h4>Escolha Refeições</h4>
                        <p class="text-muted">
                            Selecione refeições de restaurantes parceiros que se encaixam perfeitamente nas recomendações nutricionais do seu plano.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                        <h4>Acompanhe seu Progresso</h4>
                        <p class="text-muted">
                            Monitore seu progresso, registre medidas e acompanhe seu avanço em direção aos seus objetivos de saúde e forma física.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col">
                <h2 class="display-5 fw-bold">Benefícios</h2>
                <p class="lead">Por que escolher o <?= APP_NAME ?>?</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                            <i class="fas fa-check fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4>Dietas Personalizadas</h4>
                        <p class="text-muted">
                            Planos alimentares criados por nutricionistas profissionais, adaptados às suas necessidades específicas, objetivos e preferências.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-4">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                            <i class="fas fa-check fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4>Opções de Refeições Convenientes</h4>
                        <p class="text-muted">
                            Acesso a uma ampla variedade de refeições saudáveis de restaurantes parceiros, com informações nutricionais detalhadas.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-4">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                            <i class="fas fa-check fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4>Acompanhamento Profissional</h4>
                        <p class="text-muted">
                            Comunicação direta com seu nutricionista através do nosso sistema de chat integrado para orientação contínua.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-4">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                            <i class="fas fa-check fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4>Monitoramento de Progresso</h4>
                        <p class="text-muted">
                            Ferramentas intuitivas para acompanhar suas medidas, peso e aderência ao plano alimentar ao longo do tempo.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="display-5 fw-bold mb-4">Pronto para transformar sua alimentação?</h2>
                <p class="lead mb-4">
                    Comece hoje mesmo a sua jornada para uma alimentação mais saudável e equilibrada com o suporte de profissionais qualificados.
                </p>
                <a href="/register" class="btn btn-primary btn-lg">Cadastre-se Gratuitamente</a>
            </div>
        </div>
    </div>
</section>