<?php

/**
 * 
 * @param $stories – kirby pages collection
 * 
 * */

?>

<div class="container-fluid">
  <div class="row">
    <?php foreach ($stories as $story):
      if ($story->legs()->toStructure()->isEmpty()) {
        continue;
      }
      $url = $story->url();
      $name = $story->title();
      $age = $story->age()->isNotEmpty() ? $story->age() : "";
      $title = "$name, $age";
      $from = getFromPlace($story);
      $fromCountry = getFromCountry($story);
      $to = getToPlace($story);
      $toCountry = getToCountry($story);
      $subtitle = "$from, $fromCountry → $to, $toCountry";
    ?>
      <div class="col-sm-6 col-lg-4">
        <a href="<?= $url ?>" class="d-block bg-white-hover">
          <div class="svg-square-container p-3 mb-3 mt-1">
            <?php if ($story->cachedSvg()->isNotEmpty()): ?>
              <?= $story->cachedSvg()->value() ?>
            <?php endif ?>
            <div class="absolute-story-info">
              <div class="font-ser-l font-w-600 mb-1 outlined-mintcream"><?= $title ?></div>
              <div class="font-sans-m color-grey"><?= $subtitle ?></div>
            </div>
          </div>
        </a>
      </div>
    <?php endforeach ?>
  </div>
</div>