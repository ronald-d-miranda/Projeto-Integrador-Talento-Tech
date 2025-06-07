# Clínica Mais Saúde

Sistema full-stack em PHP para gerenciamento de consultas médicas, exames e histórico clínico, seguindo os princípios MVC e com autenticação JWT.

## Tecnologias Utilizadas

- PHP 8.2
- MySQL 8.0
- Nginx
- Docker e Docker Compose
- Composer
- JWT para autenticação
- Bootstrap 5 para interface

## Estrutura do Projeto

O projeto segue a arquitetura MVC (Model-View-Controller) com separação clara de responsabilidades:

```
sistema-medico/
├── docker/                  # Configurações do Docker
│   ├── mysql/               # Configurações do MySQL
│   ├── nginx/               # Configurações do Nginx
│   └── php/                 # Configurações do PHP
├── public/                  # Ponto de entrada da aplicação
│   └── index.php            # Front controller
├── src/                     # Código fonte da aplicação
│   ├── Auth/                # Autenticação e middleware
│   ├── Config/              # Configurações da aplicação
│   ├── Controller/          # Controladores
│   ├── Database/            # Conexão com banco de dados
│   ├── Model/               # Modelos de dados
│   ├── Service/             # Serviços (lógica de negócio)
│   └── View/                # Visualizações
└── vendor/                  # Dependências (gerenciadas pelo Composer)
```

## Princípios de Design

O sistema foi desenvolvido seguindo os seguintes princípios:

1. **Separação de Responsabilidades**:
   - **Controllers**: Recepcionam requisições, validam dados e delegam para os serviços.
   - **Models**: Mapeiam estruturas de dados e gerenciam persistência.
   - **Views**: Apenas exibem dados, sem lógica de negócio.
   - **Services**: Contêm toda a lógica de negócio da aplicação.

2. **Segurança**:
   - Autenticação via JWT
   - Controle de acesso baseado em tipo de usuário
   - Proteção contra SQL Injection
   - Senhas armazenadas com hash

3. **Responsividade**:
   - Interface adaptável a diferentes dispositivos (mobile-first)

## Requisitos

- Docker e Docker Compose
- Git

## Instalação e Execução

1. Clone o repositório:
   ```bash
   git clone [url-do-repositorio]
   cd sistema-medico
   ```

2. Configure as variáveis de ambiente:
   ```bash
   cp .env.example .env
   # Edite o arquivo .env conforme necessário
   ```

3. Inicie os containers Docker:
   ```bash
   docker-compose up -d
   ```

4. Instale as dependências:
   ```bash
   docker-compose exec php composer install
   ```

5. Acesse a aplicação:
   ```
   http://localhost:8080
   ```

## Funcionalidades

### Autenticação
- Login de usuários (médicos e pacientes)
- Registro de novos usuários
- Autenticação via JWT

### Pacientes
- Cadastro e gerenciamento de dados pessoais
- Visualização de histórico de consultas e exames
- Agendamento de consultas

### Médicos
- Cadastro e gerenciamento de dados profissionais
- Visualização de agenda de consultas
- Registro de consultas realizadas
- Solicitação e visualização de exames

### Consultas
- Agendamento de consultas
- Cancelamento de consultas
- Registro de diagnóstico e receita
- Verificação de disponibilidade de médicos

### Exames
- Registro de exames
- Visualização de resultados
- Histórico de exames por paciente

## Estrutura do Banco de Dados

O sistema utiliza um banco de dados MySQL com as seguintes tabelas principais:

- `pessoas`: Dados comuns a médicos e pacientes
- `pacientes`: Dados específicos de pacientes
- `medicos`: Dados específicos de médicos
- `consultas`: Registro de consultas
- `exames`: Registro de exames

## Regras de Negócio

1. Um médico não pode ter duas consultas no mesmo horário
2. Um paciente não pode ter duas consultas no mesmo horário
3. Consultas só podem ser agendadas em horário comercial (8h às 18h)
4. Consultas só podem ser canceladas com pelo menos 24h de antecedência
5. Apenas médicos podem registrar exames
6. Pacientes só podem ver seus próprios dados
7. Médicos podem ver dados de todos os pacientes

## Contribuição

Para contribuir com o projeto:

1. Faça um fork do repositório
2. Crie uma branch para sua feature (`git checkout -b feature/nova-funcionalidade`)
3. Faça commit das suas alterações (`git commit -m 'Adiciona nova funcionalidade'`)
4. Faça push para a branch (`git push origin feature/nova-funcionalidade`)
5. Abra um Pull Request

## Licença

Este projeto está licenciado sob a licença MIT - veja o arquivo LICENSE para detalhes.

