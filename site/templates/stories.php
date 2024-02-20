<?php
$stories = page("stories")->children()->listed();
?>

<?php snippet("header") ?>

<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <?= $page->text()->kt() ?>
    </div>
  </div>
</div>

<?php snippet("stories-prev", ["stories" => $stories]) ?>

<script>
  
</script>

<?php snippet("footer") ?>
