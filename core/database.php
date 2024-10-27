<?php

class Database
{
    private static $instance = null;
    private $conn;

    private $dbname   = "zoneshop_card2kv1"; // tên CSDL của bạn
    private $username = 'zoneshop_card2kv1';     // tên đăng nhập CSDL của bạn
    private $password = '%O0]JkX{A$t=';         // mật khẩu CSDL của bạn

    private function __construct()
    {
        $dburl = "mysql:host=localhost;dbname=$this->dbname;charset=utf8";
        $this->conn = new PDO($dburl, $this->username, $this->password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function getDbName()
    {
        return $this->dbname;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }
}

// Hàm thực hiện: INSERT, UPDATE, DELETE
function pdo_execute(string $sql, array $args = [])
{
    try {
        $conn = Database::getInstance()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute($args);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        throw new Exception("Failed to execute query: " . $e->getMessage());
    }
}

// Hàm thực hiện: SELECT (nhiều bản ghi)
function pdo_query(string $sql, array $args = []): array
{
    try {
        $conn = Database::getInstance()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute($args);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows !== false ? $rows : [];
    } catch (PDOException $e) {
        error_log($e->getMessage());
        throw new Exception("Failed to execute query");
    }
}

// Hàm thực hiện: SELECT (1 bản ghi)
function pdo_query_one(string $sql, array $args = []): array
{
    try {
        $conn = Database::getInstance()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute($args);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row !== false ? $row : [];
    } catch (PDOException $e) {
        error_log($e->getMessage());
        throw new Exception("Failed to execute query");
    }
}

// Hàm thực hiện: SELECT (1 giá trị)
function pdo_query_value(string $sql, array $args = [])
{
    try {
        $conn = Database::getInstance()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute($args);
        $value = $stmt->fetchColumn();
        return $value;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        throw new Exception("Failed to execute query");
    }
}

// Hàm xuất file .csv dùng để backup dữ liệu
function pdo_export_to_csv(string $sql, array $args = [], string $filename = 'backup.csv')
{
    try {
        $conn = Database::getInstance()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute($args);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($rows) {
            $fp = fopen($filename, 'w');

            // Add BOM to fix UTF-8 in Excel
            fputs($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Write the column headers
            fputcsv($fp, array_keys($rows[0]));

            // Write the data
            foreach ($rows as $row) {
                // Add a tab character at the beginning of each value
                $row = array_map(function ($value) {
                    return "\t" . $value;
                }, $row);
                fputcsv($fp, $row);
            }

            fclose($fp);
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        throw new Exception("Failed to export to CSV: " . $e->getMessage());
    }
}
