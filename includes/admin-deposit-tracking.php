<?php
include('./conn.php');

$stmt = $pdo->query("SELECT r.rent_id, CONCAT(u.fname, ' ', u.lname) AS user_name, p.product_name, 
                    r.deposit_amount, r.deposit_status, r.end_date
                    FROM rentals r
                    JOIN user u ON r.user_id = u.user_id
                    JOIN products p ON r.product_id = p.product_id
                    WHERE r.deposit_status != 'returned'");
$deposits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <h3 class="mb-4">Deposit Tracking</h3>
    <?php if(empty($deposits)): ?>
        <div class="alert alert-info">
            No deposits to track currently.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Rental ID</th>
                        <th>User</th>
                        <th>Product</th>
                        <th>Deposit Amount</th>
                        <th>Status</th>
                        <th>Due Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($deposits as $deposit): ?>
                    <tr>
                        <td>#<?= htmlspecialchars($deposit['rent_id']) ?></td>
                        <td><?= htmlspecialchars($deposit['user_name']) ?></td>
                        <td><?= htmlspecialchars($deposit['product_name']) ?></td>
                        <td>₹<?= number_format($deposit['deposit_amount'], 2) ?></td>
                        <td>
                            <span class="badge badge-<?= 
                                $deposit['deposit_status'] == 'collected' ? 'success' : 'warning' 
                            ?>">
                                <?= ucfirst(htmlspecialchars($deposit['deposit_status'])) ?>
                            </span>
                        </td>
                        <td><?= date('d M Y', strtotime($deposit['end_date'])) ?></td>
                        <td>
                            <?php if($deposit['deposit_status'] == 'pending'): ?>
                            <button class="btn btn-sm btn-primary" 
                                onclick="markDepositCollected(<?= $deposit['rent_id'] ?>)">
                                Mark Collected
                            </button>
                            <?php else: ?>
                            <button class="btn btn-sm btn-success" 
                                onclick="processDepositReturn(<?= $deposit['rent_id'] ?>)">
                                Process Return
                            </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>