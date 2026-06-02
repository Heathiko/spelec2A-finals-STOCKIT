<?php
$old = $old ?? [];
$formAction = $formAction ?? '/products';

/** @var array $categories */
?>


<form method="POST" action="<?= htmlspecialchars($formAction)?>">
    <label for="category_id">Category</label>
    <select name="category_id" id="category_id">
        <option value="">Select Category</option>

        <?php foreach ($categories as $category): ?>
            <option value="<?= (int) $category['id'] ?>"
                <?= (int) ($old['category_id'] ?? 0) === (int) $category['id']?>>
                <?= htmlspecialchars((string) $category['name'])?>
            </option>
        <?php  endforeach; ?>
    </select>   

    <label for="name">Name</label>
    <input type="text" name="name" id="name" value="<?= htmlspecialchars((string) ($old['name'] ?? '')) ?>">
    
    <label for="sku">SKU</label>
    <input type="text" name="sku" id="sku" value="<?= htmlspecialchars((string) ($old['sku'] ?? '')) ?>">

    <label for="description">Description</label>
    <textarea name="description" id="description" rows="3"><?= htmlspecialchars((string) ($old['description'] ?? '')) ?></textarea>

    <label for="quantity">Quantity</label>
    <input type="number" name="quantity" id="quantity" min="0" value="<?= htmlspecialchars((string) ($old['quantity'] ?? '0')) ?>">

    <label for="price">Price</label>
    <input type="number" name="price" id="price" min="0" step="0.01" value="<?= htmlspecialchars((string) ($old['price'] ?? '0')) ?>">
    <button type="submit" class="btn">Save Product</button>
    <a class="btn btn-secondary" href="/products">Cancel</a>
</form>
