PDNS-GUI
========

This is a fork from http://code.google.com/p/pdns-gui/

Code was not updated since early 2011, so I decided to fork it and commit bugfixes on it.

Source code was released under GNU GPL v2, so this repository will use the same license model.

Special thanks to [Olivier Doucet](https://github.com/odoucet) to fork and bugfixs here.

Installation on Ubuntu 12.04 / 12.10
--------------

If you don't have a MySQL, install it.

```bash
sudo apt-get install mysql-server mysql-client
```

Install PowerDNS and a mysql backend.

```bash
sudo apt-get install pdns-server pdns-backend-mysql
```

Create a database and user for PowerDNS.

Edit a PowerDNS conf to access a MySQL database.

```bash
sudo vim /etc/powerdns/pdns.d/pdns.local.gmysql
```

Clone this repository or download this in your server.

Change de owner and group to your web user.

Exampĺe: chown www-data.www-data pdns-gui -Rf

Into batch folder, add execution permission to install.sh. 

```bash
sudo chmod +x install.sh 
```

Run a install.sh script and insert parameters.

```bash
./install.sh
```

After this, you need to edit a conf in your web server.

This gui needs to run a pdns-control with sudo (/usr/bin/pdns_control), to this we add the pdns-control's executable to run as sudo without password.

Create a www-data file to sudoers and add a line and save.

```bash
sudo vim /etc/sudoers.d/www-data
```
```bash
www-data ALL=NOPASSWD: /usr/bin/pdns_control
```

Run in your web browser with a IP or vhost and be happy.
