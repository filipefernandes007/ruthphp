<?php

class PlanetVO extends BaseVO  {

    protected $id;
    protected $name;
    protected $age;
    protected $img;
    protected $intro;
    
    public function PlanetVO($obj = null) {
        $this->primaryKey = "id";
        
        parent::BaseVO($obj);
    }
    
    public function getId() {
        return (int) $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getImg() {
        return $this->img;
    }

    public function getAge() {
        return $this->age;
    }
    
    public function getIntro() {
        return $this->intro;
    }
    
    public function setId($id) {
        $this->id = (int) $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setAge($idade) {
        $this->age = (int) $idade;
    }

    public function setImg($image) {
        $this->img = $image;
    }
    
    public function setIntro($intro) {
        $this->intro = $intro;
    }
}
