# Rest API - Users Transactions

<p align="center">
    <a href="http://makeapullrequest.com">
        <img src="https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=flat-square" alt="PRs Welcome">
    </a>
    <a href="https://en.wikipedia.org/wiki/Proprietary_software">
        <img src="https://img.shields.io/badge/license-Proprietary-blue.svg?style=flat-square" alt="License MIT">
    </a>
</p>

## Configure

### Database
- **Version:** MySQL 5.7
- **Name:** userstransactions
- **Migration:** [Laravel Migration](https://laravel.com/docs/9.x/migrations)
---
### Back-end
- **Host:** userstransactions.local
- **PHP:** ^8.0.2
---
### Install & Requeriments
#### Requeriments
- Laravel 9.x Requeriments https://lumen.laravel.com/docs/5.8
- PHP ^8.0.2
- MySQL 5.7
- Composer && php artisan installed
--
#### Install
- Clone this repository on a clean folder: [Github](https://github.com/matmper/php-laravel-users-transactions)
- Run your docker
- Run this command: `docker-compose up`
#### Routes
---
- Use the `./routes/collection.js` to use the routes documentation
- Attention to the new patterns d of route files:
    - `routes/web.php` is the public route: www.yoursite.com/public/{route} (no auth)
    - `routes/api.php` is the root api path: www.yoursite.com/public/{route} (auth)

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

#### Database Migration
- Use [Laravel Migration](https://laravel.com/docs/9.x/migrations)
- Run `php artisan migration`
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
    - ```composer phpcs``` or  ```php vendor/bin/phpcs```
- PHP Code Beautifier and Fixer
    - ```composer phpcbf``` or ```php vendor/bin/phpcbf```
