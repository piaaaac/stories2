<?php

/** 
 * @param markup - boolean whether to output html markup
 */
?>

<?php if (!isset($markup) || $markup === true): ?>
  <section id="footer" class="mt-5">
    <div class="container-fluid">
      <div class="row">

        <div class="col-12 my-3">
          <hr />
        </div>
        <div class="col-lg-4">
          <p>
            Do you want to share a story?
          </p>
        </div>
        <div class="col-lg-4 offset-lg-4">
          <div class="block-font-sans-s">
            <p>
              Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris sed turpis sed ipsum vehicula bibendum imperdiet in orci. In vel vestibulum nisi. Donec sagittis nunc id erat aliquam blandit. <a class="green" href="mailto:test@mail.com">Write us.</a>
            </p>
          </div>
        </div>

        <div class="col-12 space-large"></div>

        <div class="col-12 my-3">
          <hr />
        </div>
        <div class="col-lg-4">
          <div class="block-font-sans-s">
            <p>
              Sincerely thanks to everyone who contributed lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris sed turpis sed ipsum vehicula bibendum imperdiet in orci. In vel vestibulum nisi. Donec sagittis nunc id erat aliquam blandit. Ut in sapien magna.
            </p>
            <p>Running on <a href="https://getkirby.com" target="_blank">Kirby</a>.</p>
          </div>
        </div>
        <div class="col-lg-4 offset-lg-4">
          <div class="block-font-sans-s">
            <a class="font-sans-s no-u text-uppercase mr-3" href="<?= page("privacy-policy")->url() ?>">Privacy</a>
            <a class="font-sans-s no-u text-uppercase mr-3" href="<?= $site->panelUrl() ?>">Login</a>
            <span class="font-sans-s text-uppercase mr-0">Released under CC 4.0?</span>
          </div>
        </div>

        <div class="col-12 py-5 spacer"></div>
      </div>
    </div>
  </section>

<?php endif; ?>
</main>

<?php snippet('seo/schemas'); ?>

</body>

</html>