#!/usr/bin/env sh
# Usage: wait-for.sh host port
host="$1"
port="$2"

echo "⏳  Waiting for $host:$port…"
while ! nc -z "$host" "$port" >/dev/null 2>&1; do
  sleep 1
done

echo "✅  $host:$port is up"
