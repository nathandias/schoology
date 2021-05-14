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

Example:

`curl -v http://localhost/terms`

Returns list of unique autocompletion terms as JSON like:

{
    "terms" : ["Foo", "Bar", "string containing spaces"]
}

### GET http://localhost/term/{int:id} READ

Example:

`curl -v http://localhost/term/3`

Returns a single autocompletion term specified by id as JSON like:

{ "id" : 1,
  "term" : "a string"
}

### POST http://localhost/term CREATE

Example:

`curl -v -d "{'term': 'the term to add'}" -H "Content-Type: application/json" -H "Accept: application/json" http://localhost/term`

Adds a term to the autocompletion table and returns the id of the newly created row:

Request:

{ "term" : "the string to add" }

Response:

{ "id" : 35 }

## TODO
1. FIX: Front-end autocomplete UI does not accept/confirmed selected option...UI needs proper configuration.
2. ADD: Embed the autocomplete input in an HTML FORM, and on submit:
    - implement a getResults() function to return results for the search
    - add the "new" search term to the autocomplete table by POSTing to http://localhost/term

## IDEAS
1. extend the autocompletions table to support things like "context" and "frequency"
    - context (string): associate the term with the page or context it was entered on, so that future suggestions can be presented per-page/context
    - frequency (integer): number of times the term has been searched, to provide alternate ranking of suggestions
    

