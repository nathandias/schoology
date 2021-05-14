## How to run this app
`docker-compose up -d

## autocomplete demo

Visit:

http://localhost/

## Backend API endpoints

### GET http://localhost/terms  READ

Returns list of unique autocomplete terms as JSON like:

{ "terms" : "Foo", "Bar", "string containing spaces" }

### GET http://localhost/term/{int:id} READ

Returns a single autocomplete term specified by id as JSON like:

{ "id" : 1,
  "term" : "a string"
}

### POST http://localhost/term CREATE

Adds a term to the autocomplete table and returns the id of the newly created row:

Request:

{ "term" : "the string to add" }

Response:

{ "id" : 35 }
