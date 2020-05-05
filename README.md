# VueSymf
Symfony and view starter pack.

![Symfony + Vue 2](https://miro.medium.com/max/1400/1*e5e6y_DL4AjsQG3aewrVpw.png)

Sample project based on Symfony 4 abd Vue 2. Main purpose for this project is to generating keys by input data from user.


## Backend installation
Based on symfony framework https://symfony.com/

All steps u need to make omn ./backend dir

### 1. Setting up local environment
After creating ocker image on setting local machine and block to ./public as root. Minimal requirments 
and instruction u can find on couple articles:

https://symfony.com/doc/4.4/setup/homestead.html

https://symfony.com/doc/4.4/setup/built_in_web_server.html (if u choice this point, remember to install mysql also).

https://symfony.com/doc/4.4/setup/web_server_configuration.html

U will need to install also ```composer``` to install php packages.

### 2. .env file
Run `cp .env .env.local`, go to `.env.local` and pass your own settings

### 3. Installing app
``` 
composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

After that steps u should see by entering on localhost (or your own domain), API documentation:

![API](https://i.ibb.co/r6Nchf1/Screenshot-2020-05-05-at-10-56-30.png)

## Frontend
Based on https://vuejs.org/

### 1. Setting up local environment
Install node and npm

### 2. Set variables
change api host in ./src/components/ThirdStep.vue[:58] 

.env file support is on @todo list

### 3. Running dev env
To run frontend app just enter to ./frontend dir and turn on server by typing
```
npm install
npm run dev
```
U should see dev version of app.

![frontend app](https://i.ibb.co/7R33VVV/Screenshot-2020-05-05-at-10-59-00.png)

## Extra

### Results
U can see results of typed element on api doc page.

### CI
After make your changes fix code by typing `composer csfix` to clean code style

There is also configured phpstan to validate your code, just run `composer phpstan`

### @todo
1. add .env support for frontend app
2. translate calendar to polish lang
