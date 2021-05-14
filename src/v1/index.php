<?php

// open a handle to the database
$db = new SQLite3('/var/www/db/db.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);

// create a table of autocompletions terms
$db->query(
    'CREATE TABLE IF NOT EXISTS "autocompletions" (
        "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
        "term" VARCHAR
    )'
);

// if the autocompletions table is empty, seed it with some sample data for testing
$term_count = $db->querySingle("SELECT COUNT('id') FROM autocompletions");
if ($term_count == 0) {
    $db->query('INSERT INTO "autocompletions" ("term") VALUES ("Apple")');
    $db->query('INSERT INTO "autocompletions" ("term") VALUES ("Application")');
    $db->query('INSERT INTO "autocompletions" ("term") VALUES ("Bannana")');
    $db->query('INSERT INTO "autocompletions" ("term") VALUES ("Orange")');
    $db->query('INSERT INTO "autocompletions" ("term") VALUES ("orangutans are Monkeys")');
    $db->query('INSERT INTO "autocompletions" ("term") VALUES ("Monkey")'); 
    $db->query('INSERT INTO "autocompletions" ("term") VALUES ("Pear")');
    $db->query('INSERT INTO "autocompletions" ("term") VALUES ("pear cider")');
    $db->query('INSERT INTO "autocompletions" ("term") VALUES ("person")');

}
class term {

    static public function get($request) {
        
        // handle a GET request
        // v1/term/{id} --> return the term with specified id, if it exists
        
        global $db;

        $id = array_shift($request);
        
        $stmt = $db->prepare('SELECT id, term FROM autocompletions WHERE id = :id;');
        $stmt->bindValue(':id', $id);
        $results = $stmt->execute();

        if ($row = $results->fetchArray()) {
            // a single row returned (note: if multiple rows returned, major problem)
                
            $json = json_encode(array('id' => $row['id'], 'term' => $row['term']));

            http_response_code(200); // Resource exists 200: OK

        } else {
            http_response_code(404); // Not Found
        }

        header("Content-Type: application/json");

        print $json;
     }


    static public function post($request) {
        global $db;

        $json_response = null;
        
        // handle a POST request
        $body = file_get_contents('php://input');
        $content_type = strtolower($_SERVER['CONTENT_TYPE']);
        switch(strtolower($content_type)) {
            case "application/json":
                
                $json_object = json_decode($body); // should contain { "term" : "the term" }
        
                // Validate input (todo)
                // make sure there's one an only key value pair ("term" => "a string")
                // escape the string and avoid SQL injection attacks
                $term = $json_object->term;
                
                $statement = $db->prepare("INSERT INTO autocompletions (term) VALUES ( :term );");
                $statement->bindValue(':term', $term, SQLITE3_TEXT);
                $result = $statement->execute();
                $id = $db->lastInsertRowID();
                
                $json_response = json_encode(array('id' => $id));

                // Create new Resource
                http_response_code(201); // Created
                $site = 'http://localhost';
                header("Location: $site/" . $_SERVER['REQUEST_URI'] . "/$id");       
                break;
            default:
                $json_response = json_encode(array('unsupported content type' => $content_type));
                http_response_code(415); // unsupported media type
                break;
        }       
            
        header('Content-Type: application/json');
        print $json_response;
    }

}

class terms {
    // handle requests related

    static public function get($request) {
        // handle a GET request
        // v1/terms     --> return all autocompletions terms
        
        global $db;
        
        $results = $db->query('SELECT DISTINCT term FROM autocompletions ORDER BY term ASC;');

        $terms = [];

        while ($row = $results->fetchArray()) {
            array_push($terms, $row['term']);
        }

        $json = json_encode(['terms' => $terms]);

        // Resource exists 200: OK
        http_response_code(200);

        // And it's being sent back as JSON
        header('Content-Type: application/json');

        print $json;
    }

}

// break apart URL and extract the root resource
$request = explode('/', $_GET['PATH_INFO']);
$resource = array_shift($request);

// only process valid resources
$resources = array('term'=> true, 'terms' => true);
if (! array_key_exists($resource, $resources)) {
    http_response_code(404);
    exit;
}

// route the request to the appropriate function based on method
$method = strtolower($_SERVER["REQUEST_METHOD"]);
switch($method) {
    case 'get':
    case 'post':
    case 'put':
    case 'delete':
    // any other methods you want to support, such as HEAD
        if (method_exists($resource, $method)) {
            call_user_func(array($resource, $method), $request);
            break;
        }
        // fall through
    default:
        http_response_code(405);
}