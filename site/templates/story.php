<?php
$ass = $kirby->url("assets");
?>

<?php snippet("header") ?>

<section>
  <div id="svg-container"></div>
</section>

<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <?= $page->text()->kt() ?>
    </div>
  </div>
</div>

<div id="tmp-label" style="position: absolute; bottom: 30px; left: 30px; font-size: 21px; color: black;"><?= $page->title() ?></div>

<script>
var s = new StorySvg(<?= $page->storyid()->value() ?>);
s.readDataAndInit();
</script>

<?php snippet("footer") ?>
