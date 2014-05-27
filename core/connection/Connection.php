<?php



/**
 *
 * Classe para ligação à base de dados.
 *
 * @author Filipe Fernander
 *
 */
final class Connection {

    private $conn;
    protected $lastQuery;
    protected $affectedRows;
    private static $instance;

    private function Connection() {

    }

    /**
     *
     * Instancia um PDO de acordo com as credenciais recebidas.
     *
     * @param  string $host
     * @param  string $user
     * @param  string $pw
     * @param  string $db
     * @throws Exception
     */
    public function init($host = null, $user = null, $pw = null, $db = null) {
        $args = func_num_args();

        if (!($args == 0 || $args == 4)) // aceitar apenas com 0 ou 4 argumentos
            throw new InvalidArgumentException("Número de argumentos inválido!", 1);

        if (!isset($host, $user, $pw, $db)) {
            $dsn = Config::init()->Adapter() . ':dbname=' . Config::init()->Database() . ';host=' . Config::init()->Host();
            $user = Config::init()->User();
            $pw = Config::init()->Password();

        } else {
            $dsn  = ''. Config::init()->Adapter() . ':dbname=' . Config::init()->Database() . ';host=' . Config::init()->Host();
            //$dsn = Config::init()->Adapter() ? Config::init()->Adapter() : "mysql:" . ":dbname=" . $db . ";host=" . $host;
        }

        try {
            $this->conn = new PDO($dsn, $user, $pw);
        } catch(PDOException $e) {
            die("Erro a instanciar PDO! " . $e->getMessage() . $e->getCode());
        }


        /*
        try {
            $this->conn = new PDO($dsn, $user, $pw);
        } catch (PDOException $e) {
            print "<br/>Erro!: " . $e->getMessage() . "<br/>" . $dsn;
            die();
        }
         *
         */
    }

    /**
     *
     * Retorna uma instância de Connection.
     * Por se tratar de um singleton, a instância é apenas uma e uma só.
     *
     * @return Connection
     */
    public static function getInstance() {
        $class = __CLASS__;

        if (!isset(self::$instance))
            self::$instance = new $class();

        return self::$instance;
    }

    public function __destruct() {
        $this->conn = null;
    }

    /**
     *
     * Retorna a ligação,
     * concretamente a instância de PDO com as credencias que permitam a ligação à base de dados.
     *
     * @return object PDO
     */
    public function getConn() {
        return $this->conn;
    }

    /**
     *
     * Efectua a query com base no SQL que é passado como argumento
     *
     * @param string $sql
     * @return mixed
     * @throws Exception
     */
    public function doQuery($sql) {

        $res = $this->conn->query($sql);

        if (!$res)
            throw new Exception("Erro! Não efectuou a query!", 0);

        $this->lastQuery = $sql;
        $this->affectedRows = $res->rowCount();

        return $res;
    }

    /**
     *
     * Efectua uma query à base de dados dado o SQL recebido como parâmetro.
     *
     * @param string $sql
     * @return array
     */
    public function query($sql) {
        return $this->conn->query($sql);
    }

    /**
     *
     * Devolve o número de linhas afectadas após a query. Nota: apenas para DELETE, INSERT, or UPDATE
     *
     * @return integer
     */
    public function affectedRows() {
        return $this->affectedRows;
    }

    public function lastInsertId() {
        return $this->conn->lastInsertId();
    }

    /**
     *
     * Obter a última query feita à base de dados
     *
     * @return string
     */
    public function getLastQuery() {
        return $this->lastQuery;
    }

    public function startTransaction() {
        $this->conn->beginTransaction();
    }

    public function commitTransaction() {
        $this->conn->commit();
    }

    public function rollbackTransaction() {
        $this->conn->rollBack();
    }

    /**
     *
     * Evitar SQL Injection
     *
     * Utilizar para bind ao objecto ou array que se quer passar na query,
     * afim de eliminar caracteres indesejáveis
     *
     * @link http://php.net/manual/pt_BR/mysqli.real-escape-string.php
     *
     * @param object $obj
     */
    public function realEscape(&$obj) {
        if (is_object($obj))
            foreach ($obj as $prop => $value)
                $obj->$prop = $this->conn->real_escape_string($value);
        else
            $obj = $this->conn->real_escape_string($obj);
    }

}
