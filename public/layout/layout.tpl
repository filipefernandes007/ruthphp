<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta http-equiv="Content-Type" content="text/html" charset="utf-8">
        <title>RuthPHP | A Small MVVM PHP Framework</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="RuthPHP, uma framework simples">
        <meta name="author" content="Filipe Fernandes">

        <!-- Le styles -->
        <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
        <link href="/css/main.css" rel="stylesheet">

        <!--<script src="http://code.jquery.com/jquery.js"></script>-->

        <script src="/js/lib/jquery/jquery.min.js"></script>
        <script src="/js/main.js"></script>

        <link rel="shortcut icon" href="/favicon/favicon.ico">

    </head>
    <body>

        <div class="navbar-wrapper">
            <!-- Wrap the .navbar in .container to center it within the absolutely positioned parent. -->
            <div class="container">
                <div class="navbar navbar-inverse">
                    <div class="navbar-inner">
                        <!-- Responsive Navbar Part 1: Button for triggering responsive navbar (not covered in tutorial). Include responsive CSS to utilize. -->
                        <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="brand" href="/"><i>RuthPHP</i> | A Small MVVM PHP Framework</a>
                        <!-- Responsive Navbar Part 2: Place all navbar contents you want collapsed withing .navbar-collapse.collapse. -->
                        <div class="nav-collapse collapse">
                            <ul class="nav">
                                <li><a class="about" href="#about">Sobre mim</a></li>
                                <li><a class="contact" href="#contact">Contacto</a></li>
                                <!-- Read about Bootstrap dropdowns at http://twitter.github.com/bootstrap/javascript.html#dropdowns -->
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Planetas<b class="caret"></b></a>
                                    {DROPDOWN-PLANETS}
                                </li>
                            </ul>
                        </div><!--/.nav-collapse -->
                    </div><!-- /.navbar-inner -->
                </div><!-- /.navbar -->

            </div> <!-- /.container -->
        </div><!-- /.navbar-wrapper -->

        {BODY}

        <div id="footer">
            <div class="container">
                <p class="muted credit">Example by Filipe Fernandes</p>
            </div>
        </div>

        <div id="theModal" class="modal hide fade">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="false">&times;</button>
                <h3 class="modal-head"></h3>
            </div>
            <div class="modal-body">
                <p class="modal-content"></p>
            </div>
            <div class="modal-footer">

            </div>
        </div>

        <script src="/bootstrap/js/bootstrap-collapse.js"></script>
        <script src="/bootstrap/js/bootstrap-transition.js"></script>
        <script src="/bootstrap/js/bootstrap-alert.js"></script>
        <script src="/bootstrap/js/bootstrap-modal.js"></script>
        <script src="/bootstrap/js/bootstrap-dropdown.js"></script>
        <script src="/bootstrap/js/bootstrap-scrollspy.js"></script>
        <script src="/bootstrap/js/bootstrap-tab.js"></script>
        <script src="/bootstrap/js/bootstrap-tooltip.js"></script>
        <script src="/bootstrap/js/bootstrap-popover.js"></script>
        <script src="/bootstrap/js/bootstrap-button.js"></script>
        <script src="/bootstrap/js/bootstrap-carousel.js"></script>
        <script src="/bootstrap/js/bootstrap-typeahead.js"></script>

        <script>

            !function ($) {
                $(function(){
                    $('#myCarousel').carousel();
                });
            }(window.jQuery)

            $("a.about").bind("click", function(e) {
                e.preventDefault();

                $(".modal-head").text("Sobre mim...");
                $(".modal-content").text("Filipe Fernandes, Web Developer");

                $("#theModal").modal();
            });

            $("a.contact").bind("click", function(e) {
                e.preventDefault();

                $(".modal-head").text("Contactos...");
                $(".modal-content").text("filipefernandes007@gmail.com");

                $("#theModal").modal();
            });

        </script>

        <script src="/bootstrap/js//holder.js"></script>

    </body>
</html>