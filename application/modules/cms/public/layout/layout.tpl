<!DOCTYPE html>
<html>
    <head>
        <title>CMS</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8">
        
        <!-- Le styles -->
        <link href="/modules/cms/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        
        <style type="text/css">
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
        </style>
        
        <!-- Le styles -->
        <link href="/modules/cms/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
        <link href="/modules/cms/css/main.css" rel="stylesheet">
        
        <script src="http://code.jquery.com/jquery.js"></script>
        <!--<script src="js/lib/jquery.json-2.4.min.js"></script>-->
        <script src="/modules/cms/bootstrap/js/bootstrap.min.js"></script>
        <script src="/js/main.js"></script>
        <script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha1.js"></script>
        
    </head>
    <body>
        
        
        <div class="container">
            <div class="navbar">
              <div class="navbar-inner">
                <div class="container" style="width: auto;">
                  <a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-inverse-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </a>
                  <a class="brand" href="#">Title</a>
                  <div class="nav-collapse collapse navbar-inverse-collapse">
                    <ul class="nav">
                      <li class="active"><a href="#">Home</a></li>
                      <li><a href="#">Link</a></li>
                      <li><a href="#">Link</a></li>
                      
                    </ul>
                      <form class="navbar-search pull-left" style="padding-top:5px;" action="">
                      <input type="text" class="search-query span2" placeholder="Search">
                    </form>
                    <ul class="nav pull-right">
                      <li><a href="#">Link</a></li>
                      <li class="divider-vertical"></li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                          <li><a href="#">Action</a></li>
                          <li><a href="#">Another action</a></li>
                          <li><a href="#">Something else here</a></li>
                          <li class="divider"></li>
                          <li><a href="#">Separated link</a></li>
                        </ul>
                      </li>
                    </ul>
                  </div><!-- /.nav-collapse -->
                </div>
              </div>
              </div><!-- /navbar-inner -->
            </div>
        
            {BODY}

            <footer>
                <p>&copy; RuthPHP 2013</p>
            </footer>
        </div> <!-- /container -->

    </body>
</html>