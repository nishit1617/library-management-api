#!/usr/bin/env bash
# Wait for TCP host:port to be available
# Usage: ./wait-for-it.sh host:port -- command args

HOSTPORT=$1
shift
CMD="$@"

HOST=$(echo $HOSTPORT | cut -d: -f1)
PORT=$(echo $HOSTPORT | cut -d: -f2)

echo "Waiting for $HOST:$PORT..."

while ! (echo > /dev/tcp/$HOST/$PORT) 2>/dev/null; do
  sleep 1
done

echo "$HOST:$PORT is available, executing command..."
exec $CMD
