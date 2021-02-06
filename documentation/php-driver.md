# PHP extension MongoDb driver

The MongoDb driver is a PHP extension that you install and check if it is installed with phpinfo.

## Install in Docker

If you use Docker for your PHP project then you can do like this.
I use a container FROM ubuntu:focal

``` 
# PECL
RUN mkdir -p /tmp/pear/cache
RUN pecl channel-update pecl.php.net
RUN apt-get install -y libgpgme11-dev
RUN apt install -y php-pear

# MongoDb
RUN pecl install mongodb
COPY mongodb.ini "/etc/php/${PHP_VERSION}/mods-available/mongodb.ini"
```
You also need the mongodb.ini file that contain:
```
extension=mongodb.so 
```

## Check if installed
Use [phpinfo](phpinfo.md) to check if the extension is installed and active.

# License
This documentation is copyright (C) 2021 Peter Lembke.  
Permission is granted to copy, distribute and/or modify this document under the terms of the GNU Free Documentation License, Version 1.3 or any later version published by the Free Software Foundation; with no Invariant Sections, no Front-Cover Texts, and no Back-Cover Texts.  
You should have received a copy of the GNU Free Documentation License along with this documentation. If not, see [https://www.gnu.org/licenses/](https://www.gnu.org/licenses/).  SPDX-License-Identifier: GFDL-1.3-or-later  
