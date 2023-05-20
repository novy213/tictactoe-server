# Database specyfication

# Api doc
## Api url
```
/tictactoe
```
## 1.1 Login
```
POST /tictactoe
```
### Params:
```
(null)
```
### Body:
```
{
  "login": "test",
  "password": "test"
}
```
### Response: 
```
{
  "error": false,
  "message": null,
  "token": "50x9v0uqxvLsBctrX1brKOL1TRhw5oDt",
  "userId": 11
}
```
## 1.2 Logout
```
DELETE /tictactoe
```
### Params:
```
(null)
```
### Body:
```
(null)
```
### Response: 
```
{
  "error":false,
  "message": null
}
```
## 1.3 Register
```
POST /tictactoe/register
```
### Params:
```
(null)
```
### Body:
```
{
  "login": "admin",
  "password": "admin",
  "name": "jan",
  "last_name: "kowalski"
}
```
### Response: 
```
{
  "error":false,
  "message": null
}
```
## 2.1 Get game info
```
GET /tictactoe/{game_id}
```
### Params:
```
game_id - unique id of game
```
### Body:
```
(null)
```
### Response: 
```
{
  "error": false,
  "message": null,
  "game":
    {
      "id": 1,
      "name": "gra1",
      "host": 2,
      "password": false,
      "invited_plater": 3
    }
}
```
## 2.2 Get info about player
```
GET /tictactoe/{user_id}
```
### Params:
```
user_id - unique id of user
```
### Body:
```
(null)
```
### Response: 
```
{
  "error": false,
  "message": null,
  "user":[
    {
      "id": 1,
      "name": "jan",
      "last_name": "kowalski"
    }
  ]
}
```
## 2.3 Start game
```
PUT /tictactoe/{game_id}
```
### Params:
```
game_id - unique id of game
```
### Body:
```
(null)
```
### Response: 
```
{
  "error": false,
  "message": null,
  "users":[
    {
      "id": 1,
      "name": "gra1",
      "host": 2,
      "enemy": 3,
      "start": 2     
    }
  ]
}
```
## 3.1 Send message to user
```
POST /chat/message/{user_to}
```
### Params:
```
user_to - unique id of the user
```
### Body:
```
{
  "message": "testowa wiadomosc",
  "user_from": 1
}
```
### Response: 
```
{
  "error": false,
  "message": null
}
```
## 3.2 Recive message from user
```
GET /chat/message/{user_from}
```
### Params:
```
(null)
```
### Body:
```
(null)
```
### Response: 
```
{
  "error": false,
  "message": null,
  "message":[
    {
      "id": 1,
      "message": "testowa wiadomosc"
    },
    {
      "id": 2,
      "message": "testowa wiadomosc 2"
    }
  ]
}
```
## 3.2 Recive conversation messages
```
GET /chat/conversation/{user_id}
```
### Params:
```
user_id - unique id of selected user that we are chatting with
```
### Body:
```
(null)
```
### Response: 
```
{
  "error": false,
  "message": null,
  "message":[
    {
      "id": 1,
      "message": "testowa wiadomosc",
      "from": "adam"
    },
    {
      "id": 2,
      "message": "testowa wiadomosc 2",
      "from": "jan"
    }
  ]
}
```
