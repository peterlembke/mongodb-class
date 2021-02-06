# MongoDb tools

The [mongodb-tools](https://docs.mongodb.com/database-tools/) can be used to manage your mongodb servers.

## Installation
In Ubuntu you can install the tools like this:
```
sudo apt-get install mongo-tools  
```

## Connect to the MongoDb server

I use Docker and a script to get the IP number of the mongo db server.

```
dox container ip mongo 
```
Then use the IP number to connect to the mongo command line.
```
mongo --username root --password infohub --authenticationDatabase admin --host 172.19.0.2 --port 27017
```
The parameters after `mongo` can be used on all mongodb tools.

Read more about the mongo [command line](command-line.md).

## Server status with mongotop

Exchange mongo to mongotop from the example above.
You get information like:
``` 
                    ns    total    read    write    2021-02-06T07:46:42+01:00
    admin.system.roles      0ms     0ms      0ms                             
    admin.system.users      0ms     0ms      0ms                             
  admin.system.version      0ms     0ms      0ms                             
config.system.sessions      0ms     0ms      0ms                             
   config.transactions      0ms     0ms      0ms                             
  local.system.replset      0ms     0ms      0ms        
```

# License
This documentation is copyright (C) 2021 Peter Lembke.  
Permission is granted to copy, distribute and/or modify this document under the terms of the GNU Free Documentation License, Version 1.3 or any later version published by the Free Software Foundation; with no Invariant Sections, no Front-Cover Texts, and no Back-Cover Texts.  
You should have received a copy of the GNU Free Documentation License along with this documentation. If not, see [https://www.gnu.org/licenses/](https://www.gnu.org/licenses/).  SPDX-License-Identifier: GFDL-1.3-or-later  
