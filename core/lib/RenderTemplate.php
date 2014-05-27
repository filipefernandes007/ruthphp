<?php

/**
 * API
 *
 * @author  Filipe Fernandes, Aluno 1005899 @UAb
 *
 * A framework.
 *
 * @created 12/03/2013
 * @update  19/06/2013
 * @version 1.1.3
 */

class RenderTemplate {
    protected $layout;      // guarda o layout
    protected $urlLayout;
    protected $content;     // guarda o conteúdo mais recente lido através do método load()
    protected $vo;          // guarda o ValeuObject, de forma a captar as propriedades que vão ser trabalhadas
    protected $searchKey;   // chaves de pesquisa para substituir propriedades de VO's pelos valores adequados
    protected $render = true;
    protected $bodyContent;

    private static $instance;   // a instância desta classe; só pode haver uma!

    /**
     * Instancia a API
     */

    private function RenderTemplate() {
        //realpath(dirname(__FILE__)) - pasta da /render
    }


    /**
     *
     * Devolve a instância da API; só pode haver uma, sendo por isso a API um Singleton
     *
     * @return RenderTemplate
     */

    public static function getInstance() {
        $class = __CLASS__;     // $class fica com o nome da classe com o proposito da instanciar a seguir

        if (!isset(self::$instance)) { // se ainda não tiver sido instanciado, criar instancia
            self::$instance = new $class();	// instância de API

            self::$instance->run();
        }

        return self::$instance;
    }

    /**
     *
     * Override a __clone para prevenir a clonagem da instância
     */

    public function __clone() {
        trigger_error('API já foi instanciada!', E_USER_WARNING);
    }

    /**
     *
     * Carrega o layout.
     *
     */

    private function run() {
        if($this->layout)
            return;

        $layout = Config::init()->Layout();

        if(!is_null($layout)) {
            $this->loadLayout($layout, $this->render);
        } else {
            $this->loadLayout("layout.tpl", $this->render);
        }
    }

    /**
     *
     * Lê um ficheiro de texto e devolve o seu conteúdo. Esse conteúdo fica guardado temporariamente em $content
     *
     * @param  string $url
     * @return string
     */

    public function load($url) {
        //$url = ROOT . "public" . DIRECTORY_SEPARATOR . $url;

        $url = PRESENTERS_LAYOUT . DIRECTORY_SEPARATOR . $url;

        if(!file_exists($url)) {
            throw new Exception("Erro! O ficheiro " . $url . " não existe!", 0);
        } else {
            $this->content = file_get_contents($url);
        }

        return $this->content;
    }
    
    /**
     * 
     * @param string $url
     * @return string
     */
    public function loadContainer($url) {
        $url = PRESENTERS_LAYOUT . DIRECTORY_SEPARATOR . "containers" . DIRECTORY_SEPARATOR . $url;
        
        return $this->freeLoad($url);
    }
    
    /**
     * 
     * @param string $url
     * @return string
     */
    public function loadElement($url) {
        $url = PRESENTERS_LAYOUT . DIRECTORY_SEPARATOR . "elements" . DIRECTORY_SEPARATOR . $url;
        
        return $this->freeLoad($url);
    }

    /**
     *
     * Lê um ficheiro de uma pasta qualquer
     *
     * @uses ViewException
     *
     * @param string $url
     * @return string
     * @throws Exception
     */

    public function freeLoad($url) {
        if(!file_exists($url)) {
            throw new Exception("Erro! O ficheiro " . $url . " não existe!", 0);
        } else {
            $this->content = file_get_contents($url);
        }

        return $this->content;
    }

    /**
     *
     * carrega o layout do projecto e executa o seu código
     *
     * @example $render->load("layout.html"); $render->setLayout();
     *
     * @param  string $url
     * @return string
     */

    public function loadLayout($layout, $render = true) {
        $this->urlLayout = LAYOUT_FOLDER . $layout;

        if(!file_exists($this->urlLayout)) {
            throw new Exception("Erro! O ficheiro " . $this->urlLayout . " não existe!", 0);
        } else {
            if(!$render) {
                $this->layout = file_get_contents($this->urlLayout);
            } else {
                $this->renderLayout();
            }
        }


        return $this->layout;
    }

    /**
     *
     * Renderiza uma view.
     *
     * @see renderLayout
     *
     * @param string $view
     * @return string
     */

    public function render($view) {
        if(isset($view)) {
            ob_start();
            include($view);
            $view = ob_get_contents();
            ob_end_clean();
        }

        return $view;
    }

    /**
     * Renderiza o layout
     */

    public function renderLayout() {
        $this->layout = $this->render($this->urlLayout);
    }

    /**
     * Não renderizar o Layout, i.e., não executar o código PHP nele contido
     */

    public function noRender() {
        $this->render = false;

        $this->run();
    }

    /**
     *
     * Devolve o layout definido
     *
     * @return string
     */

    public function getLayout() {
        return $this->layout;
    }

    /**
     *
     * Define um layout. É preferível utilizar loadLayout
     *
     * @param string $html
     * @return string
     */

    public function setLayout($html = null) {
        if(isset($html))
            $this->layout = $html;
        else
            $this->layout = $this->content;

        return $this->layout;
    }

    /**
     *
     * Devolve $content lido
     *
     * @return string
     */

    public function getContent() {
        return $this->content;
    }

    /**
     *
     * Define um content
     *
     * @param type $content
     */

    public function setcontent($content) {
        $this->content = $content;
    }

    /**
     *
     * Definir o ValueObject, afim de extrair posteriormente a sua composição.
     * O método aceita como argumento um objecto ou o nome da classe.
     *
     * @param object or string
     */

    public function setVO($vo) {
        if(is_object($vo))
            $this->vo = $vo;
        elseif(is_string($vo))
            $this->vo = new $vo;
        else
            trigger_error("ValueObject só pode ser passado enquanto objecto ou string!", E_USER_WARNING);

        // confirmar que o VO herda BaseVO
        if(!get_parent_class($this->vo) == "BaseVO")
            trigger_error("ValueObject " . get_class($this->vo) . " não herda BaseVO! Impossível prosseguir.", E_USER_ERROR);
    }

    /**
     *
     * Devolve o ValueObject definido
     *
     * @return object
     */

    public function getVO() {
        return $this->vo;
    }

    /**
     *
     * Extrair os atributos/propriedades do ValueObject, e prepará-los como TAG's correspondentes ao ficheiro html,
     * onde serão substituídas pelos valores pretendidos
     * Tratam-se de search keys.
     *
     * @return array
     */

    protected function prepareValueObjectProp() {
        if(!isset($this->vo))
            throw new InvalidArgumentException("Erro! Tem de definir o Value Object.", 0);

        $this->searchKey = array();

        $temp = $this->vo->getProp($this->vo);

        foreach($temp as $prop => $value)
            $this->searchKey[] = "{" . $prop . "}";
    }

    /**
     *
     * Setup do elemento, com base nos dados recebidos, e que se esperam estar num array, idealmente de objectos.
     * Conta-se posteriormente e com prepareValueObjectProp que esses dados sejam substituídos no element,
     * e que as chaves associadas aos valores a serem substituídos estejam necessariamente entre {}. Exemplo: <div nome="{NOME}" ...>{TITULO}</div>
     *
     * @param  array $data
     * @return string
     */

    public function htmlResultOnData($data) {
        // Nota 1:
        // searchKey é o array de valores a serem substituídos
        // $data são os valores a substituir
        // $this->content é a string onde os valores vão ser substituídos

        // Nota 2:
        // é case insensitive

        if(!is_array($data))
            $data = (array) $data;

        $html = str_ireplace($this->searchKey, $data, $this->content);

        return $html; // retorna o elemento html (<div>, <li>, <ol>) com as tags ({ID},{NAME},{XPTO}) subsituídas por valores
    }

    /**
     *
     * Devolve um conjunto de elementos, isto é, parcelas html em que vão ser substituídas tags por valores
     *
     * @param  array $data
     * @return type
     */

    public function listElements($data) {
        $html = "";
        $list = array();

        $this->prepareValueObjectProp(); // preparar as search keys com base no ValueObject

        foreach($data as $dt) {
            $list = $this->vo->getProp($dt);

            $html .= $this->htmlResultOnData($list);    // definir o element com base nessas propriedades
        }

        return $html;
    }

    /**
     *
     * Método que abrevia o str_ireplace, uma vez que se supõe $content como o alvo na substituição
     *
     * @param array $search
     * @param array $replace
     * @return string
     */

    public function replace($search, $replace) {
        $this->content = str_ireplace($search, $replace, $this->content);

        return $this->content;
    }

    /**
     *
     * Injectar, ou ir injectando os resultados obtidos (já como html) nos locais que se pretendem dentro do layout
     *
     * @param array $keys
     * @param array $data
     */

    public function injectIntoLayout($data, $keys) {
        if(isset($keys)) {
            $this->layout = str_ireplace($keys, $data, $this->layout); // vai acumulando os ficheiros
        } else {
            $this->bodyContent = $data;
            $this->layout      = str_ireplace("{BODY}", $data, $this->layout); // vai acumulando os ficheiros
        }
    }

    /**
     *
     * Métodos para imprimir o content
     */

    public function viewContent() {
        print($this->content);
    }

    /**
     *
     * Mostra o layout e os seus conteúdos
     */

    public function display() {
        if(empty($this->layout))
            print("Layout não definido!");
        else
            print($this->layout);
    }

    public function renderAndDisplay() {
        $this->renderLayout();
        $this->display();
    }

    /*********************************************************************************************/
    /* Métodos auxiliares                                                                        */
    /*********************************************************************************************/

    /**
     *
     * Verifica se existe uma classe.
     *
     * @param string $class
     * @return boolean
     */

    public function classExists($class) {
        return class_exists($class);
    }

}
