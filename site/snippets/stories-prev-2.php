<?php

/**
 * 
 * @param $stories – kirby pages collection
 * @param $style - "large" | "small" (default: "large")
 * 
 * */

$style = $style ?? "large";
?>

<div class="container-fluid <?= $style === "small" ? "texts" : "" ?>">
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

      <?php if ($style === "large"): ?>

        <div class="col-sm-6 col-xl-4">
          <a href="<?= $url ?>" class="d-block">
            <div class="svg-square-container p-5 mb-3 mt-1">
              <?php if ($story->cachedSvg()->isNotEmpty()): ?>
                <?= $story->cachedSvg()->value() ?>
              <?php endif ?>

              <?php /*  TOP-BOTTOM VERSION
            <div class="absolute-story-info">
              <div class="font-ser-l font-w-600 mb-1 outlined-page_bg_color"><?= $title ?></div>
              <div class="font-sans-m color-grey"><?= $subtitle ?></div>
            </div>
            */ ?>

              <?php /*  ALL BOTTOM VERSION
            */ ?>
              <div class="absolute-story-info">
                <div></div>
                <div>
                  <div class="font-ser-l font-w-600 mb-1 outlined-page_bg_color"><?= $title ?></div>
                  <div class="font-sans-m color-grey outlined-page_bg_color"><?= $subtitle ?></div>
                </div>
              </div>

            </div>
          </a>
        </div>

      <?php elseif ($style === "small"): ?>

        <div class="col-6 col-sm-3 col-xl-2">
          <a href="<?= $url ?>" class="d-block">
            <div class="svg-square-container p-4 p-sm-3 p-md-4 mb-3 mt-1">
              <?php if ($story->cachedSvg()->isNotEmpty()): ?>
                <?= $story->cachedSvg()->value() ?>
              <?php endif ?>
            </div>
          </a>
        </div>
        <div class="col-6 col-sm-9 col-xl-10 align-self-center">
          <a href="<?= $url ?>" class="d-block color-black no-u mb-4">
            <div class="font-ser-l font-w-600 mb-1 outlined-page_bg_color"><?= $title ?></div>
            <div class="font-sans-m color-grey outlined-page_bg_color"><?= $subtitle ?></div>
          </a>
        </div>

      <?php endif ?>

    <?php endforeach ?>
  </div>
</div>