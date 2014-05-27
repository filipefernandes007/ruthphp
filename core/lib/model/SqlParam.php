<?php

    /**
     * Description of SqlParam
     * 
     * Classe composta pelos métodos primários de queries à base de dados
     *
     * @author Filipe
     */

    class SqlParam {
        public $vo;
        public $primaryKey;
        public $table;
        public $fields;
        public $where;
        public $orderby;
        public $limit;
        public $sql;

        /**
         * Inicializa o SqlParam, percebendo quais as propriedades da classe que o invoca
         */

        public function SqlParam() {
            $prop = get_class_vars(__CLASS__);

            // inicializar as propriedades
            foreach($prop as &$value) {
                $value = "";
            }

            unset($value);

            $this->vo = null;
        }

        /**
         * 
         * Constrói a query SELECT
         * 
         * @return string
         */

        public function bindToSelect() {
            $this->sql = "SELECT * FROM {$this->table} ";

            if(!empty($this->fields) && is_array($this->fields)) {
                $i   = 0;
                $num = sizeof($this->fields);

                foreach($this->fields as $value) {
                    if($i < $num -1)
                        $this->fields .= $value . ",";
                    else
                        $this->fields .= $value;

                    $i++;
                }
            }

            if(!empty($this->where))
                $this->sql .= "WHERE {$this->where} ";

            if(!empty($this->orderby))
                $this->sql .= "ORDER BY {$this->orderby} ";

            if(!empty($this->limit))
                $this->sql .= "LIMIT {$this->limit}";

            return $this->sql;
        }

        /**
         * 
         * Constroi a query INSERT
         * 
         * @return string
         */

        public function bindToInsert() {
            $_obj      = Debug::getProp($this->vo);
            $num       = count(get_object_vars($_obj));
            $this->sql = "INSERT INTO {$this->table} ";

            $i   = 0;
            $col = "";
            $val = "";

            foreach($_obj as $key => $value) {
                if($i < $num - 1) {
                    $col .= "{$key}, ";
                    $val .= ":{$key}, ";
                } else {
                    $col .= "{$key}";
                    $val .= ":{$key}";
                }

                $i++;
            }

            $this->sql .= "($col) VALUES($val);";

            return $this->sql;
        } 

        /**
         * 
         * Constroi a query DELETE
         * 
         * @return string
         */

        public function bindToDelete() {
            $this->sql = "DELETE FROM {$this->table} WHERE ";

            if(is_array($this->primaryKey)) {
                $i = 0;

                foreach($this->primaryKey as $key => $value) {
                    if($i == 0)
                        $this->sql .= " {$key} = ?";
                    else
                        $this->sql .= " AND {$key} = ? ";

                    $i++;
                } 
            } else {
                $this->sql .= $this->primaryKey . " = ?";
            }

            return $this->sql;
        }

        /**
         * 
         * Constroi a query UPDATE
         * 
         * @return string
         */

        public function bindToUpdate() {
            $_obj      = Debug::getProp($this->vo);
            $num       = count(get_object_vars($_obj));
            $this->sql = "UPDATE {$this->table} SET ";

            $i   = 0;
            $col = "";

            foreach($_obj as $key => $value) {
                if($key != $this->primaryKey) {
                    if($i < $num - 1) {
                        $col .= "{$key} = :{$key}, ";
                    } else {
                        $col .= "{$key} = :{$key}";
                    }
                }

                $i++;
            }

            $this->sql .= $col . " WHERE ";

            if(is_array($this->primaryKey)) {
                $i = 0;

                foreach($this->primaryKey as $key => $value) {
                    if($i == 0)
                        $this->sql .= " {$key} = :{$key}";
                    else
                        $this->sql .= " AND {$key} = :{$key} ";

                    $i++;
                } 
            } else {
                $this->sql .= $this->primaryKey . " = :{$this->primaryKey}";
            }

            return $this->sql;
        }

        public function setVO($vo) {
            $this->vo = Debug::getProp($vo);
        }
    }

