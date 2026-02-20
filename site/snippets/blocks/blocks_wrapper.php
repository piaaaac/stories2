<?php

$blockOptions = [
  "bordertop"       => $block->bordertop()->toBool() ? "yes" : "no",
  "borderbottom"    => $block->borderbottom()->toBool() ? "yes" : "no",
  "paddingtop"      => $block->paddingtop()->value(),
  "paddingbottom"   => $block->paddingbottom()->value(),
  "bgcolor"         => $block->bgcolor()->value(),
  "grid"            => $block->grid()->value(),
];
$blockOptionsAttributes = [];
foreach ($blockOptions as $key => $op) {
  $blockOptionsAttributes[] = "data-" . $key . "='$op'";
}

$container = $block->grid()->value();
if ($container === "none") {
  $containerOpening = "";
  $containerClosing = "";
} else {
  $containerClass = "container-fluid";
  if ($container === "containertexts") {
    $containerClass = "container-fluid texts";
  }
  $containerOpening = "<div class='$containerClass'><div class='row'><div class='col-12'>";
  $containerClosing = "</div></div></div>";
}
?>

<section class="block blocks_wrapper" <?= implode(" ", $blockOptionsAttributes) ?>>
  <?= $containerOpening ?>
  <div class="blocks"><?= $block->contentBlocks()->toBlocks() ?></div>
  <?= $containerClosing ?>
</section>