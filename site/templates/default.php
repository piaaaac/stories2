<?php
$ass = $kirby->url("assets");
?>

<?php snippet("header") ?>

<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <?= $page->text()->kt() ?>
    </div>
  </div>
</div>

<?php snippet("footer") ?>
