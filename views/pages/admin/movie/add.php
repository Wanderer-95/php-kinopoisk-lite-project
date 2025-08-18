<?php
/**
 * @var \Kernel\View\View $view
 * @var \Kernel\Session\Session $session
 */
$view->component('start');
?>

<form action="/admin/movie/add" method="POST" class="max-w-md mx-auto bg-white p-6 rounded-2xl shadow-md space-y-4">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
        <input
                type="text"
                name="name"
                id="name"
                class="w-full rounded-lg border <?= $session->has('name') ? 'border-red-500' : 'border-gray-300' ?> px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
        >
        <?php if ($session->has('name')): ?>
            <?php foreach ($session->getFlash('name') as $name): ?>
                <p class="mt-2 text-sm text-red-600">
                    <?php echo htmlspecialchars($name); ?>
                </p>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <button
            type="submit"
            class="cursor-pointer w-full bg-indigo-600 text-white py-2 px-4 rounded-lg shadow hover:bg-indigo-700 transition"
    >
        Submit
    </button>
</form>

<?php
$view->component('end');
?>
