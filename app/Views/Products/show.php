<?php 
/** @var array $product */   ?>


<div class="card">
    <?php if ($product === null): ?>
        <h2>Product not found</h2>
        <p><a href="/products">Back to products</a></p>
    <?php else: ?>
        <h2><?= htmlspecialchars((string) $product['name']) ?></h2>
        <p><strong>SKU:</strong> <?= htmlspecialchars((string) $product['sku']) ?></p>
        <p><strong>Category:</strong> <?= htmlspecialchars((string) $product['category_name']) ?></p>
        <p><strong>Quantity:</strong> <?= (int) $product['quantity'] ?></p>
        <p><strong>Price:</strong> $<?= number_format((float) $product['price'], 2) ?></p>
        <p><?= nl2br(htmlspecialchars((string) $product['description'])) ?></p>
        <p>
            <a class="btn btn-secondary" href="/products">Back</a>
            <a class="btn" href="/products/<?= (int) $product['id'] ?>/edit">Edit</a>
        </p>
    <?php endif; ?>
</div>
