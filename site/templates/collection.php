<?php
$stories = page("stories")->children()->listed();
?>

<?php snippet("header", ["tallMenu" => true]) ?>

<?php snippet("menu", ["subtitle" => "Stories crossing borders"]) ?>

<div class="space-large"></div>

<div class="container-fluid texts">
  <div class="row">
    <div class="col-12">
      <?php foreach ($page->children()->listed() as $child): ?>
        <?php foreach ($child->tags()->split(",") as $tag) : ?>
          <a href="<?= page("stories")->url() . "?tag=" . trim($tag) ?>" class="badge badge-secondary"><?= trim($tag) ?></a>
        <?php endforeach ?>

        <?php foreach ($child->places()->split(",") as $tag) : ?>
          <a href="<?= page("stories")->url() . "?tag=" . trim($tag) ?>" class="badge badge-secondary"><?= trim($tag) ?></a>
        <?php endforeach ?>

        <h1>
          <a href="<?= $child->url() ?>" class="color-black no-u">
            <?= $child->title()->html() ?>
          </a>
        </h1>

        <hr class="mb-5" />
      <?php endforeach ?>
    </div>
  </div>
</div>

<script>

</script>

<?php snippet("footer") ?>