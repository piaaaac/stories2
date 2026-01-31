<?php
$fieldId = "block-acc-" . pseudoRandomBytes();

$blockOptions = [
  "paddingtop"      => $block->paddingtop()->value(),
  "paddingbottom"   => $block->paddingbottom()->value(),
];
$blockOptionsAttributes = [];
foreach ($blockOptions as $key => $op) {
  $blockOptionsAttributes[] = "data-" . $key . "='$op'";
}

?>

<div class="block block-accordion" id="<?= $fieldId ?>" <?= implode(" ", $blockOptionsAttributes) ?>>
  <div class=" container-fluid">
    <div class="row">
      <div class="col-12">

        <hr />

        <?php foreach ($block->faqItems()->toStructure() as $item) : ?>

          <div class="acc-item kt">
            <div class="acc-item-header">
              <div class="text"><?= $item->itemHeader()->kt() ?></div>
              <div class="arrow">&times;</div>
            </div>
            <div class="acc-item-content"><?= $item->itemContent()->kt() ?></div>
          </div>

          <hr />

        <?php endforeach ?>

      </div>
    </div>
  </div>
</div>

<script>
  var fieldId = "<?= $fieldId ?>";
  var acc = new Accordion(fieldId);
  console.log(acc);
</script>