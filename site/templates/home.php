<?php
$stories = page("stories")->children()->listed()->limit(6);
$articles = page("articles")->children()->listed()->limit(6);
?>

<?php snippet("header") ?>

<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <?= $page->text()->kt() ?>
    </div>
  </div>
</div>

<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="position-relative">
        <div class="squares">
          <div class="squares-wrapper">
            <?php foreach ($stories as $story): ?>
              <div class="square">
                <div class="square-content-wrapper">
                  <div class="content story">
                    <a class="name" href="<?= $story->url() ?>"><?= $story->title() ?></a>
                    <span class="from-bot-l">Departure</span>
                    <span class="to-bot-r">Arrival</span>
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

<div class="full-w-btn">
  <a href="<?= page("stories")->url() ?>">
    <?php 
      $text = "    See all stories →    ";
      echo $text.$text.$text.$text.$text.$text.$text.$text.$text.$text.$text.$text.$text.$text.$text;
    ?>
  </a>
</div>
      
<div class="space-large"></div>

<div class="container-fluid">
  <div class="row">
    <div class="col-12 mb-5">
      Articles & Resources
    </div>
  </div>
</div>

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

<div class="full-w-btn">
  <a href="<?= page("articles")->url() ?>">
    <?php 
      $text = "    See all resources →    ";
      echo $text.$text.$text.$text.$text.$text.$text.$text.$text.$text.$text.$text.$text.$text.$text;
    ?>
  </a>
</div>

<div class="space-large"></div>

<section id="about">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-6">
        <?= $page->textAbout()->kt() ?>
      </div>
      <div class="col-lg-4 offset-lg-2">
        <div class="block-font-mon-400-nor-sm">
          <?= $page->textAboutAuthors()->kt() ?>
        </div>
      </div>
    </div>
  </div>
</section>

<?php snippet("footer") ?>
