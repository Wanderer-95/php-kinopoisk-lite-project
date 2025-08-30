<?php
/**
 * @var \App\Kernel\View\ViewInterface $view
 * @var \App\Kernel\Session\SessionInterface $session
 */
?>

<?php $view->component('start'); ?>
    <main>
        <div class="container">
            <h3 class="mt-3">Добавление нового жанра</h3>
            <hr>
        </div>
        <div class="container">
            <?php if ($session->has('success-add-category')) { ?>
                <div id="title" class="alert alert-success" role="alert">
                    <?php echo $session->getFlash('success-add-category') ?>
                </div>
            <?php } ?>
            <form action="/admin/categories/add" method="post" class="d-flex flex-column justify-content-center w-50 gap-2 mt-5 mb-5">
                <div class="row g-2">
                    <div class="col-md">
                        <div class="form-floating">
                            <input
                                type="text"
                                class="form-control <?php echo $session->has('title') ? 'is-invalid' : '' ?>"
                                id="title"
                                name="title"
                                placeholder="Категория"
                            >
                            <label for="name">Название</label>
                            <?php if ($session->has('title')) { ?>
                                <div id="title" class="invalid-feedback">
                                    <?php echo $session->getFlash('title')[0] ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="row g-2">
                    <button class="btn btn-primary">Добавить</button>
                </div>
            </form>
        </div>
    </main>

<?php $view->component('end'); ?>