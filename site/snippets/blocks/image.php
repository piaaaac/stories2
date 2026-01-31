<?php
$blockOptions = [
  "gridstyle" => $block->gridstyle()->value(),
];
$blockOptionsAttributes = [];
foreach ($blockOptions as $key => $op) {
  $blockOptionsAttributes[] = "data-" . $key . "='$op'";
}

/** @var \Kirby\Cms\Block $block */
$alt     = $block->alt();
$caption = $block->caption();
$crop    = $block->crop()->isTrue();
$link    = $block->link();
$ratio   = $block->ratio()->or('auto');
$src     = null;
$srcMob  = null;

if ($image = $block->image()->toFile()) {
  $alt = $alt->or($image->alt());
  $src = $image->url();
}
if ($imageSmall = $block->imageSmall()->toFile()) {
  $srcMob = $imageSmall->url();
}

$breakpoint = false;
if ($block->breakpoint()->isNotEmpty()) {
  $v = $block->breakpoint()->value();
  $breakpoint = $v !== 'none' ? $v : false;
}

?>
<?php if ($src):
  ob_start(); // Start output buffering
?>

  <?php if ($breakpoint && $srcMob): ?>
    <img class="<?= "d-none d-$breakpoint-block" ?>" src="<?= $src ?>" alt="<?= $alt->esc() ?>">
    <img class="<?= "d-$breakpoint-none" ?>" src="<?= $srcMob ?>" alt="<?= $alt->esc() ?>">
  <?php else: ?>
    <img src="<?= $src ?>" alt="<?= $alt->esc() ?>">
  <?php endif ?>

  <?php
  $imgMarkup = ob_get_clean(); // Capture and clean the buffer
  ?>

  <figure <?= Html::attr(['data-ratio' => $ratio, 'data-crop' => $crop], null, ' ') ?> class="block block-image" <?= implode(" ", $blockOptionsAttributes) ?>>
    <?php if ($link->isNotEmpty()): ?>
      <a href="<?= Str::esc($link->toUrl()) ?>"><?= $imgMarkup ?></a>
    <?php else: ?>
      <?= $imgMarkup ?>
    <?php endif ?>

    <?php if ($caption->isNotEmpty()): ?>
      <figcaption>
        <?= $caption->kt() ?>
      </figcaption>
    <?php endif ?>
  </figure>
<?php endif ?>