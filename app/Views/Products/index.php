<?php /** @var array<int, array<string, mixed>> $products */ ?>

<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
        <h2 style="margin:0;">Products</h2>
        <a class="btn" href="/products/create">Add Product</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>SKU</th>
                <th>Category</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?= (int) $product['id'] ?></td>
                <td><a href="/products/<?= (int) $product['id'] ?>"><?= htmlspecialchars((string) $product['name']) ?></a></td>
                <td><?= htmlspecialchars((string) $product['sku']) ?></td>
                <td><?= htmlspecialchars((string) $product['category_name']) ?></td>
                <td><?= (int) $product['quantity'] ?></td>
                <td>$<?= number_format((float) $product['price'], 2) ?></td>
                <td>
                    <a href="/products/<?= (int) $product['id'] ?>/edit">Edit</a>
                    |
                    <form method="post" action="/products/<?= (int) $product['id'] ?>/delete" style="display:inline;" onsubmit="return confirm('Delete this product?');">
                        <button type="submit" class="btn btn-danger" style="padding:0.2rem 0.5rem;font-size:0.8rem;">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
