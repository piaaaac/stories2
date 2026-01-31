<?php
$stories = page("stories")->children()->listed();
?>

<?php snippet("header", ["tallMenu" => true]) ?>

<?php snippet("menu", ["subtitle" => "Stories crossing borders"]) ?>

<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <?= $page->text()->kt() ?>
    </div>
  </div>
</div>

<?php snippet("stories-prev-2", ["stories" => $stories, "style" => "small"]) ?>

<script>

</script>

<?php snippet("footer") ?>