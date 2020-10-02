
# OAuth api

Technologies used: Laravel framework, Passport package

Make sure you have composer installed on your device

## How to run

- Rename .env.example file to .env
- Put your database credentials and change APP_URL to your path
- Run "php artisan key:generate"
- Run "php artisan migrate" in terminal
- Run "php artisan passport:install" in terminal, client password grant secret will be created
    - Alternatively you can run "php artisan passport:client --password" then you will need to change "PASSPORT_PERSONAL_ACCESS_GRANT_CLIENT_NAME" constant inside .env to your custom password grant client name

---

## Registration

```
http://your_route/public/api/register
```

##### HTTP Verb

```
POST
```

##### Data

Credentials (email, password, password_confirmation) as form-data

###### Example

```
KEY VALUE
-----------------------------
email - test@test.com
password - 123test
password_confirmation - 123test
```

This endpoint will register user and returns his access token and refresh token
####### Default access tokens expiration date is one week

---

## Login 

```
http://your_route/public/api/login
```

##### HTTP Verb

```
POST
```

##### Data

Credentials (email, password) as form-data

###### Example
```
KEY VALUE
-----------------------------
email - test@test.com
password - 123test
```

Endpoint will login user and return his access token and refresh token
####### Default access tokens expiration date is one week

---

### Refresh token

```
http://your_route/public/api/refresh
```
##### HTTP Verb

```
GET
```

##### Data

refresh-token in request header

###### Example
```
KEY VALUE
-----------------------------
refresh-token - ef502000732...
```

Returns new access and refresh tokens

---

### Logout

```
http://your_route/public/api/logout
```

##### HTTP Verb

```
GET
```

##### Data

Access token(Authorization) in request header

###### Example
```
KEY VALUE
-----------------------------
Authorization - Bearer tef502000732...
```

Revokes your access and refresh tokens, makes them invalid

---

### Currency rates

```
http://your_route/public/api/rates
```

##### HTTP Verb

```
GET
```

##### Data
Base as a parameter & access token(Authorization) in request header

###### Example
```
KEY VALUE
-----------------------------
base - EUR
```

```
KEY VALUE
-----------------------------
Authorization - Bearer tef502000732...
```

Shows currency rates based on base currency for (EUR, USD, GBP) only for authorized users
    

