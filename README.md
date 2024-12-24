# Fullstack Challenge 🏅 - Dictionary

Este é um desafio de back-end onde foi desenvolvido um sistema para interagir com a API Free Dictionary, permitindo que os usuários busquem palavras, as adicionem aos favoritos, visualizem o histórico de palavras e realizem o login de forma segura.

## Tecnologias

- **Linguagem**: PHP
- **Framework**: Laravel
- **Banco de Dados**: MongoDB
- **Autenticação**: JWT (JSON Web Token)
- **Docker**: Para ambiente de desenvolvimento e deploy
- **Documentação da API**: OpenAPI 3.0 (Swagger)

## Instalação e Uso

### Pré-requisitos

1. Docker instalado na máquina.
2. Composer para gerenciar dependências do PHP.
3. Docker configurado para rodar esses serviços.

### Passos para rodar o projeto caso esteja usando Docker:

1. **Clone o repositório e acesse o diretório do projeto**:
   ```bash
   git clone https://github.com/Jardelsr/backend-dictionary.git
   cd backend-dictionary       
   cd dictionary-api

2. **Renomeie o arquivo `.env.example` para `.env`**

3. **Suba os containers com Docker (caso esteja usando Docker)**:
    ```bash
    docker-compose up -d

4. **Acesse a API**: Agora você pode acessar a API na URL: http://localhost:8000/api/ no Postman ou pela documentação, que pode ser acessada diretamente através do Swagger por meio da URL: http://localhost:8000/api/documentation.

### Passos para rodar o projeto localmente:

1. **Clone o repositório e acesse o diretório do projeto**:
   ```bash
   git clone https://github.com/Jardelsr/backend-dictionary.git
   cd backend-dictionary       
   cd dictionary-api

2. **Renomeie o arquivo `.env.example` para `.env`**

3. **Instale as dependências do projeto**: Se estiver rodando fora do Docker, instale as dependências com Composer:
    ```bash
    composer install

4. **Rodando o servidor de desenvolvimento**: Após a instalação e configuração, você pode rodar o servidor localmente com o Laravel:
    ```bash
    php artisan serve

5. **Acesse a API**: Agora você pode acessar a API no Postman ou pela documentação, que pode ser acessada diretamente através do Swagger por meio da URL: http://localhost:8000/api/documentation.

## Funcionalidades e suas respectivas rotas
```bash
# Home
Home [GET] /api

# Autenticação
Cadastro de usuário: [POST] /auth/signup
Login de usuário: [POST] /auth/signin

# Gestão de Palavras
Listar palavras: [GET] /entries/en
Detalhes de uma palavra: [GET] /entries/en/{word}
Adicionar palavra aos favoritos: [POST] /entries/en/{word}/favorite
Remover palavra dos favoritos: [DELETE] /entries/en/{word}/unfavorite

# Histórico e Favoritos do Usuário
Visualizar histórico de palavras: [GET] /user/me/history
Visualizar palavras favoritas: [GET] /user/me/favorites
```

## Processo de Investigação e Desenvolvimento
1. **Análise do Desafio**: Nessa etapa fiz uma análise do que foi exigido e que ferramentas poderia utilizar para implementa-lo.

2. **Escolha das Tecnologias**:
    - Escolhi o Laravel porque tenho familiriade com ele e é a stack usada no cargo ao qual me candidato.
    - MongoDB foi escolhido porque achei o serviço de banco de dados gratuito do MongoDB Atlas, o melhor dentre as opções.
    - Docker para garantir que o ambiente de desenvolvimento seja facilmente configurável e consistente entre diferentes desenvolvedores.

3. **Implementação das Funcionalidades**: A API foi construída em etapas, começando pela implementação da autenticação com JWT, seguida pela criação dos endpoints de busca de palavras, favoritos e histórico.

4. **Cache**: Depois utilizei um middleware para armazenar as respostas da API externa, associando a chave de cache à URL da requisição. Isso garantiu que buscas repetidas por palavras não precisassem ser feitas novamente para cada usuário.

5. **Documentação**: A documentação foi criada utilizando OpenAPI 3.0, e o Swagger foi integrado para facilitar a visualização dos endpoints e das respostas da API.



This is a challenge by Coodesh.