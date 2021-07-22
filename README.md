# RESTfull API Document Service with Laravel and JWT    

## Install Dependencies
- clone the project `git clone https://github.com/giant41/document-service.git`
- cd document-service
- Run `composer install`
- create database `document_service`
- Run `php artisan migrate`
- Run `php artisan serve`

## User Authentication
### Create new User

- Endpoint : http://document-service.test/api/auth/document-service/register
- Request Method : POST
- Request body : 
```
{
    "name" : "john Due",
    "email" : "john.due@gmail.com",
    "password" : "123456",
    "password_confirmation" : "123456"
}
```

- Respon Body : 
```
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
```


### Login User
- Endpoint : http://document-service.test/api/auth/document-service/login
- Request Method : POST
- Request body : 
```
{
    "email" : "john.due@gmail.com",
    "password" : "123456"
}
```
- Respon Body :
```
{
    "access_token": "qweqwertyuiopasdfghjklzxcvbnm1234567890",
    "token_type": "bearer",
    "expires_in": 3600
}
```

### Logout User
- Endpoint : http://document-service.test/api/auth/logout
- Request Method : POST
- Use `Bearer Token` at Authorization when access this endpoint
- Respon Body :
```
{
    "message": "Successfully logged out"
}
```


## Manage Folder

### Show all folder and document
- Endpoint : http://document-service.test/api/auth/document-service
- Request Method : GET
- Use `Bearer Token` at Authorization when access this endpoint
- Respon Body :
```
{
    "error": false,
    "data": [
        {
            "id": "82b07a6f-0001-4403-8fd2-329ef0de045n",
            "name": "Document Pindah Ke Folder-A",
            "type": "document",
            "is_public": 1,
            "owner_id": 123,
            "share": "[1,23,4232,121]",
            "timestamp": 1605081795,
            "company_id": 23
        },
        {
            "id": "82b07a6f-60cc-4403-8fd2-329ef0de0d3c",
            "name": "Folder-C",
            "type": "folder",
            "is_public": 1,
            "owner_id": 1,
            "share": "",
            "timestamp": 16576232323,
            "company_id": 23
        },
        {
            "id": "82b07a6f-60cc-4403-8fd2-329ef0de0d3a",
            "name": "Folder-A",
            "type": "folder",
            "is_public": 1,
            "owner_id": 1,
            "share": "",
            "timestamp": 16576232323,
            "company_id": 23
        }
    ]
}
```


### Set/Create New Folder
- Endpoint : http://document-service.test/api/auth/document-service/folder
- Request Method : POST 
- Use `Bearer Token` at Authorization when access this endpoint
- Request Body :
```
    {
        "id": "82b07a6f-60cc-4403-8fd2-329ef0de0d3d",
        "name": "Folder-D", 
        "timestamp": 16576232323
    }
```
- Respon Body :
```
{
    "error": false,
    "message": "folder created",
    "data": {
        "id": "82b07a6f-60cc-4403-8fd2-329ef0de0d3d",
        "name": "Folder-D",
        "type": "folder",
        "content": [],
        "timestamp": 16576232323,
        "owner_id": 120,
        "company_id": 130
    }
}
``` 


### Delete Folder
- Endpoint : http://document-service.test/api/auth/document-service/folder
- Request Method : DELETE
- Use `Bearer Token` at Authorization when access this endpoint
- Request Body : use folder_id as body request
```
    {
        "id": "s2d80llw-7jta-01by-ca9v-exli0f1774vz"
    }
```
- Respon Body :
```
{
    "error": false,
    "message": "Success delete folder"
}
}
```


## Manage Document

### List File/Document Per Folder
- Endpoint : http://document-service.test/api/auth/document-service/folder/:folder_id  -> user folder_id as a key
- Request Method : GET 
- Use `Bearer Token` at Authorization when access this endpoint
- Respon Body :
```
{
    "error": false,
    "data": [
        {
            "id": 7,
            "name": "Document Pindah Ke Folder-A",
            "type": "document",
            "folder_id": "izmjxeri-udf1-9vye-gi2p-aavigwjey27p",
            "content": {
                "blocks": [
                    {
                        "type": "paragraph",
                        "text": "This is paragraph 1A"
                    },
                    {
                        "type": "paragraph",
                        "text": "This is paragraph 2A"
                    }
                ]
            },
            "timestamp": 1605081795,
            "owner_id": 123,
            "share": "[1,23,4232,121]"
        }
    ]
}
```


### Set (Create/Update) document
- Endpoint : http://document-service.test/api/auth/document-service/document
- Request Method : POST 
- Use `Bearer Token` at Authorization when access this endpoint
- Request Body : 
```
{
        "id" : "82b07a6f-60cc-4403-8fd2-329ef0de045s",
        "name" : "Document in Folder-C",
        "type" : "document",
        "folder_id" : "ipaykfia-zs93-pikt-8iji-41l6q998uj5o",
        "content" : {
            "blocks" : [
                {
                    "type" : "paragraph",
                    "text" : "This is paragraph Folder-C 1"
                },
                {
                    "type" : "paragraph",
                    "text" : "This is paragraph Folder-C 2"
                }
                ]
        }, 
        "timestamp" : 1605081795,
        "owner_id" : 130, 
        "share" : [1,23,4232,121], 
        "company_id" : 130
    }
```
- Respon body :
```
{
    "error": false,
    "message": "Success set document",
    "data": {
        "document": {
            "id": "82b07a6f-60cc-4403-8fd2-329ef0de045s",
            "name": "Document in Folder-C",
            "type": "document",
            "folder_id": "ipaykfia-zs93-pikt-8iji-41l6q998uj5o",
            "content": [
                {
                    "type": "paragraph",
                    "text": "This is paragraph Folder-C 1"
                },
                {
                    "type": "paragraph",
                    "text": "This is paragraph Folder-C 2"
                }
            ],
            "timestamp": 1605081795,
            "owner_id": 130,
            "company_id": [
                1,
                23,
                4232,
                121
            ]
        }
    }
}
```


### Get Detail Document
- Endpoint : http://document-service.test/api/auth/document-service/document/82b07a6f-60cc-4403-8fd2-329ef0de045s  -> :document_id
- Request Method : GET
- Use `Bearer Token` at Authorization when access this endpoint
- Respon ody :
```
{
    "error": false,
    "message": "Success get document",
    "data": {
        "id": 9,
        "name": "Document in Folder-C",
        "type": "document",
        "folder_id": "ipaykfia-zs93-pikt-8iji-41l6q998uj5o",
        "content": {
            "blocks": [
                {
                    "type": "paragraph",
                    "text": "This is paragraph Folder-C 1"
                },
                {
                    "type": "paragraph",
                    "text": "This is paragraph Folder-C 2"
                }
            ]
        },
        "timestamp": 1605081795,
        "owner_id": 130,
        "share": "[1,23,4232,121]"
    }
}
```


### Delete Document
- Endpoint : http://document-service.test/api/auth/document-service/document
- Request Method : DELETE
- Use `Bearer Token` at Authorization when access this endpoint
- Request Body:  user :document-id as body request
```
{
    "id": "82b07a6f-60cc-4403-8fd2-329ef0de045s"
}
```
- Response Body:
{
    "error": false,
    "message": "Success delete document"
}
