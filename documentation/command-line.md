# Command line

The mongo command line can be used to add users, list databases, create things. You just have to know what to write.

I think the documentation is [Command line Interface CLI](https://docs.mongodb.com/mongocli/master/) but I am not sure.

## Connect to the MongoDb server

I use Docker and a script to get the IP number of the mongo db server.

```
dox container ip mongo 
```
Then use the IP number to connect to the mongo command line.
```
mongo --username root --password infohub --authenticationDatabase admin --host 172.19.0.2 --port 27017
```

## Create user

```
db.createUser({
    user:"admin",
    pwd:"infohub",
    roles:[
        {
        role:"readWrite",
        db:"local"
        }
    ],
    mechanisms:[
        "SCRAM-SHA-1"
    ]
})
```

# License
This documentation is copyright (C) 2021 Peter Lembke.  
Permission is granted to copy, distribute and/or modify this document under the terms of the GNU Free Documentation License, Version 1.3 or any later version published by the Free Software Foundation; with no Invariant Sections, no Front-Cover Texts, and no Back-Cover Texts.  
You should have received a copy of the GNU Free Documentation License along with this documentation. If not, see [https://www.gnu.org/licenses/](https://www.gnu.org/licenses/).  SPDX-License-Identifier: GFDL-1.3-or-later  
