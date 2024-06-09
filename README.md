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

You need npm installed.

1. Install Spectral & Redocly CLI:
   
        npm install -g @stoplight/spectral-cli
        npm install -g @redocly/cli

2. Validate:

        make check


## Build docs

To create a static HTML file, `doc/rps.html`

   make docs-build


To serve the doc so that it refreshes automatically when the spec file is edited:

   make docs-preview

and then view at http://127.0.0.1:8080
