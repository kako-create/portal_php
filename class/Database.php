<?php
include_once(CLASS_PATH . "/Config.php");

class Database {
    private $conn;
    private $driver; // mysql | mysqli

    public function __construct() {
        // Detecção automática do driver
        if (function_exists('mysqli_connect')) {
            $this->driver = 'mysqli';
        } elseif (function_exists('mysql_connect')) {
            $this->driver = 'mysql';
        } else {
            // Lança uma exceção se nenhum driver for encontrado
            throw new Exception('Nenhuma extensão de banco de dados (MySQLi ou MySQL) foi encontrada.');
        }
    }
    
    /**
     * Conecta ao banco de dados. Lança uma exceção em caso de falha.
     */
    private function connect() {
        // Só conecta se ainda não houver uma conexão ativa
        if ($this->conn) {
            return;
        }

        if ($this->driver === "mysqli") {
            $this->conn = @mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if (!$this->conn) {
                throw new Exception("Erro ao conectar com MySQLi: " . mysqli_connect_error());
            }
            mysqli_set_charset($this->conn, CHARSET);
        } else {
            $this->conn = @mysql_connect(DB_HOST, DB_USER, DB_PASS);
            if (!$this->conn) {
                throw new Exception("Erro ao conectar com MySQL: " . mysql_error());
            }
            mysql_select_db(DB_NAME, $this->conn);
            mysql_set_charset(CHARSET, $this->conn);
        }
    }

    /**
     * Escapa uma string para uso seguro em queries.
     */
    public function escapeString($value) {
        $this->connect(); // Garante que a conexão exista
        if ($this->driver === "mysqli") {
            return mysqli_real_escape_string($this->conn, $value);
        } else {
            return mysql_real_escape_string($value, $this->conn);
        }
    }

    public function query($sql) {
        $this->connect(); // Garante que a conexão exista
        if ($this->driver === "mysqli") {
            $result = mysqli_query($this->conn, $sql);
            if (!$result) {
                 throw new Exception("Erro na query MySQLi: " . mysqli_error($this->conn));
            }
            return $result;
        } else {
            $result = mysql_query($sql, $this->conn);
             if (!$result) {
                 throw new Exception("Erro na query MySQL: " . mysql_error($this->conn));
            }
            return $result;
        }
    }

    public function fetch($result) {
        if (!$result) return null;
        
        if ($this->driver === "mysqli") {
            return mysqli_fetch_assoc($result);
        } else {
            return mysql_fetch_assoc($result);
        }
    }

    public function fetchAll($sql) {
        $res = $this->query($sql);
        $rows = [];
        while ($row = $this->fetch($res)) {
            $rows[] = $row;
        }
        $this->freeResult($res);
        $this->clearResults();
        return $rows;
    }

    public function numRows($result) {
        if (!$result) return 0;

        if ($this->driver === "mysqli") {
            return mysqli_num_rows($result);
        } else {
            return mysql_num_rows($result);
        }
    }
    
    /**
     * ⚠️ AVISO: A extensão 'mysql' não suporta prepared statements.
     * Esta função apenas escapa os parâmetros, o que é menos seguro.
     * A recomendação é usar apenas MySQLi ou PDO.
     */
    public function callProcedure($procedure, $params = []) {
        $paramList = [];
        foreach($params as $p) {
            if (is_numeric($p)) {
                $paramList[] = $p;
            } else {
                // Usa o método de escape correto e mais seguro
                $paramList[] = "'" . $this->escapeString($p) . "'";
            }
        }
        $sql = "CALL {$this->escapeString($procedure)}(" . implode(", ", $paramList) . ")";
        return $this->query($sql);
    }
    
    public function freeResult($res) {
        if ($this->driver === 'mysqli' && $res instanceof mysqli_result) {
             $res->free();
        } elseif (is_resource($res)) {
            mysql_free_result($res);
        }
    }
    
    public function clearResults() {
        if ($this->driver === 'mysqli' && $this->conn && method_exists($this->conn, 'more_results')) {
            while ($this->conn->more_results() && $this->conn->next_result()) {
                if ($extra = $this->conn->store_result()) {
                    $extra->free();
                }
            }
        }
    }

    public function getLastError() {
        if (!$this->conn) return "Sem conexão.";
        
        return ($this->driver === 'mysqli')
            ? mysqli_error($this->conn)
            : mysql_error($this->conn);
    }

    public function __destruct() {
        if ($this->conn) {
            if ($this->driver === 'mysqli') {
                mysqli_close($this->conn);
            } else {
                mysql_close($this->conn);
            }
        }
    }
}