# Slim 4 RPS API

An API that implements the Rock-Paper-Scissors game written in Slim 4.

See [Open API Specification](https://akrabat.com/stuff/rps.html).


# Useful curl commands

Create a game:

    curl -i -H "Accept: application/json" -H "Content-Type: application/json" \
      http://localhost:8888/games -d '{"player1": "Rob", "player2": "Jon"}'

List games:

    curl -i -H "Accept: application/json" http://localhost:8888/games
