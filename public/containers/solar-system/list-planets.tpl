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

<script>
    $('.carousel-control.left').click(function() {
        $('#myCarousel').carousel('prev');
    });

    $('.carousel-control.right').click(function() {
        $('#myCarousel').carousel('next');
    });
    
    $('.item').eq(0).addClass('active');
</script>
