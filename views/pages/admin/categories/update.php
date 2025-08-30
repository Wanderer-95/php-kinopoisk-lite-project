<?php
/**
 * @var \App\Kernel\View\ViewInterface $view
 * @var \App\Kernel\Session\SessionInterface $session
 * @var \App\Models\Category $category
 */
?>

<?php $view->component('start'); ?>
    <main>
        <div class="container">
            <h3 class="mt-3">Изменить - <?php echo $category->getTitle() ?></h3>
            <hr>
        </div>
        <div class="container">
            <?php if ($session->has('category-update')) { ?>
                <div id="category" class="alert alert-primary" role="alert">
                    <?php echo $session->getFlash('category-update') ?>
                </div>
            <?php } ?>
            <form action="/admin/categories/update" method="post" class="d-flex flex-column justify-content-center w-50 gap-2 mt-5 mb-5">
                <input type="hidden" name="id" value="<?php echo $category->getId() ?>">
                <div class="row g-2">
                    <div class="col-md">
                        <div class="form-floating">
                            <input
                                type="text"
                                class="form-control <?php echo $session->has('title') ? 'is-invalid' : '' ?>"
                                id="title"
                                value="<?php echo $category->getTitle() ?>"
                                name="title"
                            >
                            <label for="title">Название</label>
                            <?php if ($session->has('title')) { ?>
                                <div id="title" class="invalid-feedback">
                                    <?php echo $session->getFlash('title')[0] ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="row g-2">
                    <button class="btn btn-success">Сохранить</button>
                </div>
            </form>
        </div>
    </main>

<?php $view->component('end'); ?>