<?php
$stories = page("stories")->children()->listed()->limit(6);
$articles = page("articles")->children()->listed()->limit(3);
?>

<?php snippet("header") ?>

<div class="spacer py-4"></div>

<?php snippet("stories-prev", ["stories" => $stories]) ?>

<div class="container-fluid">
  <div class="row">
    <div class="col-12 mt-5 mb-4">
      <?= $page->text()->kt() ?>
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
      <h2>Articles & Resources</h2>
    </div>
  </div>
</div>

<?php snippet("articles-prev", ["articles" => $articles]) ?>

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
        <div class="block-font-san-300-nor-md">
          <?= $page->textAbout()->kt() ?>
        </div>
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
