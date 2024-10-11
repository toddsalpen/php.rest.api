<?php
function route($uri, $method, callable $callback): void {
    $serverMethod = strtoupper($_SERVER['REQUEST_METHOD']);
    $serverUri = $_SERVER['REQUEST_URI'];
    if ($serverMethod !== $method || !preg_match('#^' . preg_replace('#\{(\w+)\}#', '(?<$1>[^/]+)', $uri) . '$#i', $serverUri, $matches)) {
        return;
    }
    $uriParams = array_intersect_key($matches, array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY));
    $id = $uriParams['id'] ?? null;
    switch($method){
        case 'POST': {
            $body = json_decode(file_get_contents("php://input"));
            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);
                echo json_encode(["error" => "Invalid JSON payload"]);
                exit;
            }
            call_user_func($callback, $body);
            exit;
        }
        case 'GET': {
            call_user_func($callback, $id);
            exit;
        }
        case 'PUT': {
            if(is_null($id)){
                http_response_code(400);
                echo json_encode(['error' => 'Failed to read identifier']);
                exit;
            }
            $body = json_decode(file_get_contents("php://input"));
            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);
                echo json_encode(["error" => "Invalid JSON payload"]);
                exit;
            }
            call_user_func($callback, $id, $body);
            exit;
        }
        case 'DELETE': {
            if(is_null($id)){
                http_response_code(400);
                echo json_encode(['error' => 'Failed to read identifier']);
                exit;
            }
            call_user_func($callback, $id);
            exit;
        }
        default:{
            header('HTTP/1.1 405 Unauthorized');
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }
    }
}
function getAuthorizationToken(){
    $authorizationToken = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    if (empty($authorizationToken)) {
        header('HTTP/1.1 401 Unauthorized');
        header('WWW-Authenticate: Bearer realm="Restricted Access"');
        echo json_encode(['error' => 'authorization header missing']);
        exit;
    }
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    $contentType = strtolower(trim($contentType));
    if ($contentType !== 'application/json') {
        header('HTTP/1.1 415 Unsupported Media Type');
        header('Content-Type: application/json');
        echo json_encode([
            'error' => 'Unsupported Media Type',
            'message' => 'Content-Type must be application/json'
        ]);
        http_response_code(415);
        exit;
    }
    return $authorizationToken;
}

require_once 'config.php';
require_once 'Database.php';
require_once 'UserController.php';

// Create object
route('/object', 'POST', function($body) {
    $token = getAuthorizationToken();
    echo json_encode(['success' => true, 'message' => 'this helps to add one object to the system', 'token' => $token, 'body' => $body]);
});

// Read users
route('/objects', 'GET', function() {
    $token = getAuthorizationToken();
    echo json_encode(['success' => true, 'message' => 'this helps to display objects from the system', 'token' => $token]);
});

// Read user
route('/object/{id}', 'GET', function($id) {
    $token = getAuthorizationToken();
    if(is_null($id)){
        http_response_code(400);
        echo json_encode(['error' => 'Failed to read identifier']);
        exit;
    }
    echo json_encode(['success' => true, 'message' => 'this helps to display one object from the system', 'token' => $token,'id' => $id]);
});

// Update user
route('/object/{id}', 'PUT', function($id, $body) {
    $token = getAuthorizationToken();
    echo json_encode(['success' => true, 'message' => 'this helps to object one user to the system', 'token' => $token, 'id' => $id, 'body' => $body]);
});

// Delete user
route('/object/{id}', 'DELETE', function($id) {
    $token = getAuthorizationToken();
    echo json_encode(['success' => true, 'message' => 'this helps to delete one object from the system', 'token' => $token, 'id' => $id]);
});

// Default route if no match (optional)
route('/', 'GET', function() {
    echo "Objects by trinketronix.com";
});

// If no route matches, you might want to show a 404
if (!isset($matched)) {
    http_response_code(404);
    echo "404 Not Found";
}