<?php
$ass = $kirby->url("assets") ."/images";
?>

<nav id="menu-header" class="top">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 wrapper d-flex align-items-center justify-content-between">

        <div class="left">
          <a href="<?= $site->url() ?>" class="d-flex align-items-center no-u">
            <!-- <img src="<?= $ass ?>/logo.svg" alt="Logo"> -->
            <span>Project title</span>
          </a>
        </div>

        <div class="right">
          <div class="items d-none d-lg-block">
            <a class="item" href="page.php">Menu-item</a>
            <a class="item active" href="page.php">Menu-item</a>
            <a class="item" href="page.php">Menu-item</a>
            <a class="item" href="page.php">Menu-item</a>
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
$("button.hamburger").click( function (e) {
  toggleMenu();
});

function toggleMenu (newState) {
  var isOpen = $("body").hasClass("menu-xs-open");
  if (newState === true || newState === false) {
    isOpen = !newState;
  }
  $("body").toggleClass("menu-xs-open", !isOpen);
  $("button.hamburger").toggleClass("is-active", !isOpen);
}
</script>












