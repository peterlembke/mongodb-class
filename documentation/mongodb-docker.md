# Using docker

I use [Docker](https://www.docker.com/) to run software like MongoDb and MongoDb Express. You find a lot of docker containers at the [Docker Hub](https://hub.docker.com/).

In your docker-compose.yml you can add:

``` 
# https://hub.docker.com/_/mongo
  mongo:
    container_name: ${PROJECT_NAME}-mongo
    image: mongo
    restart: always
    expose:
      - "27017"
    environment:
      MONGO_INITDB_ROOT_USERNAME: root
      MONGO_INITDB_ROOT_PASSWORD: infohub

# https://github.com/mongo-express/mongo-express
  mongo-express:
    container_name: ${PROJECT_NAME}-mongo-admin
    image: mongo-express
    restart: always
    ports:
      - 8081:8081
    environment:
      ME_CONFIG_MONGODB_ADMINUSERNAME: root
      ME_CONFIG_MONGODB_ADMINPASSWORD: infohub
```

# Mongo express

You can reach the mongo express in your browser with
```
http://0.0.0.0:8081/
```

# MongoDb

Connect with a [manager software](mongodb-manage.md) or with the [mongo-tools](mongodb-tools.md).

# License
This documentation is copyright (C) 2021 Peter Lembke.  
Permission is granted to copy, distribute and/or modify this document under the terms of the GNU Free Documentation License, Version 1.3 or any later version published by the Free Software Foundation; with no Invariant Sections, no Front-Cover Texts, and no Back-Cover Texts.  
You should have received a copy of the GNU Free Documentation License along with this documentation. If not, see [https://www.gnu.org/licenses/](https://www.gnu.org/licenses/).  SPDX-License-Identifier: GFDL-1.3-or-later  
