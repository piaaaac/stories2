<?php
$blockOptions = [
  "paddingtop"      => $block->paddingtop()->value(),
  "paddingbottom"   => $block->paddingbottom()->value(),
];
$blockOptionsAttributes = [];
foreach ($blockOptions as $key => $op) {
  $blockOptionsAttributes[] = "data-" . $key . "='$op'";
}

/** @var \Kirby\Cms\Block $block */ ?>

<div class="block block-text" <?= implode(" ", $blockOptionsAttributes) ?>>
  <div class="container-fluid texts">
    <div class="row">
      <div class="col-xl-6">
        <?= $block->text() ?>
      </div>
    </div>
  </div>
</div>