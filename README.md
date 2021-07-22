# Laravel + VueJs API CRUD 
<p>RESTfull API Document Service with Laravel and JWT. </p>

Quick Start
## Database Name 
document_service

## Install Dependencies
- `clone the project git clone https://github.com/giant41/document-service.git`
- `cd document-service`
- `composer install`
- `Run php artisan migrate`
- `Run php artisan serve` 

## User Authentication
### Create new User

End Point : POST -> http://document-service.test/api/auth/document-service/regiter
Request body : 
{
    "name" : "john Due",
    "email" : "john.due@gmail.com",
    "password" : "123456",
    "password_confirmation" : "123456"
}

Respon Body : 
{
    "message": "data aset created",
    "data": {
        "name": "john Due",
        "email": "john.due@gmail.com",
        "updated_at": "2021-07-22T00:32:40.000000Z",
        "created_at": "2021-07-22T00:32:40.000000Z",
        "id": 1
    },
    "token": "qwetuyrtyuiopasdfghjklzxcvbnm1234567890"
}

### Login User
End Point : POST -> http://document-service.test/api/auth/document-service/login
Request body : 
{
    "email" : "john.due@gmail.com",
    "password" : "123456"
}

Respon Body : 
{
    "access_token": "qweqwertyuiopasdfghjklzxcvbnm1234567890",
    "token_type": "bearer",
    "expires_in": 3600
}

### Logout User
End Point : POST -> http://document-service.test/api/auth/logout
Respon Body :
{
    "message": "Successfully logged out"
}