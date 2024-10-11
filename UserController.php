<?php

class UserController {
    private static $db;

    public static function init() {
        self::$db = Database::getInstance();
    }

    public static function create() {

        // Reads the json body received by the post request
        $data = json_decode(file_get_contents("php://input"), true);

        // Basic validation
        if (!isset($data['username'], $data['password'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }

        $username = filter_var($data['username'], FILTER_VALIDATE_EMAIL);
        $password = $data['password'];

        // Validate email
        if ($username === false) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid email format']);
            return;
        }

        // Check if username exists
        $checkStmt = self::$db->prepare("SELECT id FROM Users WHERE username = ?");
        $checkStmt->bind_param('s', $username);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            http_response_code(409); // Conflict
            echo json_encode(['error' => 'Username already exists']);
            $checkStmt->close();
            return;
        }

        $checkStmt->close();

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Prepare SQL statement
        $stmt = self::$db->prepare("INSERT INTO Users (username, password) VALUES (?, ?)");

        // Bind parameters
        $stmt->bind_param('ss', $username, $hashedPassword);

        // Execute statement
        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(['message' => 'User created successfully', 'id' => self::$db->insert_id]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create user: ' . $stmt->error]);
        }

        // Close statement
        $stmt->close();
    }

    public static function getById($id) {
        // Query database for user with id
        // Return user data
    }

    public static function getAll() {
        // Query all users from database
        // Return list of users
    }

    public static function update($id) {
        $data = json_decode(file_get_contents("php://input"), true);
        // Update user in database
        // Return success/failure
    }

    public static function updatePartial($id) {
        // Similar to update but might only update specified fields
    }

    public static function delete($id) {
        // Delete user from database
        // Return success/failure
    }

    public static function getMarvels() {
        $pdo = Database::getInstance()->getConnection();
        try {
            // Prepare and execute SQL to fetch all marvels
            $stmt = $pdo->query("SELECT * FROM marvels");
            $results = $stmt->fetchAll();

            // Set the content type to JSON
            header('Content-Type: application/json; charset=utf8');

            // Output JSON encoded data
            echo json_encode($results, JSON_PRETTY_PRINT);
        } catch(PDOException $e) {
            // Log the error or display a user-friendly message
            echo json_encode(['error' => 'An error occurred while fetching data: ' . $e->getMessage()]);
            http_response_code(500); // Internal Server Error
        }
    }
}

UserController::init();