# NutriMenu - Sistema de Gerenciamento Nutricional

NutriMenu é uma aplicação web PHP para conectar usuários, nutricionistas e restaurantes, permitindo a criação e acompanhamento de planos alimentares personalizados.

![NutriMenu](logo.png)

## Características

- **Usuários**: Registro, login, edição de perfil, acompanhamento de medidas e progresso
- **Nutricionistas**: Criação de planos alimentares personalizados para clientes
- **Restaurantes**: Cadastro de refeições com detalhes nutricionais dos ingredientes
- **Dietas**: Prescrição de dietas com metas nutricionais e acompanhamento de adesão
- **Chat**: Sistema de comunicação entre usuários e nutricionistas
- **Admin**: Painel administrativo para gerenciar usuários, nutricionistas e restaurantes

## Requisitos

- PHP 7.4 ou superior
- SQLite3
- Extensões PHP: PDO SQLite, JSON, mbstring
- Servidor web com suporte a mod_rewrite (Apache) ou configuração equivalente (Nginx, IIS)

## Instalação

### Instalação Local

1. Clone este repositório:
```bash
git clone https://github.com/seu-usuario/nutritionphp.git
cd nutritionphp
```

2. Certifique-se de que os diretórios `database` e `public/uploads` têm permissões de escrita:
```bash
chmod 777 database public/uploads
```

3. Configure um servidor web (Apache, Nginx) ou use o servidor embutido do PHP:
```bash
php -S localhost:8000 -t public/
```

4. Acesse a aplicação em seu navegador:
```
http://localhost:8000
```

A aplicação criará automaticamente o banco de dados SQLite na primeira execução, juntamente com um usuário administrador padrão:

- Email: admin@nutrimenu.com.br
- Senha: admin123

### Instalação em Servidor de Produção

1. Edite o arquivo `deploy.sh` configurando as variáveis para seu ambiente:
```bash
REMOTE_USER="seu_usuario"
REMOTE_HOST="seu_servidor.com"
REMOTE_PATH="/caminho/para/instalacao"
APP_URL="https://seu_dominio.com/"
```

2. Execute o script de implantação:
```bash
./deploy.sh
```

3. Configure seu servidor web para apontar para o diretório `public`.

#### Exemplo de configuração Apache:

```apache
<VirtualHost *:80>
    ServerName nutrimenu.example.com
    DocumentRoot /var/www/nutrimenu/public
    
    <Directory /var/www/nutrimenu/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/nutrimenu-error.log
    CustomLog ${APACHE_LOG_DIR}/nutrimenu-access.log combined
</VirtualHost>
```

## Estrutura do Projeto

```
nutritionphp/
├── database/              # Diretório do banco de dados SQLite
├── public/                # Diretório público (ponto de entrada)
│   ├── css/               # Arquivos CSS
│   ├── js/                # Arquivos JavaScript
│   ├── img/               # Imagens
│   ├── uploads/           # Diretório para uploads
│   ├── index.php          # Arquivo principal
│   ├── .htaccess          # Regras de reescrita para Apache
│   └── favicon.ico        # Favicon do site
├── src/                   # Código fonte da aplicação
│   ├── config/            # Arquivos de configuração
│   │   ├── application.php # Configurações específicas do ambiente
│   │   ├── config.php     # Configurações gerais
│   │   └── database.php   # Configuração do banco de dados
│   ├── controllers/       # Controladores
│   ├── migrations/        # Migrações de banco de dados
│   ├── models/            # Modelos
│   ├── utils/             # Utilitários
│   ├── views/             # Visualizações
│   │   ├── admin/         # Views do painel administrativo
│   │   ├── auth/          # Views de autenticação
│   │   ├── chat/          # Views do sistema de chat
│   │   ├── dashboard/     # Views dos dashboards
│   │   ├── diet/          # Views de planos de dieta
│   │   ├── error/         # Views de páginas de erro
│   │   ├── home/          # Views da página inicial
│   │   ├── layouts/       # Layouts compartilhados
│   │   ├── nutritionist/  # Views específicas de nutricionistas
│   │   ├── restaurant/    # Views específicas de restaurantes
│   │   └── user/          # Views de perfil de usuário
│   └── init.php           # Arquivo de inicialização
├── .htaccess              # Redirecionamento para o diretório public
├── web.config             # Configuração para IIS (Windows)
├── deploy.sh              # Script de implantação
└── README.md              # Este arquivo
```

## Convenções de Codificação

- **Estilo de Codificação**: PSR-4
- **Padrão de Projeto**: MVC (Model-View-Controller)
- **Idioma do Código**: Inglês
- **Idioma da Interface**: Português (Brasil)
- **Formatação de Datas**: dd/mm/yyyy (formato brasileiro)

## Uso

### Usuários

1. Registre-se como usuário na página inicial
2. Complete seu perfil com medidas e objetivos
3. Procure por nutricionistas e inicie uma conversa
4. Receba planos alimentares personalizados
5. Selecione refeições de restaurantes parceiros
6. Acompanhe seu progresso e aderência ao plano

### Nutricionistas

Apenas o administrador pode criar contas de nutricionistas. Depois de criada, o nutricionista pode:

1. Gerenciar clientes
2. Criar planos alimentares personalizados
3. Comunicar-se com clientes através do chat
4. Acompanhar o progresso e aderência dos clientes

### Restaurantes

Apenas o administrador pode criar contas de restaurantes. Depois de criada, o restaurante pode:

1. Cadastrar ingredientes com informações nutricionais
2. Criar refeições com ingredientes cadastrados
3. Visualizar estatísticas sobre suas refeições

### Administrador

O administrador tem acesso total ao sistema e pode:

1. Gerenciar todos os usuários
2. Criar contas de nutricionistas
3. Criar contas de restaurantes
4. Visualizar estatísticas globais

## API

A aplicação inclui endpoints JSON para integração com aplicativos mobile ou outros sistemas:

- `/api/meals`: Lista refeições disponíveis
- `/api/meals/nutrition`: Retorna detalhes nutricionais de uma refeição
- `/api/ingredients`: Lista ingredientes cadastrados
- `/api/measurements`: Retorna medidas e progresso do usuário

## Segurança

- Senhas armazenadas com hash seguro (Bcrypt)
- Validação de entrada em todos os formulários
- Proteção contra CSRF e XSS
- Controle de acesso baseado em funções
- Validação de sessão em cada requisição

## Customização

### Alterando a Aparência

Os estilos CSS estão localizados em `public/css/styles.css`.

### Configuração da Aplicação

As configurações globais estão localizadas em `src/config/config.php`. Para configurações específicas do ambiente, edite `src/config/application.php`.

## Solução de Problemas

### Banco de Dados

Se houver problemas com o banco de dados:

1. Verifique se o diretório `database` tem permissões de escrita
2. Remova o arquivo `database/nutrition.db` para reiniciar o banco de dados
3. Acesse a aplicação novamente para recriar o banco de dados

### Permissões de Arquivos

Se houver problemas de permissão:

```bash
chmod -R 755 /caminho/para/nutritionphp
chmod 777 /caminho/para/nutritionphp/database
chmod 777 /caminho/para/nutritionphp/public/uploads
```

## Contribuição

Contribuições são bem-vindas! Sinta-se à vontade para abrir issues ou enviar pull requests.

1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/nova-funcionalidade`)
3. Faça commit das suas alterações (`git commit -m 'Adiciona nova funcionalidade'`)
4. Envie para o branch (`git push origin feature/nova-funcionalidade`)
5. Abra um Pull Request

## Licença

Este projeto está licenciado sob a [MIT License](LICENSE).

## Autor

Desenvolvido como parte de um projeto para conectar nutricionistas, usuários e restaurantes em uma plataforma integrada de gestão nutricional.