<?php

$blockOptions = [
  "bordertop"       => $block->bordertop()->toBool() ? "yes" : "no",
  "borderbottom"    => $block->borderbottom()->toBool() ? "yes" : "no",
  "paddingtop"      => $block->paddingtop()->value(),
  "paddingbottom"   => $block->paddingbottom()->value(),
  "bgcolor"         => $block->bgcolor()->value(),
];
$blockOptionsAttributes = [];
foreach ($blockOptions as $key => $op) {
  $blockOptionsAttributes[] = "data-" . $key . "='$op'";
}
?>

<section class="block blocks_wrapper" <?= implode(" ", $blockOptionsAttributes) ?>>
  <div class="blocks"><?= $block->contentBlocks()->toBlocks() ?></div>
</section>