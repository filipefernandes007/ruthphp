<!DOCTYPE html>
<html>
    <head>
        <title>ObjectBuilder</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8">
        <meta name="author" content="Filipe Fernandes @UAb">
        
        <!-- Bootstrap -->
        <!-- Le styles -->
        <link href="<?php print(PUBLIC_FOLDER); ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        
        <style type="text/css">
            body {
                padding-top: 40px;
                padding-bottom: 40px;
                background-color: #333;
            }

            .form-signin {
                max-width: 300px;
                padding: 19px 29px 29px;
                margin: 0 auto 20px;
                background-color: #fff;
                border: 1px solid #e5e5e5;
                -webkit-border-radius: 5px;
                -moz-border-radius: 5px;
                border-radius: 5px;
                -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
            }
            .form-signin .form-signin-heading,
            .form-signin .checkbox {
                margin-bottom: 10px;
            }
            .form-signin input[type="text"],
            .form-signin input[type="password"] {
                font-size: 16px;
                height: auto;
                margin-bottom: 15px;
                padding: 7px 9px;
            }


          /* Wrapper for page content to push down footer */
          #wrap {
            min-height: 100%;
            height: auto !important;
            height: 100%;
            /* Negative indent footer by it's height */
            margin: 0 auto -60px;
          }

          /* Set the fixed height of the footer here */
          #push,
          #footer {
            height: 60px;
          }
          #footer {
            background-color: #EEEEEE;
          }

          /* Lastly, apply responsive CSS fixes as necessary */
          @media (max-width: 767px) {
            #footer {
              margin-left: -20px;
              margin-right: -20px;
              padding-left: 20px;
              padding-right: 20px;
              
            }
          }

          .muted {
            text-align: right;
          }


        </style>
        
        <link href="<?php print(PUBLIC_FOLDER); ?>/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    </head>
    <body>
        <div id="wrap">
                
            <div class="container">
                
                <div class="hero-unit">
                        <div class="dropdown">
                            <button class="btn dropdown-toggle" data-toggle="dropdown">
                                Bases de Dados
                                <span class="caret"></span>
                            </button>
                        <!-- Link or button to toggle dropdown -->
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                            {DATABASES}
                        </ul>
                    </div>
                    
                    <h1>ObjectBuilder</h1><br/>
                    <div class="hero-unit">
                        <p>
                            ObjectBuilder cria a estrutura do seu <i>Web Project</i> com todos os ficheiros essenciais.<br/>
                            Para isso, basta escolher a base de dados, e introduzir as credenciais.<br/>
                            ObjectBuilder funciona apenas em desenvolvimento local.<br/>
                            Insira os dados que são pedidos para que seja criado o CRUD a partir da base de dados e os ficheiros base do projecto.
                        </p>
                        <p><button class="btn btn-large btn-primary" type="button" data-toggle="modal" data-target="#modalForm">Build</button></p>
                    </div>
                </div>

                <div id="modalForm" class="modal hide fade">
                    <div class="modal-header">
                         <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3>ObjectBuilder</h3>
                    </div>
                    <div class="modal-body">
                        <form class="form-build" action="build.php" method="POST">
                            <fieldset>
                                <legend>[ Build DataBase ]</legend>
                                    <h4>&nbsp;<i>"Preparar para Build!"</i></h4>
                                    <input data-toggle="tooltip" title="Nome do Projecto" type="text" class="input-block-level" placeholder="Projecto" name="projecto" required>
                                    <input data-toggle="tooltip" title="Defina a porta caso não seja 80" type="text" class="input-block-level" placeholder="Porta:80" name="porta" value="80">
                                    <input data-toggle="tooltip" title="O username para a base de dados. Caso não introduza nada assume root" type="text" class="input-block-level" placeholder="Username:root" name="username" value="root" required>
                                    <input data-toggle="tooltip" title="Nome da base de dados" type="text" class="input-block-level" placeholder="Base de Dados" name="bd" class="form-bd" required>
                                    <input data-toggle="tooltip" title="Opcional: nome da tabela a criar" type="text" class="input-block-level" placeholder="Tabela" name="table">
                                    <input type="password" class="input-block-level" placeholder="Password" name="password" value="">
                                    <button class="btn btn-large btn-primary" type="submit">Submeter</button>
                                </legend>
                            </fieldset>
                        </form>
                    </div>
                </div>  
            </div>

            <div id="push"></div>

            <div id="footer">
              <div class="container">
                <br/>
                <p class="muted credit">@Filipe Fernandes 2013</p>
              </div>
            </div>

        </div>

        <script src="http://code.jquery.com/jquery.js"></script>
        <script src="<?php print(PUBLIC_FOLDER); ?>/bootstrap/js/bootstrap.min.js"></script>
        
        <script>
            $(".form-build input").val("");
            $(".input-block-level").tooltip("hide");
            
      
            $(".choose-database").click(function(event) {
                //event.preventDefault();

                var db = $(this).attr("data-database");

                $("input[name='bd']").val(db);
            });
            
            $(".choose-table").click(function(event) {
                var db      = $(this).attr("data-database");
                var table   = $(this).attr("data-table");
                
                $("input[name='bd']").val(db);
                $("input[name='table']").val(table);
            });

        </script>
    </body>
</html>