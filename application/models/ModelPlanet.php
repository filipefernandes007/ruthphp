<?php

class ModelPlanet extends Model {

    public function ModelPlanet() {
        parent::init();

        $this->table      = "planet";
        $this->vo         = "PlanetVO";
        $this->primaryKey = "id";
    }
    
    /**
     * [allowRaw(production)]
     * 
     * @return mixed
     */
    public function getAll() {
        return parent::getAll();
    }

    public function getById($i) {
        //return parent::getById($i); // é o que se define por defeito

        $sqlFile = new SqlFile();
        $sql  = $sqlFile->load("PlanetGetById");
        $sql  = $sqlFile->Where(array("pl.id" => (int)$i));
        //$sql  = $sqlFile->GroupBy(array("pl.id"));

        // efectuar a query, passando simplesmente o SQL
        $data = $this->query($sql);

        // vamos utilizar um VO Genérico porque os dados obtidos vêm de uma querie
        // que além de fazer reflectir toda a tabela Planet, reflecte também outras tabelas
        $vo   = new GenericVO($data[0]);

        // não esquecer que o resultado esperado é sempre um array de objectos
        $array   = array();
        $array[] = $vo;

        return $array;
    }

    public function delete(SqlParam $obj) {
        return parent::Delete($obj);
    }

    public function update(SqlParam $obj) {

    }

    public function insert(SqlParam $obj) {
        $sqlParam = new SqlParam();
        $sqlParam->setVO($obj->vo);
        $sqlParam->table = $this->table;

        return parent::Insert($sqlParam);
    }

    public function printAll() {
        print("All");
    }

}
