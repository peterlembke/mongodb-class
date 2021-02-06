# Manage MongoDb

There are several tools you can use to manage MongoDb.

## Install a software

If you run Windows, MacOS, Ubuntu, RedHat then you can download and install [MongoDb Compass](https://www.mongodb.com/try/download/compass) from the MongoDb homepage.

Note to Ubuntu users. I had trouble just clicking the .deb file and instead installed it by typing:
```
cd ~/Downloads
sudo apt install mongodb-compass_1.25.0_amd64.deb
```

MongoDb Compass connection string
``` 
mongodb://username:password@domainNameOrIpNumber:portNumber
```
The default port number is: 27017

## Use a web page

You can use the [Mongo-Express](https://github.com/mongo-express/mongo-express) software and run it in your browser.

I use Docker to run software. See how I do that in [mongodb-docker](mongodb-docker). 

## Write commands

[mongo-tools](https://docs.mongodb.com/database-tools/) is a collection of tools for administering MongoDB servers from the command line.

See how you can [use mongo-tools](mongodb-tools.md) and the [command line](command-line.md).

# License
This documentation is copyright (C) 2021 Peter Lembke.  
Permission is granted to copy, distribute and/or modify this document under the terms of the GNU Free Documentation License, Version 1.3 or any later version published by the Free Software Foundation; with no Invariant Sections, no Front-Cover Texts, and no Back-Cover Texts.  
You should have received a copy of the GNU Free Documentation License along with this documentation. If not, see [https://www.gnu.org/licenses/](https://www.gnu.org/licenses/).  SPDX-License-Identifier: GFDL-1.3-or-later  
