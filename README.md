# Onfly Travel Order API

Uma API RESTful desenvolvida em Laravel para gerenciamento de pedidos de viagem, com autenticação via Laravel Sanctum e documentação automática via Swagger.

## Decisões Técnicas e Arquiteturais

Este projeto foi desenvolvido seguindo as melhores práticas do ecossistema Laravel, com foco em simplicidade, escalabilidade e facilidade de manutenção:

- **Framework**: Laravel 13 (mais recente) para aproveitar as últimas funcionalidades e correções de segurança
- **Autenticação**: Laravel Sanctum para API tokens, permitindo autenticação stateless
- **Banco de Dados**: MySQL 8.4 para compatibilidade e performance
- **Containerização**: Docker para isolamento de ambiente e consistência entre desenvolvimento e produção
- **Debugging**: Xdebug configurado para desenvolvimento local
- **Testes**: PHPUnit com factories e seeders para testes automatizados
- **Documentação**: Swagger/OpenAPI gerado automaticamente via `darkaonline/l5-swagger`
- **Estrutura**: Separação clara entre Models, Controllers, Policies e Events
- **Validação**: Form Requests para validação robusta de entrada
- **Autorização**: Policies para controle de acesso baseado em regras de negócio

## Pré-requisitos

- Docker e Docker Compose
- Git
- VS Code (recomendado) com extensão PHP Debug
- Conta no GitHub (opcional, para contribuir)

## Instalação

1. **Clone o repositório:**
   ```bash
   git clone https://github.com/charlenr/onfly-travel-order-api.git
   cd onfly-travel-order-api
   ```

2. **Instale as dependências PHP (via Docker):**
   ```bash
   make setup
   ```
   Este comando irá:
   - Construir as imagens Docker
   - Instalar dependências do Composer
   - Executar migrations
   - Popular o banco com dados de teste

## Configuração do Ambiente

1. **Variáveis de Ambiente:**
   O arquivo `.env` já está configurado para desenvolvimento local. Principais configurações:
   - `APP_ENV=local`
   - `DB_CONNECTION=mysql`
   - `DB_HOST=db`
   - `DB_DATABASE=laravel_db`
   - `SANCTUM_STATEFUL_DOMAINS=localhost:8000`

2. **Banco de Dados:**
   O banco MySQL é automaticamente criado e configurado via Docker Compose. As migrations são executadas durante o `make setup`.

3. **Configuração do Xdebug (Debugging):**
   - Porta: 9003
   - Host: `host.docker.internal`
   - Modo: `debug,develop`
   - Inicie o debugger no VS Code selecionando "Listen for Xdebug"

## Executando o Projeto

1. **Inicie os serviços:**
   ```bash
   docker-compose up -d
   ```

2. **Acesse a aplicação:**
   - API: http://localhost:8000
   - Documentação Swagger: http://localhost:8000/api/documentation

3. **Para desenvolvimento com hot-reload:**
   O volume mapeado permite edição em tempo real dos arquivos.

## Executando os Testes

```bash
# Via Docker (recomendado)
docker-compose exec app-dev php artisan test

# Ou via Makefile
make test
```

Os testes incluem:
- Testes unitários para Models e Services
- Testes de feature para endpoints da API
- Testes de autenticação e autorização

## API Endpoints

### Autenticação
- `POST /api/login` - Login (público)
- `POST /api/logout` - Logout (autenticado)

### Pedidos de Viagem (Travel Orders)
Todas as rotas abaixo requerem autenticação via Bearer Token:

- `GET /api/travel-orders` - Listar pedidos
- `POST /api/travel-orders` - Criar pedido
- `GET /api/travel-orders/{id}` - Ver pedido específico
- `PUT /api/travel-orders/{id}` - Atualizar pedido
- `DELETE /api/travel-orders/{id}` - Deletar pedido
- `PATCH /api/travel-orders/{id}/approve` - Aprovar pedido (admin)
- `PATCH /api/travel-orders/{id}/cancel` - Cancelar pedido

### Status dos Pedidos
- `requested` - Solicitado
- `approved` - Aprovado
- `cancelled` - Cancelado

## Debugging com Xdebug

1. Instale a extensão "PHP Debug" no VS Code
2. Configure o launch.json (já incluído no projeto)
3. Inicie o debugger: Run → Start Debugging → "Listen for Xdebug"
4. Adicione breakpoints no código
5. Faça uma requisição para a API

## Comandos Úteis

```bash
# Construir e iniciar serviços
make setup

# Executar testes
make test

# Acessar container da aplicação
docker-compose exec app-dev bash

# Ver logs
docker-compose logs -f app-dev

# Parar serviços
docker-compose down

# Limpar volumes (reset banco)
docker-compose down -v
```

## Estrutura do Projeto

```
src/
├── app/
│   ├── Domain/TravelOrder/    # Lógica de negócio
│   ├── Events/               # Eventos do sistema
│   ├── Http/Controllers/     # Controllers da API
│   ├── Models/              # Models Eloquent
│   ├── Policies/            # Autorização
│   └── Providers/           # Service Providers
├── database/
│   ├── factories/           # Factories para testes
│   ├── migrations/          # Migrations do banco
│   └── seeders/            # Seeders
├── routes/
│   └── api.php             # Definição das rotas
└── tests/                  # Testes automatizados
```

## Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/nova-feature`)
3. Commit suas mudanças (`git commit -am 'Adiciona nova feature'`)
4. Push para a branch (`git push origin feature/nova-feature`)
5. Abra um Pull Request

## Licença

Este projeto está sob a licença MIT. Veja o arquivo LICENSE para mais detalhes.

## Suporte

Para dúvidas ou problemas:
- Abra uma issue no GitHub
- Consulte a documentação do Swagger em `/api/documentation`
- Verifique os logs do container: `docker-compose logs app-dev`</content>
<parameter name="filePath">/Users/charlenrodrigues/workfolder/onfly/README.md