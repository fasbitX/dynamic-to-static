# StaticMaker

Host a Website on a Dynamic IP Network!
Host Your Website on your own Desktop without paying for a Static IP address!

Step 1
Set up a free account with Cloudflare. 
Cloudlfare is a proxy service that allows you to re-direct web traffic with the use of their API. 

Step 2
Install the script on the computer that will be hosting the website.
If your local ISP changes your IP address, the script will detect the change and send a code to Cloudlfare to change the destination IP address from the old IP address to the new one.
The script runs as a daemon in the background and needs very little if any adjusting.

    • You need 1 instance of StaticMaker per Cloudflare account.
    • Manage multiple Domains & Sub-Domains from the same Cloudflare account

Proxy Services
    • If you know of other free proxy services with API access, shoot us an email and we will add them to the option list.

Current Program built for Ubuntu 18.04

Windows installer: coming soon.

Dependencies


Installation


Run


## INSTALLATION

- Create database by importing ipchanger.sql file into your MySQL.

- Copy public/config-sample.json to public/config.json and add required credentials.

- Run following command to install required packages.

```
    composer install
```

## GUI Access

Access the Administrative GUI at  https://127.0.0.1/StaticMaker/public

## COMMAND EXAMPLE

```
/usr/bin/php /path/to/your/package/script.php
```


info@fasbit.com
