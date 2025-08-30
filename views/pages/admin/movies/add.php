<?php
/**
 * @var \App\Kernel\View\ViewInterface $view
 * @var \App\Kernel\Session\SessionInterface $session
 * @var array<\App\Models\Category> $categories
 */
?>

<?php $view->component('start'); ?>
    <main>
        <div class="container">
            <h3 class="mt-3">Добавление фильма</h3>
            <hr>
        </div>
        <div class="container">
            <form action="/admin/movies/add" method="post" enctype="multipart/form-data" class="d-flex flex-column justify-content-center w-50 gap-2 mt-5 mb-5">
                <div class="row g-2">
                    <div class="col-md">
                        <div class="form-floating">
                            <input
                                type="text"
                                class="form-control <?php echo $session->has('title') ? 'is-invalid' : '' ?>"
                                id="title"
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
                    <div class="col-md">
                        <div class="form-floating">
                            <textarea
                                style="height: 100px"
                                type="text"
                                class="form-control <?php echo $session->has('description') ? 'is-invalid' : '' ?>"
                                id="description"
                                name="description"
                            ></textarea>
                            <label for="name">Описание</label>
                            <?php if ($session->has('description')) { ?>
                                <div id="name" class="invalid-feedback">
                                    <?php echo $session->getFlash('description')[0] ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="row g-2">
                    <div class="col-md">
                        <div class="mb-3">
                            <label for="image" class="form-label">Изображение</label>
                            <input class="form-control" type="file" name="image" id="image">
                        </div>
                    </div>
                </div>
                <div class="row g-2">
                    <select class="form-select" name="category">
                        <option selected disabled>Жанр</option>
                        <?php foreach ($categories as $category) { ?>
                            <option value="<?php echo $category->getId() ?>">
                                <?php echo $category->getTitle() ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="row g-2">
                    <button class="btn btn-primary">Добавить</button>
                </div>
            </form>
        </div>
    </main>

<?php $view->component('end'); ?>