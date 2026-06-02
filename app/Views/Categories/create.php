<?php 
$old = $old ?? [];
?>


<div class="card">

    <h2>Add Category</h2>


    <?php if(!empty($errors)): ?>
        <div class="errors">
            <ul>
                <?php foreach ($errors as $message):?>
                    <li><?= htmlspecialchars((string) $message) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php require __DIR__ . '/_form.php'; ?>
</div>