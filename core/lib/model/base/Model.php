<?php
    
    namespace Core\Lib\Model\Base;
    
    use Core\Lib\Model\Base\BaseModel;
    
    include_once CORE_LIB . '/model/base/BaseModel.php';

    /**
     * @abstract
     * 
     * Todos os Model's devem estender esta classe, já que ela que detém os métodos necessários a efectuar queries perceber os VO's e as tabelas envolvidas
     * 
     * @author  Filipe Fernandes
     * 
     */

    abstract class Model extends BaseModel {

        protected $table;   // a tabela da base de dados a que respeita o Model
        protected $vo;      // o ValueObject que está na base de abstracção da tabela da base de dados

        public $primaryKey;

        /**
         * 
         * Estabelece a ligação à base de dados e guarda a instância da
         * classe que providencia essa ligação.
         * 
         * @return Connection
         */

        protected function init() {
            parent::BaseModel();
        }



        /**
         * 
         * A tabela da base de dados a que respeita o Model
         * 
         * @return string
         */

        public function getTable() {
            return $this->table;
        }

        /**
         * 
         * O ValueObject
         * 
         * @return string
         */

        public function getVO() {
            return $this->vo;
        }

        /************************************************************************************/
        /* Métodos protegidos para efectuar queries, e que devem ser inocados pelas classes */
        /* que herdem Model.                                                                */
        /************************************************************************************/

        /**
         * 
         * Método para qualquer tipo de queries
         * 
         * @param string $sql
         * @return array
         */

        protected function query($sql, $withVO = true) {
            $obj = $this->calledClass();
            $res = $this->getConn()->doQuery($sql);

            if($withVO)
                return $res->fetchAll(PDO::FETCH_CLASS, $obj->getVO());

            return $res->fetchAll();
        }

        /**
         * 
         * * Método genérico para SELECT, que deve ser o invocado pelos Model's
         * 
         * @param SqlParam $sqlParam
         * @return type
         */

        protected function Select(SqlParam $sqlParam) {
            $sqlParam->bindToSelect();
            $res = $this->bindQuery($sqlParam);

            return $res->fetchAll(PDO::FETCH_CLASS, $this->vo);;
        }

        /**
         * 
         * Método genérico para INSERT numa tabela
         * 
         * @param SqlParam $sqlParam
         * @return type
         */

        protected function Insert(SqlParam $sqlParam) {
            $sqlParam->bindToInsert();
            $res = $this->bindQuery($sqlParam);

            return $this->getConn()->lastInsertId();
        }


        /**
         * 
         * Método genérico para UPDATE a uma tabela
         * 
         * @param SqlParam $sqlParam
         * @return int
         */

        protected function Update(SqlParam $sqlParam) {
            $sqlParam->bindToUpdate();
            $res = $this->bindQuery($sqlParam);

            return $res->rowCount();
        }

        /**
         * 
         * Método genérico para DELETE a uma tabela
         * 
         * @param SqlParam $sqlParam
         * @return type
         */

        protected function Delete(SqlParam $sqlParam) {
            $sqlParam->bindToDelete();
            $res = $this->bindQuery($sqlParam);
            return $res->rowCount();
        }

        protected function DeleteById($id) {
            $obj = $this->calledClass();
            $v   = $obj->getVO();
            $vo  = new $v();

            $vo->setId($id);

            $sqlParam = new SqlParam();
            $sqlParam->setVO($vo);
            $sqlParam->table = $obj->getTable();
            $sqlParam->primaryKey = $obj->primaryKey;

            $sqlParam->bindToDelete();

            $res = $this->bindQuery($sqlParam);

            return $res->rowCount();
        }


        /**
         * 
         * Efectua uma transacção.
         * 
         * @param  string $sql
         * @return mixed
         */

        protected function Transaction($sql) {
            $res = array();

            try {
                $this->getConn()->getConn()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->getConn()->startTransaction();

                $res = $this->query($sql);

                $this->getConn()->commitTransaction();
            } catch (Exception $e) {
                $this->getConn()->rollbackTransaction();

                if(Config::init()->Stage() == DEVELOPMENT)
                    $msg = $e->getMessage();
                else
                    $msg = $e->getMessage() . "<br/>" . $e->getTraceAsString();

                $view = new ViewException($msg, true);
                exit();
            }

            return $res;
        }

        /************************************************************************************/
        /* Métodos pré-feitos para queries genéricas, como getById e getAll                 */
        /************************************************************************************/

        /**
         * 
         * Consultar um determinado registo de uma tabela da base de dados
         * 
         * @param int $i
         * @return object
         */

        protected function getById($id) {
            $obj = $this->calledClass();

            $query = "SELECT * FROM {$obj->getTable()} WHERE ";

            if(is_array($obj->primaryKey) && is_array($id)) {
                $i = 0;

                foreach($obj->primaryKey as $key => $value) {
                    if($i == 0)
                        $query .= " {$key} = " . (int) $id[$key];
                    else
                        $query .= " AND {$key} = " . (int) $id[$key];

                    $i++;
                } 
            } else {
                $query .= $obj->primaryKey . " = " . (int) $id;
            }

            return $this->query($query);
        }

        /**
         * 
         * Método genérico para a consulta de todos os dados de uma tabela da base de dados
         * 
         * @return array of objects
         */

        protected function getAll() {
            $obj = $this->calledClass();

            $sql = "SELECT * FROM {$obj->getTable()};";

            return $this->query($sql);
        }


        /************************************************************************************/
        /* Utils                                                                            */
        /************************************************************************************/

        /**
         * 
         * Obter a classe que herda Model e que invocou um dos seus métodos.
         * Essencial para abstrair em que tabela da base de dados se opera,
         * bem como o tipo de objecto esperado que resulta da query à base de dados.
         * 
         * @return \strClass
         */

        protected function calledClass() {
            $strClass = get_called_class();

            return new $strClass();
        }



    }
