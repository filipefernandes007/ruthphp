<?php foreach($this->images as $key => $value) : ?>

<div class="item">
    <img src="/images/<?php print($value[1]); ?>" alt="">
    <div class="container">
        <div class="carousel-caption">
            <h1><?= $this->name ?></h1>
            <p class="lead"><?= $value[2] ?></p>
        </div>
    </div>
</div>

<?php endforeach; ?>
