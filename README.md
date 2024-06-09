# Slim 4 RPS API

An API that implements the Rock-Paper-Scissors game written in Slim 4.

See [Open API Specification](https://akrabat.com/stuff/rps.html).

## Running the apps:

1. Run composer

       $ composer install

2. Run the migrations:

       $ touch db/rps.db
       $ vendor/bin/doctrine-migrations migrations:migrate -n

3. Run the API

       $ php -d html_errors=0 -S 0.0.0.0:8888 -t public/

4. Test that it works using

       $ bin/play-game.sh

5. Reset the database

       $ composer reset-database

## Useful curl commands

Create a game:

    curl -i -H "Accept: application/json" -H "Content-Type: application/json" \
      http://localhost:8888/games -d '{"player1": "Rob", "player2": "Jon"}'

List games:

    curl -i -H "Accept: application/json" http://localhost:8888/games

Make a move:

    curl -i -H "Accept: application/json" -H "Content-Type: application/json" \
      -d '{"player": "Rob", "move": "rock"}' http://localhost:8888/games/f548aae6-3f4f-4c7f-a5fc-c0c1099411f7/moves


## Example

![Screenshot of Terminal showing curl playing the RPA game ](https://raw.githubusercontent.com/akrabat/slim4-rps-api/master/doc/slim4-api-rps-example.png)


## Validating the OpenAPI Spec

1. Install Spectral:
   
        npm install -g @stoplight/spectral-cli

2. Validate:

        cd doc
        spectral lint rps-openapi.yaml

