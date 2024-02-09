# StaticMaker

Host a Website on a Dynamic IP Network!
Host Your Website on your own Desktop without paying for a Static IP address!

Step 1
Set up a free account with Cloudflare. 
Cloudlfare is a proxy service that allows you to re-direct web traffic with the use of their API. 

Step 2
Install the StaticMaker script on the computer that will be hosting the website.
If your local ISP changes your IP address, the script will detect the change and send a code to Cloudlfare to change the destination IP address from the old IP address to the new one.
The script runs as a daemon in the background and needs very little if any adjusting.

    You need 1 instance of StaticMaker per Cloudflare account.
    Manage multiple Domains & Sub-Domains from the same Cloudflare account

Step 3 
Install Mysql and a web server like Apache2 or Nginx.  

Current Program built for Ubuntu 18.04, with easy to use front end web GUI

## INSTALLATION

Copy the StaticMaker files into your web server root
```
git clone https://github.com/fasbitX/StaticMaker /var/www/html
```

- Create database by importing db.sql file into your MySQL.
```
mysql -u YOUR_USERNAME -p  < db.sql

```
Located in /var/www/html/StaticMaker/public
- Copy public/config-sample.php to public/config.php and update db credentials.
```
cd
cd /var/www/html/StaticMaker/public
cp config-sample.php config.php
```

- Run following command to install required packages.

```
apt-get install composer
composer update
composer install
```

- Install Speed Test utility by running following commands

```
wget -O speedtest-cli https://raw.githubusercontent.com/sivel/speedtest-cli/master/speedtest.py
chmod +x speedtest-cli
sudo mv speedtest-cli /usr/local/bin/ 
```

## GUI Access

Access the Administrative GUI at  https://127.0.0.1/StaticMaker/public

## CRON CONFIGURATION

Set this cron job on your server. CRON frequency can be configured in the GUI.

```
* * * * * /usr/bin/php /path/to/your/package/cron.php
```

info@fasbit.com
