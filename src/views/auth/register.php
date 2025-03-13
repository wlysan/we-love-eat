<?php $title = 'Cadastro - ' . APP_NAME; ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h4 class="card-title text-center mb-0">Criar Conta no <?= APP_NAME ?></h4>
                </div>
                <div class="card-body p-4">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger">
                            <?= $error ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="/register">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome Completo</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="form-text">A senha deve ter pelo menos 6 caracteres.</div>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirmar Senha</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" required>
                            <label class="form-check-label" for="terms">
                                Concordo com os <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Termos de Uso</a> e <a href="#" data-bs-toggle="modal" data-bs-target="#privacyModal">Política de Privacidade</a>
                            </label>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Cadastrar</button>
                        </div>
                    </form>
                    
                    <div class="mt-4 text-center">
                        <p>Já tem uma conta? <a href="/login">Entrar</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Termos de Uso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5>1. Aceitação dos Termos</h5>
                <p>Ao acessar e usar o <?= APP_NAME ?>, você concorda em cumprir estes Termos de Uso e todas as leis e regulamentos aplicáveis.</p>
                
                <h5>2. Uso do Serviço</h5>
                <p>Você concorda em usar o serviço apenas para fins legais e de acordo com estes Termos. Você é responsável por manter a confidencialidade de sua conta e senha.</p>
                
                <h5>3. Conteúdo do Usuário</h5>
                <p>Você é responsável por qualquer conteúdo que enviar ao serviço, incluindo sua legalidade, confiabilidade e adequação.</p>
                
                <h5>4. Alterações nos Termos</h5>
                <p>Reservamo-nos o direito de modificar estes termos a qualquer momento. Você é responsável por revisar periodicamente os termos para verificar se há alterações.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Privacy Modal -->
<div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="privacyModalLabel">Política de Privacidade</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5>1. Informações Coletadas</h5>
                <p>Coletamos informações pessoais como nome, email, informações de perfil e dados de saúde necessários para fornecer nossos serviços.</p>
                
                <h5>2. Uso das Informações</h5>
                <p>Usamos suas informações para fornecer, manter e melhorar nossos serviços, personalizar sua experiência e comunicar-nos com você.</p>
                
                <h5>3. Compartilhamento de Informações</h5>
                <p>Compartilhamos suas informações apenas com seu consentimento, com nutricionistas e restaurantes parceiros conforme necessário para fornecer os serviços, e conforme exigido por lei.</p>
                
                <h5>4. Segurança</h5>
                <p>Implementamos medidas de segurança para proteger suas informações pessoais, mas nenhum método de transmissão pela Internet é 100% seguro.</p>
                
                <h5>5. Seus Direitos</h5>
                <p>Você tem o direito de acessar, corrigir ou excluir suas informações pessoais a qualquer momento.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>