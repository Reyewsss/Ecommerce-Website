<?php
/**
 * Database Connection Class
 * 
 * This class handles all database connections and operations
 * using PDO for security and flexibility
 */

require_once 'config.php';

class Database {
    private $host;
    private $port;
    private $username;
    private $password;
    private $database;
    private $charset;
    private $connection;
    private static $instance = null;

    public function __construct() {
        $this->host = DB_HOST;
        $this->port = DB_PORT;
        $this->username = DB_USERNAME;
        $this->password = DB_PASSWORD;
        $this->database = DB_NAME;
        $this->charset = DB_CHARSET;
        
        $this->connect();
    }

    /**
     * Singleton pattern to ensure only one database connection
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Establish database connection
     */
    private function connect() {
        try {
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->database};charset={$this->charset}";
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$this->charset}"
            ];

            $this->connection = new PDO($dsn, $this->username, $this->password, $options);
            
            if (is_development()) {
                error_log("Database connected successfully to: " . $this->database);
            }
            
        } catch (PDOException $e) {
            $error_message = "Database Connection Failed: " . $e->getMessage();
            
            // Log the error
            error_log($error_message);
            
            if (is_development()) {
                die($error_message);
            } else {
                die("Database connection error. Please try again later.");
            }
        }
    }

    /**
     * Get the PDO connection instance
     */
    public function getConnection() {
        return $this->connection;
    }

    /**
     * Execute a query with parameters
     */
    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Query Error: " . $e->getMessage() . " | SQL: " . $sql);
            if (is_development()) {
                throw $e;
            }
            return false;
        }
    }

    /**
     * Fetch all results from a query
     */
    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt ? $stmt->fetchAll() : [];
    }

    /**
     * Fetch single result from a query
     */
    public function fetchOne($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt ? $stmt->fetch() : false;
    }

    /**
     * Get the last inserted ID
     */
    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }

    /**
     * Get the number of affected rows
     */
    public function rowCount($stmt) {
        return $stmt->rowCount();
    }

    /**
     * Begin transaction
     */
    public function beginTransaction() {
        return $this->connection->beginTransaction();
    }

    /**
     * Commit transaction
     */
    public function commit() {
        return $this->connection->commit();
    }

    /**
     * Rollback transaction
     */
    public function rollback() {
        return $this->connection->rollback();
    }

    /**
     * Insert data into a table
     */
    public function insert($table, $data) {
        $columns = implode(',', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        
        try {
            $stmt = $this->connection->prepare($sql);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            error_log("Insert Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update data in a table
     */
    public function update($table, $data, $where, $whereParams = []) {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "{$key} = :{$key}";
        }
        $set = implode(', ', $set);
        
        $sql = "UPDATE {$table} SET {$set} WHERE {$where}";
        
        try {
            $stmt = $this->connection->prepare($sql);
            return $stmt->execute(array_merge($data, $whereParams));
        } catch (PDOException $e) {
            error_log("Update Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete data from a table
     */
    public function delete($table, $where, $params = []) {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        
        try {
            $stmt = $this->connection->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Delete Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if table exists
     */
    public function tableExists($table) {
        try {
            $stmt = $this->connection->prepare("SHOW TABLES LIKE ?");
            $stmt->execute([$table]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Test database connection
     */
    public function testConnection() {
        try {
            $this->connection->query('SELECT 1');
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}

// Create global database instance
try {
    $db = Database::getInstance();
} catch (Exception $e) {
    error_log("Failed to create database instance: " . $e->getMessage());
    if (is_development()) {
        die("Database initialization failed: " . $e->getMessage());
    } else {
        die("Database error. Please try again later.");
    }
}

?>