# Rest API - Users Transactions

<p align="center">
    <a href="http://makeapullrequest.com">
        <img src="https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=flat-square" alt="PRs Welcome">
    </a>
    <a href="https://github.com/matmper/php-laravel-users-transactions/actions/workflows/github_actions.yml?query=branch%3Amain+event%3Apush">
        <img src="https://github.com/github/docs/actions/workflows/main.yml/badge.svg?event=push" alt="License MIT">
    </a>
    <a href="https://en.wikipedia.org/wiki/Proprietary_software">
        <img src="https://img.shields.io/badge/license-Proprietary-blue.svg?style=flat-square" alt="License MIT">
    </a>
</p>

### Install & Requeriments
#### Requeriments
- Docker
- Docker Compose
---
#### Install
- Clone this repository on a clean folder: [Github](https://github.com/matmper/php-laravel-users-transactions)
- Start docker and run this commands: 
```base
$ cp .env.example .env
$ make build
```
- Use `make up` to start or `make down` to stop

---
## Documentation
- Diagram (Wiki): [click](https://github.com/matmper/php-laravel-users-transactions/wiki/Diagrama)
- Flowchart (Wiki): [click](https://github.com/matmper/php-laravel-users-transactions/wiki/Fluxograma---Transa%C3%A7%C3%B5es)
- Database Relationship (Wiki): [click](https://github.com/matmper/php-laravel-users-transactions/wiki/Relacionamento-de-Dados)
- Swagger: `./docs/swagger.json` (up application and access `http://localhost:81` to use SwaggerUI).

---
## Auth (development)
In development envrioment we offer two tests accounts, one is a commum user and other one is a store.

- User - PF (credits: R$49,50)
```json
{
    "documentNumber": "11122233301",
    "password": "mypass"
}
```

- Store - PJ (credits: R$1,50)
```json
{
    "documentNumber": "11222333000101",
    "password": "mypass"
}
```
---
#### Routes
- Attention to the new patterns d of route files:
    - `routes/web.php` for public routes (no auth)
    - `routes/api.php` for private api routes (auth)

- Use Laravel patterns to use all routes:

| Verb | Path | Action | Route Name | Desc |
|--|--|--|--|--|
| GET | /users | index | user.index | get all users |
| GET | /users/create | create | user.create | view to create user |
| POST | /users | store | user.store | save new user |
| GET | /users/{id} | show | user.show | get and user |
| GET | /users/{id}/edit | edit | user.edit | get user data to edit |
| PATCH/PUT | /users/{id} | update | user.update | save new user data |
| DELETE | /users/{id} | destroy | user.destroy | delete an user |

---
#### Create models
- You can use [reliese/laravel](https://github.com/reliese/laravel) library to generate a new model
- To generate this, use this command:
    - ```docker-compose exec webapp php artisan code:models --table=yourtable```
---
#### Create repositories
- You can use this custom command to generate a new repository
    - `docker-compose exec webapp php artisan repository:create {modelName}`
    - `docker-compose exec webapp php artisan repository:create all`
---
#### Tests - PHP Unit
- Library: [PHP Unit 10](https://phpunit.de/getting-started/phpunit-10.html)
- Use this commands for run testes
    - `make tests`
---
#### Code Sniffer & Code Beautifier
Use this commands for keep code defaults:
- PHP Code Check
    - `make code-check`
- PHP Code Beautifier and Fixer
    - `make phpcbf`
---
## License
This repository use [MIT License](https://choosealicense.com/licenses/mit/)
