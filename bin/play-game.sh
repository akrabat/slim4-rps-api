#!/usr/bin/env bash

set -eo pipefail

# Display a usage message with the -h flag
if [ "$1" == "-h" ]; then
  echo "Usage: $(basename "$0") [player1 [player2] [move1] [move2]"
  echo
  echo "Defaults:"
  echo "  player1: Nick"
  echo "  player2: Sadie"
  echo "  move1: paper"
  echo "  move2: scissors"
  echo
  echo "Set RPS_URL to change the URL from http://localhost:8888"
  exit 1
fi

player1=${1:-Nick}
player2=${2:-Sadie}
move1=${3:-rock}
move2=${4:-scissors}
url=${RPS_URL:-http://localhost:8888}

# Run curl, putting the HTTP status code in $status_code and the body into $body
# Based on https://stackoverflow.com/a/52832805/23060
curlwithcode() {
  local code=0
  local tmpfile; tmpfile=$(mktemp /tmp/curlwithcode.XXXXXX)

  status_code=$(curl -s -w "%{http_code}" -o >(cat >"$tmpfile") "$@") || code="$?"

  body="$(cat "$tmpfile")"
  rm -f "tmpfile"

  return $code
}

# create game
echo "1. Create game between $player1 and $player2"
echo "   POST to /games, with data: "'{"player1": "'"$player1"'", "player2": "'"$player2"'"}'
echo "       curl -s -H 'Accept: application/hal+json' -H 'Content-Type: application/json' '$url/games' -d '{\"player1\": \"$player1\", \"player2\": \"$player2\"}'"
curlwithcode -H "Accept: application/hal+json" -H "Content-Type: application/json" "$url/games" -d '{"player1": "'"$player1"'", "player2": "'"$player2"'"}'
echo "   Response: $status_code:"
echo "$body" | pr -to 6

# extract next move url from JSON body
move1_url=$(echo "$body" | jq -r '._links.makeNextMove.href')
move1_url="${move1_url/http:\/\/rps.example/$url}"

# Make player 1's move
echo ""
echo "2. Make $player1's move: $move1"
echo "   POST to /games/{game_id}, with data: "'{"player": "'"$player1"'", "move": "'"$move1"'"}'
echo "       curl -s -H \"Accept: application/hal+json\" -H \"Content-Type: application/json\" -d '{\"player\": \"$player1\", \"move\": \"$move1\"}' \"$move1_url\""
curlwithcode -H "Accept: application/hal+json" -H "Content-Type: application/json" -d '{"player": "'"$player1"'", "move": "'"$move1"'"}' "$move1_url"
echo "   Response: $status_code:"
echo "$body" | pr -to 6


# extract next move url from JSON body
move2_url=$(echo "$body" | jq -r '._links.makeNextMove.href')
move2_url="${move1_url/http:\/\/rps.example/$url}"

# Make player 2's move
echo ""
echo "3. Make $player2's move: $move2"
echo "   POST to /games/{game_id}, with data: "'{"player": "'"$player2"'", "move": "'"$move2"'"}'
echo "       curl -s -H \"Accept: application/hal+json\" -H \"Content-Type: application/json\" -d '{\"player\": \"$player2\", \"move\": \"$move2\"}' \"$move2_url\""
curlwithcode -H "Accept: application/hal+json" -H "Content-Type: application/json" -d '{"player": "'"$player2"'", "move": "'"$move2"'"}' "$move2_url"
echo "   Response: $status_code:"
echo "$body" | pr -to 6


result=$(echo "$body" | jq -r '.result')
echo ""
echo "Result of game: $result"
