<?php

/**
 * 
 * @param $articles – kirby pages collection
 * 
 * */

?>

<?php foreach ($articles as $article): ?>
  <div class="article-prev">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div> <!-- top -->
            <p>
              <span class="tags"><?= implode(", ", $article->tags()->split()) ?></span>
              <span class="places"><?= implode(", ", $article->places()->split()) ?></span>
            </p>
            <p class="title-wrapper">
              <a class="title" href="<?= $article->url() ?>"><?= $article->title() ?></a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endforeach ?>    







<?php /*

OLD (squared)

<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="position-relative">
        <div class="squares">
          <div class="squares-wrapper">
            <?php foreach ($articles as $article): ?>
              <div class="square">
                <div class="square-content-wrapper">
                  <div class="content article">
                    <div> <!-- top -->
                      <p class="tags"><?= implode(", ", $article->tags()->split()) ?></p>
                      <p class="title-wrapper">
                        <a class="title" href="<?= $article->url() ?>"><?= $article->title() ?></a>
                      </p>
                    </div>
                    <div> <!-- bottom -->
                      <p class="places"><?= implode(", ", $article->places()->split()) ?></p>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach ?>    
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
*/ ?>