PLEASE SEE THE README FILE IN CODE FORMAT

STEP 1 :
https://github.com/SumitBhawal/Jisho-Dockerfile/tree/main
PULL/DOWNLOAD THIS REPOSITORY.

STEP 2 :
PULL/DOWNLOAD the Jisho (https://github.com/SumitBhawal/Jisho) repository.

STEP 3 :
Follow the folder structure :
src/
├── dockerfile/
│   ├── composer
│   ├── nginx
│   └── php
├── env/
│   └── mysql.env
├── Jisho/
│   └── (Laravel app files and directories)
├── kuromoji-service/
│   ├── node_modules/
│   ├── index.js
│   ├── package.json
│   └── package-lock.json
├── nginx/
│   └── nginx.conf
└── docker-compose.yml

The repository Jisho pulled in step 2 will come under the Jisho folder. 

STEP 4 :
Rename the kuromoji-service-files to kuromoji-service
Download docker desktop
And make sure to open Docker Desktop before the next step

STEP 5 :
Open your terminal in the src folder

STEP 6:
command : docker-compose up -d
The -d flag runs the Docker containers in daemon mode (in the background).

STEP 7:
command : docker ps
Check if all the containers are running

STEP 8 :
Open the PHP Terminal: Use the following command to open a terminal inside the PHP container:
command : docker exec -it php bash
Run Database Migrations: Inside the PHP terminal, run the following command to set up the database:
command : php artisan migrate
Alternatively you can open docker-desktop app and in containers find the php container and click on the three dots and select the option open terminal from there.

STEP 9 :
**IMPORTANT**

Fix :
If you get an error that vendor/xxxx not found
Run this command : docker-compose run --rm composer install

Configuration :
The Dockerfile is configured with the following default credentials:

Username: homestead
Database: homestead
Password: secret
You can modify these credentials in the Dockerfile as well as in the .env file according to your preferences.

STEP 10 :
To run the automated Unit tests run the command in php terminal 
command : php artisan test

You're All Set!
Now you can start using the web page and explore the features of Jisho.
