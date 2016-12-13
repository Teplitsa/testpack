#!/usr/bin/env bash

HOST=$1

: ${HOST:="nlnew.dev"}

time ./upgrade.sh $HOST