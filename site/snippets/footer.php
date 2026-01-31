<?php

/** 
 * @param markup - boolean whether to output html markup
 */
?>

<section id="footer" class="mt-5">

  <div class="container-fluid texts mb-4">
    <div class="row">
      <div class="col-12 mb-3">
        <p>
          Moving Lines: Stories Crossing Borders is an independent project.
          <br />Want to contribute? <a href="">Get in touch.</a>
        </p>
      </div>
    </div>
  </div>

  <div class="full-w-btn" style="margin-bottom: 30px;">
    <a href="<?= page("stories")->url() ?>">
      <?php
      $text = "    See all stories →    ";
      echo $text . $text . $text . $text . $text . $text . $text . $text . $text . $text . $text . $text . $text . $text . $text;
      ?>
    </a>
  </div>

</section>

</main>

<?php snippet('seo/schemas'); ?>

</body>

</html>