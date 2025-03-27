# Sistema de Gerenciamento de Chamados

Sistema desenvolvido em Laravel 11 para gerenciamento de chamados de suporte, permitindo a criação, acompanhamento e resolução de chamados por diferentes setores da organização.

## Funcionalidades

- **Gestão de Estrutura Organizacional**
  - Centros de Custo
  - Setores
  - Atendentes
  - Usuários

- **Gestão de Chamados**
  - Criação de chamados
  - Atribuição de prioridades
  - Transferência entre setores
  - Histórico de alterações
  - Resolução de chamados

- **Controle de Acesso**
  - Autenticação via API
  - Diferentes níveis de acesso (Usuário/Atendente)
  - Visualização restrita por setor

## Requisitos

- PHP 8.2 ou superior
- Composer
- MySQL
- Node.js (para o frontend com Vite/React)

## Instalação

### 1. Clone o Repositório
```bash
git clone https://github.com/seu-usuario/api_chamados_nxt.git
cd api_chamados_nxt
```

### 2. Instale as Dependências
```bash
composer install
```

### 3. Configure o Ambiente
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure o Banco de Dados
Edite o arquivo `.env` e configure as credenciais do banco de dados:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=seu_banco
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### 5. Execute as Migrações
```bash
php artisan migrate
```

### 6. Popule o Banco com Dados Iniciais (Opcional)
```bash
php artisan db:seed
```

### 7. Inicie o Servidor
```bash
php artisan serve
```

## Estrutura do Sistema

### Hierarquia Organizacional
1. **Centros de Custo**
   - Representam as áreas principais da organização
   - Exemplo: TI, RH, Financeiro

2. **Setores**
   - Subdivisões dos Centros de Custo
   - Exemplo: Suporte TI, Desenvolvimento, Infraestrutura

3. **Atendentes**
   - Membros da equipe que atendem os chamados
   - Vinculados a um ou mais setores
   - Possuem um usuário associado no sistema

4. **Usuários**
   - Podem ser solicitantes ou atendentes
   - Atendentes têm um perfil específico vinculado

### Fluxo de Chamados

1. **Criação**
   - Usuário cria um chamado selecionando o setor
   - Define título, descrição e prioridade
   - O chamado é criado com status "aberto"

2. **Atendimento**
   - Atendentes do setor veem o chamado em sua lista
   - Podem atualizar status e adicionar observações
   - Podem transferir para outro setor se necessário

3. **Resolução**
   - Atendente marca o chamado como resolvido
   - Adiciona observações sobre a resolução
   - O chamado é fechado com data de finalização

## API Endpoints

### Autenticação
```
POST /api/login
POST /api/register
POST /api/logout
```

### Centros de Custo
```
GET    /api/centros_de_custo
POST   /api/centros_de_custo
GET    /api/centros_de_custo/{id}
PUT    /api/centros_de_custo/{id}
DELETE /api/centros_de_custo/{id}
```

### Setores
```
GET    /api/setores
POST   /api/setores
GET    /api/setores/{id}
PUT    /api/setores/{id}
DELETE /api/setores/{id}
```

### Atendentes
```
GET    /api/atendentes
POST   /api/atendentes
GET    /api/atendentes/{id}
PUT    /api/atendentes/{id}
DELETE /api/atendentes/{id}
```

### Chamados
```
GET    /api/chamados
POST   /api/chamados
GET    /api/chamados/{id}
PUT    /api/chamados/{id}
GET    /api/chamados/{id}/historico
POST   /api/chamados/{id}/transferir
POST   /api/chamados/{id}/resolver
```

## Documentação da API

A documentação completa da API está disponível através do Swagger UI após iniciar o servidor:
```
http://localhost:8000/api/documentation
```

## Segurança

- Autenticação via Sanctum
- Tokens de acesso
- Controle de acesso baseado em setor
- Histórico de todas as alterações

## Desenvolvimento

### Gerar Documentação Swagger
```bash
php artisan l5-swagger:generate
```

### Executar Testes
```bash
php artisan test
```

## Contribuição

1. Faça um Fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.
