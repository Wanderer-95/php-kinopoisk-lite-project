<?php

/**
 * @var \Kernel\View\ViewInterface $view
 * @var \Kernel\Session\SessionInterface $session
 */
$view->component('start');
?>

    <main>
        <div class="container">
            <h2><?php echo $session->getFlash('message'); ?></h2>
            <h3 class="mt-3">Новинки</h3>
            <hr>
        </div>
    </main>

<?php
$view->component('end');
