LANager
=======

LANager is a web application designed to make [LAN parties](https://en.wikipedia.org/wiki/Lan_party)
more enjoyable for attendees and organisers alike.

## Features

### Encourage socialising & participation

* **Games being played** - by your LAN's attendees, updated every minute from Steam
* **Events timetable** - so your attendees know what's on at the LAN
* **Achievements** - created by you and awarded to attendees 
* **Attendee profiles** - with a link to their Steam profile, and their gameplay history at the LAN
* **Games in common** - when viewing another attendee's profile
* **Games recently played** - showing the top games most recently played by attendees at the LAN

### Broadcast useful information 
* **Live Dashboard** - showing games attendees are currently playing, and current and upcoming events, for TVs/projectors around your venue 
* **Guides** - written by you, to help attendees learn about things like venue facilities, rules, or gameplay guides
* **Links** - to other websites, such as your organisation's website, or a game statistics page

## Requirements

* A server running Ubuntu Server 18.04 (_with shell access - basic web hosting is not supported_)
* A [Steam API Key](http://steamcommunity.com/dev/apikey)
* A [Google API Key](https://cloud.google.com/maps-platform/?apis=maps) enabled for the Maps Embed API
* Internet access

While it's possible to run LANager on a server at your venue and make it only accessible internally, we recommend you cloud host. This allows you to easily update the site outside of your events (for example, to prepare for your next LAN), and allows your attendees to use it to find information ahead of the event.

## Installation

1. Install required packages:

    ```bash
    sudo add-apt-repository universe
    sudo apt update
    sudo apt install php7.2-common php7.2-fpm php7.2-mysql php7.2-mbstring php7.2-bcmath php7.2-xml php7.2-zip
    sudo apt install zip composer mysql-server nginx
    ```

2. Create a Nginx site configuration:

    ```bash
    sudo nano /etc/nginx/sites-available/lanager
    ```

    ```
    server {
            listen 80;
    
            root /var/www/lanager/public;
    
            index index.html index.htm index.php;
    
            # Change to your domain:
            server_name lanager.example.com;
    
            location / {
                try_files $uri $uri/ /index.php?$query_string;
            }
    
            location ~ \.php$ {
                    include snippets/fastcgi-php.conf;
    
                    fastcgi_pass unix:/var/run/php/php7.2-fpm.sock;
            }
            client_max_body_size 20M;
    }
    ```

3. Increase PHP's upload file size limit:

    ```bash
    sudo nano /etc/php/7.2/fpm/php.ini
    ```

    Find and update the lines:

    ```
    upload_max_filesize = 20M
    post_max_size = 8M
    ```

    Restart PHP
    ```bash
    sudo systemctl restart php7.2-fpm
    ```

4. Enable the site:

    ```bash
    rm /etc/nginx/sites-enabled/default
    ln -s /etc/nginx/sites-available/lanager /etc/nginx/sites-enabled/lanager
    nginx -s reload
    ```

5. Configure MySQL:

    ```bash
    mysql
    ```
    
    ```
    CREATE DATABASE lanager;
    CREATE USER 'lanager'@'%' IDENTIFIED BY 'YOUR-PASSWORD-HERE';
    GRANT ALL PRIVILEGES ON lanager.* TO 'lanager'@'%';
    FLUSH PRIVILEGES;
    QUIT;
    ```

6. Clone a copy of LANager:

    ```
    git clone https://github.com/zeropingheroes/lanager /var/www/lanager/
    ``` 

7. Grant permissions:

    ```
    chgrp www-data -R /var/www/lanager/
    chmod 777 -R /var/www/lanager/storage
    ```

8. Install LANager's dependencies:

    ```
    composer install --no-dev --working-dir=/var/www/lanager
    ```

9. Configure LANager:
    
    ```bash
    cd /var/www/lanager/ && cp .env.example .env && nano .env
    ```
    
    - `APP_URL` - The full URL
    - `APP_TIMEZONE` - Your [timezone](https://en.wikipedia.org/wiki/List_of_tz_database_time_zones#List)
    - `STEAM_API_KEY` - Your [Steam API Key](http://steamcommunity.com/dev/apikey)
    - `GOOGLE_API_KEY` - Your [Google API Key](https://console.cloud.google.com/apis/)
    - `DB_PASSWORD` - The password you chose for the `lanager` MySQL user above

10. Run first-time setup commands:

    ```bash
    php artisan key:generate
    php artisan migrate:fresh
    php artisan db:seed
    php artisan storage:link
    php artisan lanager:import-steam-apps-csv
    php artisan lanager:update-steam-apps
    php artisan lanager:update-steam-apps-metadata
    php artisan lanager:update-origin-games
    php artisan config:cache
    ```

11. Visit the app URL to check that the installation was successful.

12. Enable the scheduled commands:

    ```bash
    crontab -e
    ```

    ```
    * * * * * php /var/www/lanager/artisan schedule:run >> /dev/null 2>&1
    ```

13. Disable debugging, set the site's environment to *production*, and enable MySQL logging:

    ```bash
    nano /var/www/lanager/.env
    ```

    ```
    APP_DEBUG=false
    APP_ENV=production
    LOG_CHANNEL=stack
    ```

## Troubleshooting

- Enable debugging in your `.env` file.

- [Create an issue](https://github.com/zeropingheroes/lanager/issues) with the error message(s) you see.

- If you don't see an error message, in your `.env` file:
    - set `LOG_CHANNEL=stack` 
    - check for errors in `/var/www/lanager/storage/logs/laravel.log`
    
## Getting started

To set up the LANager for your next LAN party, you need to create a LAN page, then add Events (such as scheduled games or lunch breaks) and Guides (such as game rules) to that LAN page.

### Become a Super Admin

The LANager assigns the "Super Admin" role to the first account that logs into it, so make sure you log in as soon as you have completed installation.

Super Admins can perform any action on the site, including assigning roles to other users. 

### Create your LAN page

The first thing you need to do is to create a LAN page in the LANager. This process is the same whether your LAN party is one day or multiple days. You need to do this before you can create an event schedule, publish any guides or award achievements.

Log into the LANanger, and go to ⚙ > **LANs**, then select the **+** button to go to the LAN page creation form. Enter your LAN's details, and add a description with [markdown formatting](https://en.wikipedia.org/wiki/Markdown#Example) if you want to.

The LANager automatically adds anyone who signs into the LANager during the LAN party to the LAN's list of attendees. For each attendee it displays the current LAN page, which contains the LAN's timetabled events, guides and attendees list.

### Create Events & Guides

Once you have a LAN page for your LAN party, you can create Events and Guides to help attendees enjoy your party.

* **Events** are a useful way to timetable game tournaments, highlight big game sessions, schedule breaks and mealtimes, and let people know when it's time to go home
* **Guides** are a useful way to provide people with the rules and download links to the games you're playing, to let them know where the nearest shops and restaurants are, to provide a code of conduct for your event, and to communicate any other information you think your guests might need.

From the LAN page, click the **+** button next to the **Events** and **Guides** headings to go to their creation forms.

#### Using links

You can use markdown-formatted links in LANs, guides and events. For example, you can write a single guide, and insert a link to it on several event pages:

    If you need any help, please contact one of our [tournament staff](/lans/4/guides/3) 

Where possible, it's good practice to use relative links as demonstrated above, so that if you change your domain, the links continue to work.

#### Using images

You can upload images to LAN pages, Events and Guides. To do this, follow these steps:

* Below the "description" text box, select "upload images".
* Upload the image you want to use.
* Next to the image, select ⚙ > **Copy Markdown** 
* Paste the markdown into the guide, event or LAN's **description** field, in the location you want it to appear in the text.

### Display the Live Dashboard

The Live Dashboard make it easy for your attendees to see at a glance which games are being played, events that are in progress, and any that are upcoming.

To display the Live Dashboard, go to ⚙ > **Dashboard**. Alternatively, if you aren't logged in, visit the following URL:

`http://(your LANager install's address)/dashboard`

It's useful to display this on a TV or projector that everyone can see.

### Create and award Achievements

Click ⚙ > **Achievements** and then click the **+** button to create achievements that you can award to users. 

To award an Achievement to an attendee, go to the navigation bar and select **Achievements**. This opens the list of Achievements you have awarded to attendees of the current LAN. At the bottom of the page, choose the Achievement and the
attendee to award it to, then select **Award**.

### Customise the navigation bar

Click ⚙ > **Navigation** to customise the links shown on the navigation bar. You can link to pages on the LANager or to third-party sites, organise the links into drop-down menus, and choose the order that the links appear in the navbar or dropdown.

## Upgrading from v0.5.x on Ubuntu 14.04

1. Back up the database and site files:

    ```bash
    mysqldump -u lanager -p --extended-insert=FALSE lanager > ~/lanager-backup.sql
    tar -zcvf ~/lanager-backup.tar.gz /var/www/lanager
    ```

2. Delete any local changes and get the latest version from GitHub:

    ```bash
    cd /var/www/lanager
    git checkout .
    git pull
    ```

3. Remove Apache and PHP5:

    ```bash
    sudo apt remove php5-common php5-cli php5-mcrypt php5-curl php5-mysql libapache2-mod-php5 apache2
    ```
    
4. Install NGINX, PHP7.2 and MySQL 5.7:

    ```bash
    cd ~
    sudo add-apt-repository ppa:ondrej/php
    sudo add-apt-repository ppa:ondrej/nginx-mainline
    wget http://dev.mysql.com/get/mysql-apt-config_0.6.0-1_all.deb
    sudo dpkg -i mysql-apt-config_0.6.0-1_all.deb
    rm -f mysql-apt-config_0.6.0-1_all.deb
    sudo apt update
    sudo apt install php7.2-common php7.2-fpm php7.2-mysql php7.2-mbstring php7.2-bcmath php7.2-xml php7.2-zip zip nginx mysql-server
    mysql_upgrade -u root -p
    ```

5. From the normal [installation instructions](#installation), follow steps 2, 3, 4, 7, 8 and 9.

6. Run the following commands:

    ```bash
    cd /varr/www/lanager
    php artisan key:generate
    php artisan storage:link
    php artisan lanager:upgrade-database
    ```
    
6. From the normal [installation instructions](#installation), follow steps 11, 12 and 13.

## Development

### Requirements

- [Vagrant](https://www.vagrantup.com/downloads.html)
- [VirtualBox](https://www.virtualbox.org/wiki/Downloads)
- [Composer](https://getcomposer.org/)

### Installation:

1. `git clone https://github.com/zeropingheroes/lanager && cd lanager`
2. `cp .env.example .env`
3. `nano .env`
   1. `STEAM_API_KEY` - Enter your [Steam API Key](http://steamcommunity.com/dev/apikey)
   2. `DB_USERNAME=homestead`
4. `composer install`
5. `vagrant plugin install vagrant-vbguest vagrant-winnfsd`
6. `vagrant up`

When you complete these steps, your development environment should be available at [lanager.localhost:8000](http://lanager.localhost:8000/).

## Feedback & Contributions

* Found a bug? Got a great feature idea? Post it to the [issue tracker](https://github.com/zeropingheroes/lanager/issues)!
* Want to contribute?
    * [Fork the project](https://github.com/zeropingheroes/lanager/fork) and add the features you want to see.
    * Work on new features / bug fixes in the [issue tracker](https://github.com/zeropingheroes/lanager/issues).
    * If you're really hardcore, request commit access.

If you want to support the project in a non-technical way, we'd love it if you donated to us:

[![Flattr this git repo](http://api.flattr.com/button/flattr-badge-large.png)](https://flattr.com/submit/auto?user_id=zeropingheroes&url=https%3A%2F%2Fgithub.com%2Fzeropingheroes%2Flanager)

Enjoy using the LANager!
