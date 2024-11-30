<?php
namespace Model;

class Database
{
    protected $mysqli;

    public function __construct()
    {
        $env = parse_ini_file('./config/env.ini');
        $host = $env['host'];
        $user = $env['user'];
        $password = $env['password'];
        $dbname = $env['dbname'];

        $this->mysqli = new \mysqli($host, $user, $password, $dbname);
        $this->mysqli->set_charset("utf8");
    }
}

class GetProducts extends Database
{
    public $result;

    public function __construct($category = null, $before_price = null, $after_price = null, $name = null)
    {
        parent::__construct();

        $query = 'SELECT * FROM products WHERE 1=1';
        $params = [];
        $types = '';

        if ($category) {
            $types .= 's';
            $params[] = $category;
            $query .= ' AND category = ?';
        }
        if ($before_price) {
            $types .= 'd';
            $params[] = $before_price;
            $query .= ' AND price >= ?';
        }
        if ($after_price) {
            $types .= 'd';
            $params[] = $after_price;
            $query .= ' AND price <= ?';
        }
        if ($name) {
            $types .= 's';
            $params[] = "%$name%";
            $query .= ' AND name LIKE ?';
        }

        $stmt = $this->mysqli->prepare($query);

        if ($stmt) {
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $this->result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }
    }
}
