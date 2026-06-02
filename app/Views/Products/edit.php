<?php /** @var array $product  */ ?>

<div class="card">
    <h2>Edit Product</h2>

    <?php if (!empty($errors)): ?>
        <div class="errors">
            <ul>
                <?php foreach ($errors as $message): ?>
                    <li><?= htmlspecialchars((string) $message) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php
    $old = $product;
    $formAction = '/products/' . (int) $product['id'] . '/edit';
    require __DIR__ . '/_form.php';
    ?>
</div>
