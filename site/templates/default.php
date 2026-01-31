<?php
$hideDefaultTitle = $page->hideDefaultTitle()->bool();
?>

<?php snippet("header", ["tallMenu" => false]) ?>

<?php snippet("menu", ["subtitle" => ""]) ?>

<div class="space-large"></div>

<!-- Opening image -->
<?php if ($image = $page->cover()->toFile()):
  $focus = $image->focus();
  // kill($focus);
?>
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 mb-5">
        <img class="cover-image" src="<?= $image->url() ?>" alt="<?= $image->alt() ?>" style="
        object-fit: cover;
        object-position: <?= $focus->focus() ?>;
        ">
      </div>
    </div>
  </div>
<?php endif ?>


<!-- Title with breadcrumb and tags -->
<div class="container-fluid texts">
  <div class="row">
    <div class="col">

      <?php if ($parent = $page->parent()): ?>
        <?php if ($parent->intendedTemplate()->name() === 'collection'):
          $txt = $parent->breadcrumbText()->isNotEmpty() ? $parent->breadcrumbText()->html() : $parent->title()->html();
        ?>
          <a href="<?= $parent->url() ?>" class="badge badge-primary no-u">
            &larr; <?= $txt ?>
          </a>
        <?php endif ?>
      <?php endif ?>

      <?php foreach ($page->tags()->split(",") as $tag) : ?>
        <a href="<?= page("stories")->url() . "?tag=" . trim($tag) ?>" class="badge badge-secondary"><?= trim($tag) ?></a>
      <?php endforeach ?>

      <?php foreach ($page->places()->split(",") as $tag) : ?>
        <a href="<?= page("stories")->url() . "?tag=" . trim($tag) ?>" class="badge badge-secondary"><?= trim($tag) ?></a>
      <?php endforeach ?>
      <h1 class="mt-2 mb-5">
        <?= $page->title()->html() ?>
      </h1>
    </div>
  </div>
</div>

<div class="blocks"><?= $page->blocks()->toBlocks() ?></div>

<div class="container-fluid texts mt-3">
  <div class="row">
    <div class="col-12 col-xl-6">
      <span class="
      font-sans-xs font-weight-600
      color-bg_color_dark
      ">
        &#9679;
        <!-- &#9675;&nbsp; -->
        LAST UPDATE <?= $page->updated()->toDate('d/m/y'); ?></span>
    </div>
  </div>
</div>

<div class="space-large"></div>

<?php snippet("footer") ?>