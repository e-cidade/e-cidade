# Guia de instalação

Você pode instalar o e-Cidade utilizando Docker ou diretamente no seu servidor web, caso você deseje atualizar sua 
instalação siga os passos do [guia de atualização](UPGRADE.md).

- [Instalação em servidor web](#instalação-em-servidor-web)
- [Instalação utilizando Docker](#docker)
- [Primeiro acesso](#primeiro-acesso)

### Docker
...em breve ..


## Instalação em servidor web

Para instalar o projeto execute **todos os passos** abaixo conectado em seu servidor web. Este passo a passo serve para um servidor Ubuntu 22.04 LTS 



### 1. Postgresql

#### 1.1 Instalação

    sudo apt-get update
    sudo apt-get upgrade
    sudo apt-get install postgresql-12 postgresql-contrib-12
    sudo pg_dropcluster --stop 12 main


#### 1.2 Configurando locales

    sudo vim /usr/share/i18n/locales/pt_BR
    sudo localedef -i pt_BR -c -f ISO-8859-1 -A /usr/share/locale/locale.alias pt_BR
    sudo locale-gen pt_BR
    sudo dpkg-reconfigure locales
    export LC_ALL=pt_BR
    sudo echo LC_ALL=pt_BR >> /etc/environment

#### 1.3 Configurando cluster

    sudo pg_createcluster -e LATIN1 12 main
    sudo /etc/init.d/postgresql start
    sudo vim /etc/postgresql/12/main/pg_hba.conf
    sudo /etc/init.d/postgresql reload

#### 1.3 Configurando Postgres

Edit o arquivo postgresql.conf com os dados a abaixo

    sudo vim /etc/postgresql/12/main/postgresql.conf


    listen_addresses = '*'
    max_connections = 20
    bytea_output = 'escape'
    max_locks_per_transaction = 256
    escape_string_warning = off
    standard_conforming_strings = off
    sudo /etc/init.d/postgresql restart

#### 1.4 Configurando Postgres
Cria banco de dados e usuários

    psql -U postgres -h localhost template1 -c "create role ecidade with superuser login password 'ecidade'"
    psql -U postgres -h localhost template1 -c "create role dbportal with superuser login password 'dbportal'"
    psql -U postgres -h localhost template1 -c "create role dbseller with login password 'dbseller'"
    psql -U postgres -h localhost template1 -c "create role contass with login password 'cts36162'"
    psql -U postgres -h localhost template1 -c "create role plugin with login password 'plugin'"
    psql -U postgres -h localhost template1 -c "create role usersrole with login password 'usersrole'"
    createdb -U dbportal e-cidade
    psql -U dbportal e-cidade -f dump_zerado.sql


### 2. Apache

    sudo apt-get update
    sudo apt-get upgrade
    sudo apt-get install apache2
    sudo vim /etc/apache2/apache2.conf
    sudo vim /etc/apache2/conf-available/charset.conf
    sudo a2enmod rewrite
    sudo /etc/init.d/apache2 restart
    sudo mkdir /var/www/tmp
    sudo chown -R www-data.www-data /var/www/tmp
    sudo chmod -R 777 /var/www/tmp
    sudo vim /etc/apache2/sites-available/000-default.conf
    sudo a2enmod proxy_fcgi setenvif rewrite
    sudo systemctl restart apache2


### 3. Instale o PHP

#### 3.1 Instale PHP

    sudo apt-get update
    sudo apt-get upgrade
    sudo apt-get install php7.4 php7.4-gd php7.4-pgsql php7.4-cli php7.4-bcmath php7.4-zip php7.4-imagick php7.4-curl php7.4-mbstring php7.4-xml curl libcurl4 libapache2-mod-php7.4 zip
    sudo mkdir /var/www/log
    sudo chown -R www-data.www-data /var/www/log
    sudo chown root.www-data /var/lib/php/

#### 3.2 Configurando PHP

Edit o arquivo e altere os parametros conforme o modelo

    sudo vim /etc/php/7.4/apache2/php.ini
     
    short_open_tag = On
    register_argc_argv = on
    post_max_size = 64M
    upload_max_filesize = 64M
    default_socket_timeout = 60000
    max_execution_time = 60000
    max_input_time = 60000
    memory_limit = 512M
    allow_call_time_pass_reference = on
    error_reporting = E_ALL & ~E_NOTICE
    display_errors = off
    log_errors = on
    error_log = /var/www/log/php-scripts.log
    session.gc_maxlifetime = 7200

#### 3.3 Composer

    cd ~
    curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
    sudo php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer


### 4. Instale o Libreoffice

    sudo apt-get install libreoffice-writer python3-uno openjdk-17-jre-headless
    systemctl status rc-local
    sudo vim /lib/systemd/system/rc-local.service
    printf '%s\n' '#!/bin/bash' 'exit 0' | sudo tee -a /etc/rc.local
    sudo chmod +x /etc/rc.local
    sudo vim /etc/rc.local
    sudo systemctl enable rc-local
    sudo systemctl start rc-local.service
    sudo systemctl status rc-local


### 5. Instale o e-Cidade

    cd /var/www
    sudo mkdir /var/www/e-cidade
    sudo chown -R contass.www-data /var/www/e-cidade
    sudo chmod -R 775 /var/www/e-cidade
    git clone git@github.com:e-cidade/e-cidade.git e-cidade
    sudo chown -R www-data:www-data e-cidade/
    sudo chmod -R 775 e-cidade/
    sudo chmod -R 777 e-cidade/tmp/
    cd /var/www/e-cidade
    composer install
    cp -a /var/www/e-cidade/imagens/files.proper /var/www/e-cidade/imagens/files
    cp .env.example .env





