<?php

/**
 * Menu snippet
 * 
 * @param subtitle – string subtitle under logo
 * @param showSwitch – bool show template switcher
 *
 */
$showSwitch = $showSwitch ?? false;

$items = [
  (object)[
    "text" => "Stories",
    "url" => page("stories")->url(),
    "uid" => page("stories")->uid(),
    "onclick" => "",
  ],
  // (object)[
  //   "text" => "Reading",
  //   "url" => page("articles")->url(),
  //   "uid" => page("articles")->uid(),
  //   "onclick" => "",
  // ],
  (object)[
    "text" => "About",
    "url" => "#",
    "uid" => "",
    "onclick" => "about();",
  ],
];
?>

<script>
  function about() {
    if (window.currentPage === "home") {
      $('html, body').animate({
        scrollTop: $("#about").offset().top
      });
    } else {
      window.location = "<?= $site->url() ?>#about";
    }
  }

  // $("#menu-header a.item").click(() => {
  // });
</script>

<nav id="menu-header" class="top">
  <div class="container-fluid">
    <div class="row">

      <div class="col-12 wrapper d-flex align-items-center justify-content-between">
        <div class="left">
          <h2 class="d-inline font-weight-600">
            <a href="<?= $site->url() ?>" class="no-u">
              Moving Lines
            </a>
            <?php if ($page->template()->name() === "story"): ?>
              <span> / </span>
              <span><?= $page->title() ?></span>
            <?php endif ?>
          </h2>
        </div>
        <div class="right">
          <div class="items d-none d-lg-block">
            <?php foreach ($items as $item):
              // add new key to object via https://stackoverflow.com/a/32581773/2501713
              $item->{"active"} = $page->uid() === $item->uid;
            ?>
              <a
                class="item <?= $item->active ? " active" : "" ?>"
                href="<?= $item->url ?>"
                onclick="<?= $item->onclick ? $item->onclick : "" ?>"><?= $item->text ?></a>
            <?php endforeach ?>
          </div>
          <div class="d-lg-none mt-1">
            <button class="hamburger hamburger--slider" type="button">
              <span class="hamburger-box">
                <span class="hamburger-inner"></span>
              </span>
            </button>
          </div>
        </div>
      </div>

      <?php if (isset($subtitle) && $subtitle != ""): ?>
        <div class="col-12 wrapper-subtitle d-flex align-items-center justify-content-between">
          <div class="font-sans-m mt-1">
            <?= $subtitle ?>
          </div>
          <?php if ($showSwitch): ?>
            <div class="template-switcher mt-1">
              <label>
                <div class="switch small">
                  <input type="checkbox" id="map-checkbox" onchange="handleSwitchChange(this);">
                  <span class="slider round"></span>
                </div>
                <span class="text font-sans-m">Map</span>
              </label>
            </div>
          <?php endif ?>
        </div>
      <?php endif ?>

    </div>
  </div>
</nav>

<div id="menu-xs">
  <a class="item" href="page.php">Menu-item</a>
  <a class="item" href="page.php">Menu-item</a>
  <a class="item" href="page.php">Menu-item</a>
  <a class="item" href="page.php">Menu-item</a>
</div>

<script>
  $("button.hamburger").click(function(e) {
    toggleMenu();
  });

  function toggleMenu(newState) {
    var isOpen = $("body").hasClass("menu-xs-open");
    if (newState === true || newState === false) {
      isOpen = !newState;
    }
    $("body").toggleClass("menu-xs-open", !isOpen);
    $("button.hamburger").toggleClass("is-active", !isOpen);
  }


  // ---------------------------------------------------------------------------
  // Handle map visibility
  // ---------------------------------------------------------------------------

  const currentMapVisibility = localStorage.getItem("mapVisible") === "true";
  const toggleEl = document.getElementById("map-checkbox").checked = currentMapVisibility;

  function handleSwitchChange(el) {
    // use el.checked ...
    console.log("Switch changed: " + el.checked);
    toggleMapStyle(el.checked);
  }
</script>