# Sistema de Gerenciamento de Chamados

Esta é uma API desenvolvida em Laravel 11 para gerenciar um sistema de chamados.

## Requisitos

- PHP 8.2 ou superior
- Composer
- MySQL
- Node.js (para o frontend com Vite/React)

## Instalação

### Backend (Laravel)

1. Clone o repositório:
    ```bash
    git clone https://github.com/seu-usuario/api_chamados_nxt.git    
    cd sistema-de-chamados
    ```

2. Instale as dependências do PHP com o Composer:
    ```bash
    composer install
    ```

3. Copie o arquivo `.env.example` para `.env` e configure as variáveis de ambiente, especialmente o banco de dados:
    ```bash
    cp .env.example .env
    ```

4. Gere a chave da aplicação:
    ```bash
    php artisan key:generate
    ```

5. Execute as migrações para criar as tabelas no banco de dados:
    ```bash
    php artisan migrate
    ```

6. (Opcional) Popule o banco de dados com dados fictícios:
    ```bash
    php artisan db:seed
    ```

7. Inicie o servidor de desenvolvimento:
    ```bash
    php artisan serve
    ```

## Endpoints da API

### Autenticação

- **Login**
    ```http
    POST /api/login
    ```
    Body:
    ```json
    {
        "email": "seu-email@example.com",
        "password": "sua-senha"
    }
    ```

- **Registro**
    ```http
    POST /api/register
    ```
    Body:
    ```json
    {
        "name": "Seu Nome",
        "email": "seu-email@example.com",
        "password": "sua-senha",
        "password_confirmation": "sua-senha",
        "telefone": "123456789" // opcional
    }
    ```

- **Logout**
    ```http
    POST /api/logout/{user}
    ```

### Centros de Custo

- **Listar todos os centros de custo**
    ```http
    GET /api/centros_de_custo
    ```

- **Mostrar um centro de custo específico**
    ```http
    GET /api/centros_de_custo/{id}
    ```

- **Criar um novo centro de custo**
    ```http
    POST /api/centros_de_custo
    ```
    Body:
    ```json
    {
        "nome": "Nome do Centro",
        "descricao": "Descrição do Centro",
        "codigo": "001"
    }
    ```

- **Atualizar um centro de custo existente**
    ```http
    PUT /api/centros_de_custo/{id}
    ```
    Body:
    ```json
    {
        "nome": "Nome Atualizado",
        "descricao": "Descrição Atualizada",
        "codigo": "002"
    }
    ```

- **Deletar um centro de custo**
    ```http
    DELETE /api/centros_de_custo/{id}
    ```

### Setores

- **Listar todos os setores**
    ```http
    GET /api/setores
    ```

- **Mostrar um setor específico**
    ```http
    GET /api/setores/{id}
    ```

- **Criar um novo setor**
    ```http
    POST /api/setores
    ```
    Body:
    ```json
    {
        "nome": "Nome do Setor",
        "descricao": "Descrição do Setor",
        "codigo": "001",
        "centro_de_custo_id": 1
    }
    ```

- **Atualizar um setor existente**
    ```http
    PUT /api/setores/{id}
    ```
    Body:
    ```json
    {
        "nome": "Nome Atualizado",
        "descricao": "Descrição Atualizada",
        "codigo": "002",
        "centro_de_custo_id": 1
    }
    ```

- **Deletar um setor**
    ```http
    DELETE /api/setores/{id}
    ```

### Atendentes

- **Listar todos os atendentes**
    ```http
    GET /api/atendentes
    ```

- **Mostrar um atendente específico**
    ```http
    GET /api/atendentes/{id}
    ```

- **Criar um novo atendente**
    ```http
    POST /api/atendentes
    ```
    Body:
    ```json
    {
        "nome": "Nome do Atendente",
        "email": "atendente@example.com",
        "telefone": "123456789",
        "setor_id": 1, // opcional
        "is_gestor": false // opcional
    }
    ```

- **Atualizar um atendente existente**
    ```http
    PUT /api/atendentes/{id}
    ```
    Body:
    ```json
    {
        "nome": "Nome Atualizado",
        "email": "atualizado@example.com",
        "telefone": "987654321",
        "setor_id": 1, // opcional
        "is_gestor": false // opcional
    }
    ```

- **Deletar um atendente**
    ```http
    DELETE /api/atendentes/{id}
    ```

### Setores Atendentes

- **Listar todos os setores atendentes**
    ```http
    GET /api/setores_atendentes
    ```

- **Mostrar um setor atendente específico**
    ```http
    GET /api/setores_atendentes/{id}
    ```

- **Criar um novo setor atendente**
    ```http
    POST /api/setores_atendentes
    ```
    Body:
    ```json
    {
        "setor_id": 1,
        "atendente_id": 1,
        "is_gestor": false
    }
    ```

- **Atualizar um setor atendente existente**
    ```http
    PUT /api/setores_atendentes/{id}
    ```
    Body:
    ```json
    {
        "setor_id": 1,
        "atendente_id": 1,
        "is_gestor": false
    }
    ```

- **Deletar um setor atendente**
    ```http
    DELETE /api/setores_atendentes/{id}
    ```

## Licença

Este projeto está licenciado sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.
