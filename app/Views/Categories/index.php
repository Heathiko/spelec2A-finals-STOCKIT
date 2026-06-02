<?php /** @var array $categories */?>

<div class="card">
    
    <div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
        <h2 style="margin:0;">Categories</h2>
        <a class="btn" href="/categories/create">Add Category</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>
                    ID
                </th>
                <th>
                    NAME
                </th>
                <th>
                    DESCRIPTION
                </th>
            </tr>
        </thead>
        

        <tbody>
            <?php foreach ($categories as $category):  ?>
                <tr>
                    <td>
                        <?= (int) $category['id'] ?>
                    </td>

                    <td>
                        <?= htmlspecialchars((string) $category['name'])?>
                    </td>

                    <td>
                        <?= htmlspecialchars((string ) $category['description'])?>
                    </td>
                </tr>

                <?php endforeach ?>
        </tbody>
    </table>
</div>