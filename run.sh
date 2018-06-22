#!/usr/bin/env bash

NAME="test"
IMAGE_NAME="user-transactions"
NETWORK="docker-network"
IP_ADDRESS="172.18.0.1"

docker rm -f $NAME

docker run -d \
    --net="${NETWORK}" \
    --name=$NAME \
    --ip="$IP_ADDRESS" \
    $IMAGE_NAME