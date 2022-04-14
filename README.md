# Rest API - Users Transactions

<p align="center">
    <a href="http://makeapullrequest.com">
        <img src="https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=flat-square" alt="PRs Welcome">
    </a>
    <a href="https://en.wikipedia.org/wiki/Proprietary_software">
        <img src="https://img.shields.io/badge/license-Proprietary-blue.svg?style=flat-square" alt="License MIT">
    </a>
</p>

### Install & Requeriments
#### Requeriments
- Laravel 9.x Requeriments
- Docker Installed
---
#### Install
- Clone this repository on a clean folder: [Github](https://github.com/matmper/php-laravel-users-transactions)
- Execute your docker
- Run this commands: 
```base
$ docker-compose up
$ docker-compose exec webapp cp .env.example .env 
$ docker-compose exec webapp composer install 
$ docker-compose exec webapp php artisan key:generate 
$ docker-compose exec webapp php artisan jwt:secret
$ docker-compose exec webapp php artisan migrate
```
---
## Documentation
- Routes (Postman): [show collection](https://documenter.getpostman.com/view/8724744/Uyr4LL6b)
- Flowchart (Wiki): [click](https://github.com/matmper/php-laravel-users-transactions/wiki/Fluxograma)

---
## Auth (development)
In development envrioment we offer two tests accounts, one is a commum user and other one is a store.
You can user [POST] /auth to create a new user.

- User (credits: R$49,50)
```json
{
    "documentNumber": "11122233344",
    "password": "mypass"
}
```

- Store (credits: R$1,50)
```json
{
    "documentNumber": "11222333000144",
    "password": "mypass"
}
```
---
#### Routes
- Attention to the new patterns d of route files:
    - `routes/web.php` is the public route: www.yoursite.com/public/{route} (no auth)
    - `routes/api.php` is the root api path: www.yoursite.com/{route} (auth)

- Use Laravel patterns to use all routes:

| Verb | Path | Action | Route Name | Desc |
|--|--|--|--|--|
| GET | /user | index | user.index | get all users |
| GET | /user/create | create | user.create | view to create user |
| POST | /user | store | user.store | save new user |
| GET | /user/{id} | show | user.show | get and user |
| GET | /user/{id}/edit | edit | user.edit | get user data to edit |
| PATCH/PUT | /user/{id} | update | user.update | save new user data |
| DELETE | /user/{id} | destroy | user.destroy | delete an user |

---
#### Create models
- You can use [reliese/laravel](https://github.com/reliese/laravel) library to generate a new model
- Get your connection name in `database.php` file
- To generate this, use this command:
    - ```php artisan code:models --table=yourtable```
---
#### Create repositories
- You can use this custom command to generate a new repository
    - `php artisan repository:create {modelName}`
    - `php artisan repository:create all`
---
#### Code Sniffer & Code Beautifier
Use this commands for keep code defaults:
- PHP Code Sniffer
    - `php vendor/bin/phpcs`
- PHP Code Beautifier and Fixer
    - `php vendor/bin/phpcbf`
---
## License
This repository use [MIT License](https://choosealicense.com/licenses/mit/)
