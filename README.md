# REST API for PHP 8

The end point of this sample is available at [api.trinketronix.net]("https://api.trinketronix.net")

## HTTP Requests

The REST API can handle basic request for the basic CRUD operations:

- **POST** for Create object.
- **GET** for Read object or objects.
- **PUT** for Update object.
- **DELETE** for Delete object.

---

## HTTP Headers
 The REST API need mandatory to receive specific Headers to operate correctly:

- **Authorization** for future authentication token implementation.
- **Content-Type** the value should be `"application/json"` to ensure the data interchanged is JSON.

---

## Methods in detail
All the methods requires the `Authorization: <authentication_token>` and `Content-Type: application/json` headers.

>[!CAUTION]
> In case that **Authorization Header** is not present the system will return an error `HTTP/1.1 401 Unauthorized` \
> In case that **Content-Type Header** is not present the system will return an error `HTTP/1.1 415 Unsupported Media Type`

---

### POST for Create
Sending a POST Request to the endpoint [api.trinketronix.net]("https://api.trinketronix.net")
additional to the required headers `POST` method also requires a JSON body.

Example:
```shell
curl -H 'Content-Type: application/json' -H 'Authorization: nbvfder567ujhsgfr567ujehgr56378udicjn' -d '{"key1": "value1","key2": "value2","key3": "value3"}' api.trinketronix.net
```
>[!TIP]
> In this particular example REST API, the POST request can receive any JSON object it will process it correctly.

---

### GET for Read
Sending a GET Request to the endpoint [api.trinketronix.net]("https://api.trinketronix.net")
there is 2 ways one to get all the objects and other to get just one object by id.

Example for object:
this example uses the path `/object/{id}` where the id could be any integer number as reference to the object you want.
```shell
curl -H 'Content-Type: application/json' -H 'Authorization: nbvfder567ujhsgfr567ujehgr56378udicjn' api.trinketronix.net/object/{id}
```

Example for objects:
no extra data here, it just returns all the objects.
```shell
curl -H 'Content-Type: application/json' -H 'Authorization: nbvfder567ujhsgfr567ujehgr56378udicjn' api.trinketronix.net/objects
```
---

### PUT for Update
Sending a PUT Request to the endpoint [api.trinketronix.net]("https://api.trinketronix.net")
is needed to get the object id to get just one object by id, also requires the JSON body with the information about to be update.  
```shell
curl -H 'Content-Type: application/json' -H 'Authorization: nbvfder567ujhsgfr567ujehgr56378udicjn' -d '{"key1": "value1","key2": "value2","key3": "value3"}' api.trinketronix.net/object/{id}
```
---

### DELETE for Delete
Sending a DELETE Request to the endpoint [api.trinketronix.net]("https://api.trinketronix.net")
it requires the id of the object to be deleted the id  to get all the objects and other to get just one object by id.

Example for object:
this example uses the path `/object/{id}` where the id could be any integer number as reference to the object you want.
```shell
curl -H 'Content-Type: application/json' -H 'Authorization: nbvfder567ujhsgfr567ujehgr56378udicjn' api.trinketronix.net/object/{id}
```

---
