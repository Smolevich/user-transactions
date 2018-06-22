### Service for work with user transactions

## Table of Contents
 - [Requirenments](#requirenments)
 - [Installation](#installation)
 - Operations
    - [Enrollment](#enrollment)
    - [Write-off](#write-off)
    - [Transfer](#transfer)
    - [Accept](#accept)
    - [Block](#block)
 - [Workers](#workers)

## Requirenments
    - Laravel framework
    - Redis
    - Docker

## Installation
1) If docker is not installed, install docker [Docker Docs](#https://docs.docker.com/install/)

2) Build image `docker build -t user-transactions .` in project directory

3) Run container
        ```
            docker run -d
            --name={NAME_CONTAINER}
            -e REDIS_HOST={REDIS_HOST}
            -e REDIS_PORT={REDIS_PORT}
            user-transactions
        ```
4) Enter in container `docker run -it {$NAME_CONTAINER}` sh
5) Install vendors `composer install`
6) Create test-users `php artisan create:users`


## Operations

### Enrollment

### Enrollment with pre blocked

Format data for push into queue:

```
    {
        "type": "enrollment",
        "user_id": <INT>
        "extra_data": {
            "sum": <INT>,
            "pre-blocked": true
        }
    }
```

For example,  run command in container `php artisan enrollment:push {user_id} {sum} --pre-blocked`

### Enrollment without pre blocked

Format data for push into queue:

```
    {
        "type": "enrollment",
        "user_id": <INT>
        "extra_data": {
            "sum": <INT>
        }
    }
```

For example,  run command in container `php artisan enrollment:push {user_id} {sum}`

### Write-off

### Write-off with pre blocked
Format data for push into queue:

```
    {
        "type": "write-off",
        "user_id": <INT>
        "extra_data": {
            "sum": <INT>,
            "pre-blocked": true
        }
    }
```
For example,  run command in container `php artisan writeoff:push {user_id} {sum}`

### Write-off without pre blocked
  Format data for push into queue:

```
      {
          "type": "write-off",
          "user_id": <INT>
          "extra_data": {
              "sum": <INT>
          }
      }
```

### Transfer

Format data for push into queue:
```
    {
        "type": "transfer",
        "target_user_id": <INT>,
        "user_id": <INT>
        "extra_data": {
            "sum": <INT>,
            "pre-blocked": true
        }
    }
```
For example,  run command in container `php artisan transfer:push {user_id} {target_user_id} {sum}`

### Accept
Format data for push into queue:
```
    {
        "type": "accept",
        "operaion_id": <STRING>
        "user_id": <INT>
        "extra_data": {
            "sum": <INT>,
        }
    }
```
For example,  run command in container `php artisan accept:push {operation_id}`

### Block

```
    {
        "type": "block",
        "operaion_id": <STRING>
        "user_id": <INT>
        "extra_data": {
            "sum": <INT>,
        }
    }
```

For example,  run command in container `php artisan block:push {operation_id}`

## Workers
Count of workes set in file `deployment/worker.conf` and rebuild docker image

