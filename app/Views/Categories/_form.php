<?php
$old = $old ?? [];
$formAction = $formAction ?? '/categories';
$errors = $errors ?? [];
?>

<?php if (!empty($errors)): ?>
    <div class="errors">
        <ul>
            <?php foreach ($errors as $message): ?>
                <li><?= htmlspecialchars((string) $message) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="<?= htmlspecialchars($formAction) ?>" novalidate>
    <label for="name">Name</label>
    <input type="text" name="name" id="name" value="<?= htmlspecialchars((string) ($old['name'] ?? '')) ?>">

    <label for="description">Description</label>
    <textarea name="description" id="description" rows="3"><?= htmlspecialchars((string) ($old['description'] ?? '')) ?></textarea>
    
    <button type="submit" class="btn">Save Category</button>
    <a class="btn btn-secondary" href="/categories">Cancel</a>
</form>