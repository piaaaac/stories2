<?php

/**
 * Menu snippet
 * 
 * @param tallMenu – bool whether to use a tall menu header
 *
 */
$tallMenu = $tallMenu ?? false;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <meta http-equiv="content-language" content="en">

  <?php snippet('seo/head'); ?>
  <!-- <?php snippet("favicon") ?> -->
  <?php snippet("load-scripts") ?>

  <script>
    window.siteUrl = '<?= $site->url() ?>';
    window.currentPage = '<?= $page->uid() ?>';
    window.currentTemplate = '<?= $page->template() ?>';
  </script>

  <?= js(['assets/js/common.js']) ?>

</head>

<body>
  <main class="<?= $tallMenu ? 'tall-menu' : '' ?>">