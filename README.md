# README #

### O que é RuthPHP? ###

* RuthPHP é uma framework PHP com base no padrão de desenho MVVM e orientada ao Modelo.
* 1.0.0

### Set Up ###

* ##Instalação##
	Para instalar copie para o terminal : ```git clone https://github.com/filipefernandes007/ruthphp.git``` 

	Agora vamos instalar as dependências via composer: ```php composer.phar install``` ou ```sudo composer install``` se já tiver instalado o composer. 
	(se não tiver instalado o composer vá a: https://getcomposer.org/doc/00-intro.md#downloading-the-composer-executable)

* ##Configuração##
	A configuração da aplicação é feita no ficheiro ``application/config/config.ini``, que deve ter o seguinte formato:
	
	```
	stage = development
	language = PT

	[development]
	adapter 		= mysql
	host 			= 127.0.0.1
	username 		= root
	password 		= root
	dbname 			= localdb_solar_system
	path 			= ruthphp
	layout 			= layout.tpl
	allwaysAllowRaw = true
	```
	
	Estas configurações devem ainda existir para [production].
	
	Em allwaysAllowRaw colocar a true se queremos visualizar os resultados de uma query no ecrã.
	
	Em ```application/config/php-settings``` definir as configurações de php.ini para debug ou outros.
	
* ##Template, Layout e Views##

	Uma view vai dar forma aos dados. Podemos criá-la em public/containers:

	````
	<div id="myCarousel" class="carousel slide">
	    <div class="carousel-inner">
	        <?php foreach($this->data as $planet): ?>
	            <div class="item planet-item">
	                <a href="/?view=ViewPlanet&args=<?= $planet->getId() ?>">
	                <img src="/images/<?= $planet->getImg() ?>" alt="">
	                <div class="container">
	                    <div class="carousel-caption lead">
	                        <h1><?= $planet->getName() ?></h1>
	                        <p class="lead"><?= $planet->getIntro() ?></p>
	                    </div>
	                </div>
	                </a>
	            </div>
	        <?php endforeach; ?>
	    </div>
	    <a class="left carousel-control" href="#" data-slide="prev">‹</a>
	    <a class="right carousel-control" href="#" data-slide="next">›</a>
	</div><!-- /.carousel -->
	```

	Para carregar a view devemos criar uma View em application/views:

	````
		...
		$this->name       = $data->name;
        $this->images     = $images;
        $this->satellites = $satellites;
        $this->view       = "views/planets.tpl"; # que view deve ser carregada

        // renderizar a view
        $this->renderView();

        // renderizar o layout
        return new LayoutPlanet(array('view' => $this->view, 'data' => $data));
	````

	Por seu turno, LayoutPlanet deve derivar de um template, que foi definido pelo programador como ATemplate, e guardado em application/templates:

	````
		class ATemplate extends Template {
        
	        protected function init($args = null) {
	            $mp  = new ModelPlanet();
	            
	            // OBRIGATÓRIO definir o ValueObject
	            $this->render->setVO("PlanetVO"); 
	            
	            // ler um ficheiro, que será um element
	            $this->render->load("elements/solar-system/dropdown-planets.html");
	            
	            // renderizar com base na leitura anterior, passando os dados
	            $planets = $this->render->listElements($mp->getAll());
	            
	            // ler o container 
	            $this->render->load("containers/solar-system/dropdown-planets.html");
	            $dp = $this->render->replace("{LIST}", $planets);

	            // injectar o html produzido na TAG correspondente do layout
	            $this->render->injectIntoLayout($dp,"{DROPDOWN-PLANETS}");
	        }
	        
	    }
	````

	Deste modo a view vai ser renderizada no template que por sua vez vai ser injectado no layout definido em config.ini.

* ##Models e VO's##

	Para que os dados sejam carregados devidamente nas views, devem ser criados models e vo's, que deverão habitar em application/models e application/models/vo.

	Os models devem derivar de ``Model``. Devem ter o seguinte formato:

	````
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

		    ...
	````

	Existem já métodos pré-definidos para ```getById(int), delete(SqlParam), update(SqlParam) e insert(SqlParam)```.
	
	
* ##Consola##
	Os ficheiros criados pela consola encontram-se na pasta tmp-files.

	``bin/console --write-vo [tabela da base de dados]`` 	: para escrever o ficheiro que representa o VO de uma tabela da base de dados

	``bin/console --write-models [tabela da base de dados]``: para escrever o ficheiro que representa o VO e o Model de uma tabela da base de dados

	``bin/console --write-models``						 	: para escrever os VO's e os Models que representam a base de dados

* ##Anotações##
	Nas anotações podemos restringir o acesso a um método através da segunite sintaxe:

	```

	/**
	 * [Session]
	 * [Roles (0,1)]
	 * [allowRaw(production)]
	 */
	public function getById($i) {
	 	return parent::getById($i);
	}

    ```

    Neste caso estamos a dizer que o método exige sessão de login, e que só pode ser acedido por utilizadores com "roles" 0 ou 1.

    Há ainda uma outra anotação, `` [allowRaw(production)] ``, que indica que é possível obter o resultado em json, xml ou 'raw'
    da chamada a ``getById``. Por exemplo estando autenticado (login efectuado com sucesso), podíamos obter os dados com:
    ``http://my-application/?call=ModelXYZ.getById&args=1&type=json``.

    As chamadas directas a métodos na query do url é sempre feita com ```<modelo>.<nome do método>```, ou seja, separados por ponto.
    Depois será preciso indicar a forma como vão ser visualizados os dados: xml ou json, o que é feito no parâmetro *args*.

    Se quiséssemos que esses dados fossem injectados numa view, bastaria indicar ``&view=ViewAll``, por exemplo. Neste caso, ficaria acessível na view através de ``$this->data``.

### Who do I talk to? ###

* Repo owner: filipefernandes007@gmail.com
