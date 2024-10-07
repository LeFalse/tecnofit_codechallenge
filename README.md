# Tecnofit Ranking API

- [Descrição](#descrição)
- [Tecnologias Utilizadas](#tecnologias-utilizadas)
- [Pré-requisitos](#pré-requisitos)
- [Instalação](#instalação)
    - [Clone o Repositório](#clone-o-repositório)
    - [Configuração do Ambiente](#configuração-do-ambiente)
    - [Construir e Iniciar os Containers](#construir-e-iniciar-os-containers)
    - [Instalar Dependências PHP](#instalar-dependências-php)
- [Configuração do Banco de Dados](#configuração-do-banco-de-dados)
    - [Execução das Migrations e Seeders](#execução-das-migrations-e-seeders)
- [Uso da API](#uso-da-api)
    - [Endpoint de Ranking](#endpoint-de-ranking)
    - [Exemplo de Requisição e Resposta](#exemplo-de-requisição-e-resposta)
- [Testes](#testes)
    - [Executando Testes de Unidade](#executando-testes-de-unidade)
- [Estrutura do Projeto](#estrutura-do-projeto)
- [Comandos Úteis](#comandos-úteis)
- [Considerações Finais](#considerações-finais)

## Descrição

Este projeto é um desafio de codificação da **Tecnofit**, desenvolvido como parte de um processo seletivo. Ele consiste em uma API REST construída com Laravel que fornece o ranking de movimentos específicos com base nos recordes pessoais dos usuários.

## Tecnologias Utilizadas

- **Docker** 24.0.2: Containerização da aplicação.
- **Docker Compose** 1.29.2: Orquestração de múltiplos containers.
- **Git** 2.34.1: Controle de versão.
- **Laravel** v11.26.0: Framework PHP.
- **PHP-FPM** v8.2.10-fpm: Linguagem de programação.
- **Nginx**: Servidor web.
- **MySQL** 8: Banco de dados relacional.
- **PHPUnit** 11.4: Framework de testes para PHP.

## Pré-requisitos

Antes de iniciar, certifique-se de ter as seguintes ferramentas instaladas em sua máquina:

- [Docker](https://www.docker.com/get-started) (v.24.0.2)
- [Docker Compose](https://docs.docker.com/compose/install/) (v.1.29.2)
- [Git](https://git-scm.com/downloads) (v.2.34.1)

## Instalação

### Clone o Repositório

```bash
git clone https://github.com/LeFalse/tecnofit_codechallenge.git
cd tecnofit_codechallenge
```

## Configuração do Ambiente

### Construir e Iniciar os Containers

Use o `Makefile` (GNU Make 4.3) fornecido para facilitar o processo de construção e inicialização dos containers Docker:

```bash
make rebuild
```

Este comando irá:

1. Parar e remover quaisquer containers existentes.
2. Construir as imagens Docker necessárias.
3. Iniciar os containers em modo destacado (background).

Alternativamente, você pode usar os comandos do Docker Compose diretamente:

```bash
docker-compose down
docker-compose up --build -d
```

### Instalar Dependências PHP

Após iniciar os containers, é necessário instalar as dependências do PHP usando o Composer. Execute o seguinte comando para acessar o container da aplicação e instalar as dependências:

```bash
docker-compose exec app bash
composer install
```

Este comando irá:

1. Acessar o container `app`.
2. Executar `composer install` para instalar todas as dependências do Laravel.

## Configuração do Banco de Dados

Após iniciar os containers e instalar as dependências, é necessário configurar o banco de dados e popular com os dados de teste fornecido.

### Execução das Migrations e Seeders

Dentro do container da aplicação, execute as migrations e seeders do Laravel:

```bash
docker-compose exec app bash
php artisan migrate --seed
```

Este comando irá:

1. Criar as tabelas necessárias no banco de dados MySQL.
2. Popular as tabelas com os dados fornecidos no `DatabaseSeeder`.

## Uso da API

A API deste projeto fornece um endpoint para obter o ranking de um movimento específico.

### Endpoint de Ranking

- **URL:** `/api/ranking/{movementId}`
- **Método:** `GET`
- **Descrição:** Retorna o ranking de um determinado movimento, incluindo o nome do movimento e uma lista ordenada com os usuários, seu respectivo recorde pessoal (maior valor), posição e data. Utilizando o método de ranking denso "1223".

### Exemplo de Requisição e Resposta

**Requisição:**

```http
GET http://localhost:8094/api/ranking/1
```

**Resposta:**

```json
{
    "movement": "Deadlift",
    "ranking": [
        {
            "position": 1,
            "user": "Jose",
            "max_value": 190.0,
            "date": "2021-01-06 00:00:00"
        },
        {
            "position": 2,
            "user": "Joao",
            "max_value": 180.0,
            "date": "2021-01-02 00:00:00"
        },
        {
            "position": 3,
            "user": "Paulo",
            "max_value": 170.0,
            "date": "2021-01-01 00:00:00"
        }
    ]
}
```

## Testes

Este projeto inclui testes de unidade para garantir a qualidade e funcionalidade do código.

### Executando Testes de Unidade

Para executar os testes de unidade:

1. **Acesse o a pasta "/src" e execute o código:**

   ```bash
   vendor/bin/phpunit
   ```

   **Saída Esperada:**

   ```bash
   PHPUnit 11.4.0 by Sebastian Bergmann and contributors.

   ...                                                                 3 / 3 (100%)

   Time: 00:00.098, Memory: 26.00 MB
   ```

   Todos os testes devem passar sem erros.

## Estrutura do Projeto

```
├── Dockerfile
├── Makefile
├── docker-compose.yml
├── nginx
│   └── conf.d
│       └── app.conf
├── src
│   ├── app
│   │   ├── Entities
│   │   │   ├── Movement.php
│   │   │   ├── PersonalRecord.php
│   │   │   └── User.php
│   │   ├── Repositories
│   │   │   └── RankingRepositoryInterface.php
│   │   ├── UseCases
│   │   │   └── GetMovementRanking.php
│   ├── bootstrap
│   ├── config
│   ├── database
│   │   ├── migrations
│   │   └── seeders
│   ├── public
│   ├── resources
│   ├── routes
│   ├── storage
│   └── tests
│       └── Unit
│           └── UseCases
│               └── GetMovementRankingTest.php
├── README.md
└── ... outros arquivos do Laravel
```

- **nginx/conf.d/app.conf:** Configuração do Nginx para o Laravel.
- **docker-compose.yml:** Definição dos serviços Docker (app, webserver, db).
- **Dockerfile:** Configuração da imagem Docker para a aplicação PHP.
- **Makefile:** Comandos utilitários para facilitar operações com Docker.
- **src/app:** Código-fonte da aplicação Laravel, organizado em camadas (Entities, Repositories, UseCases).
- **src/database:** Migrations e seeders para configuração do banco de dados.
- **src/routes:** Definição das rotas da API.
- **src/tests/Unit/UseCases/GetMovementRankingTest.php:** Testes de unidade para o caso de uso de ranking.
- **src/phpunit.xml:** Configuração do PHPUnit.

## Comandos Úteis

- **Rebuild dos Containers:**

  ```bash
  make rebuild
  ```

- **Parar os Containers:**

  ```bash
  docker-compose down
  ```

- **Iniciar os Containers:**

  ```bash
  docker-compose up -d
  ```

- **Instalar Dependências PHP:**

  ```bash
  docker-compose exec app composer install
  ```

- **Executar Migrations e Seeders:**

  ```bash
  docker-compose exec app php artisan migrate --seed
  ```

- **Executar Testes de Unidade:**

  ```bash
  docker-compose exec app vendor/bin/phpunit
  ```

- **Acessar o Container da Aplicação:**

  ```bash
  docker-compose exec app bash
  ```

## Considerações Finais

Este projeto foi desenvolvido seguindo as melhores práticas de desenvolvimento para garantir um código limpo, organizado e pronto para produção. Ele demonstra a capacidade de construir APIs RESTful utilizando Laravel e Docker.

### Autor

**Fábio Henrique**  
**Contato:** https://www.linkedin.com/in/fabio-henrique-dev

---

Para qualquer dúvida ou contribuição, sinta-se à vontade para abrir uma issue ou enviar um pull request no repositório Git.