<?php
include_once(CLASS_PATH . "/Config.php");

class Database {
    private $conn;
    private $driver; // mysql | mysqli

    public function __construct($driver = "mysql") {
        $this->driver = $driver;

        if ($driver === "mysqli") {
            $this->conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            mysqli_set_charset($this->conn, CHARSET);
            if(!$this->conn) die("Erro MySQLi: " . mysqli_connect_error());
        } else {
            $this->conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("Erro MySQL: " . mysql_error());
            mysql_select_db(DB_NAME, $this->conn);
            mysql_set_charset(CHARSET, $this->conn);
        }
    }

    public function query($sql) {
        if ($this->driver === "mysqli") {
            return mysqli_query($this->conn, $sql);
        } else {
            return mysql_query($sql, $this->conn);
        }
    }

    public function fetch($result) {
        if ($this->driver === "mysqli") {
            return mysqli_fetch_assoc($result);
        } else {
            return mysql_fetch_assoc($result);
        }
    }

    public function numRows($result) {
        if ($this->driver === "mysqli") {
            return mysqli_num_rows($result);
        } else {
            return mysql_num_rows($result);
        }
    }

    public function callProcedure($procedure, $params = []) {
        $paramList = [];
        foreach($params as $p) {
            if (is_numeric($p)) {
                $paramList[] = $p;
            } else {
                $paramList[] = "'" . addslashes($p) . "'";
            }
        }
        $sql = "CALL {$procedure}(" . implode(", ", $paramList) . ")";
        return $this->query($sql);
    }

}
