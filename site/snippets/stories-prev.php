<?php

/**
 * 
 * @param $stories – kirby pages collection
 * 
 * */

?>

<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="position-relative">
        <div class="squares">
          <div class="squares-wrapper">
            <?php foreach ($stories as $story): 
              if ($story->legs()->toStructure()->isEmpty()) { 
                continue; 
              }
              ?>
              <div class="square">
                <div class="square-content-wrapper">
                  <div class="svg-cont">
                    <?php if ($story->cachedSvg()->isNotEmpty()): ?>
                      <?= $story->cachedSvg()->value() ?>
                    <?php endif ?>
                    <!--  
                    <svg class="svg-story-prev" data-story-slug="<?= $story->slug() ?>">
                      <line x1="0" y1="80" x2="100" y2="20" stroke="black" />
                    </svg>
                    -->
                  </div>
                  <div class="content story">
                    <a class="name" href="<?= $story->url() ?>"><?= $story->title() ?></a>
                    <span class="from-bot-l"><?= $story->departurePlace()->value() ?></span>
                    <span class="to-bot-r"><?= $story->legs()->toStructure()->last()->place() ?></span>
                  </div>
                </div>
              </div>
            <?php endforeach ?>    
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<script>

const converter = new GeoJSON2SVG(/*options*/);
// const svgStrings = converter.convert(geojson,options);

var svgs = document.querySelectorAll("svg.svg-story-prev");

svgs.forEach((svg) => {
  console.log(svg)
});


</script>








