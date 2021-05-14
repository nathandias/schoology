## How to run this demo

`sudo chown -R www-data:www-data db/ # fix permissions on sqlite database`

`docker-compose up -d`

Optional:

`sudo rm db/db.sqlite # start the autocompletions table from scratch`
## autocomplete demo

Visit:

http://localhost/

## Backend API endpoints

### GET http://localhost/terms  READ

Returns list of unique autocompletion terms as JSON like:

{
    "terms" : ["Foo", "Bar", "string containing spaces"]
}

### GET http://localhost/term/{int:id} READ

Returns a single autocompletion term specified by id as JSON like:

{ "id" : 1,
  "term" : "a string"
}

### POST http://localhost/term CREATE

Adds a term to the autocompletion table and returns the id of the newly created row:

Request:

{ "term" : "the string to add" }

Response:

{ "id" : 35 }
