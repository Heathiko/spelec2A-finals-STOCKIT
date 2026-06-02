<?php /** @var array $rows */ ?>
<?php /** @var int $totalUnits */ ?>
<?php /** @var float $totalValue */ ?>


<div class="card">
    <h2>STOCK LEVEL REPORT</h2>


    <div class="stats">
        <div class="stat">
            <span>Total units in storage</span>
            <strong><?= (int) $totalUnits ?></strong>
        </div>

        <div class="stat">
            <span>Total inventory value</span>
            <strong><?= number_format((float) $totalValue, 2) ?></strong>
        </div>

        <div class="stat">
            <span>Tracked products</span>
            <strong><?= count($rows) ?></strong>
        </div>
    </div>



    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Product</th>
                <th>SKU</th>
                <th>Category</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Value</th>
                <th>Status</th>
            </tr>
        </thead>


        <tbody>
            <?php foreach ($rows as $row): ?>
                <?php 
                    $status = strtolower((string) $row['stock_status']);
                    $badgeClass = match($status) {
                    'low'=>'badge-low',
                    'medium'=>'badge-medium',
                    default => 'badge-healthy',
                    }; ?>



                <tr>
                    <td><?= htmlspecialchars((int) $row['id']) ?></td>
                    <td><?= htmlspecialchars((string) $row['name']) ?></td>
                    <td><?= htmlspecialchars((string) $row['sku']) ?></td>
                    <td><?= htmlspecialchars((string) $row['category_name']) ?></td>
                    <td><?= (int) $row['quantity'] ?></td>
                    <td>$<?= number_format((float) $row['price'], 2) ?></td>
                    <td>$<?= number_format((float) $row['inventory_value'], 2) ?></td>
                    <td><span class="badge <?= $badgeClass ?>"><?= htmlspecialchars((string) $row['stock_status']) ?></span></td>
                </tr>

            <?php endforeach; ?>
        </tbody>
    </table>

</div>