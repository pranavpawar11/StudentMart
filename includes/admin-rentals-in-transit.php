<?php
include('./conn.php');

$stmt = $pdo->prepare("SELECT r.*, CONCAT(u.fname, ' ', u.lname) AS user_name, p.product_name 
                      FROM rentals r
                      JOIN user u ON r.user_id = u.user_id
                      JOIN products p ON r.product_id = p.product_id
                      WHERE r.rental_status = 'in_transit'");
$stmt->execute();
$rentals = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <h3 class="mb-4">Rentals In Transit</h3>
    <?php if(empty($rentals)): ?>
        <div class="alert alert-info">
            No rentals in transit found.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Rental ID</th>
                        <th>User</th>
                        <th>Product</th>
                        <th>Plan Type</th>
                        <th>Expected Delivery</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rentals as $rental): ?>
                    <tr>
                        <td>#<?= htmlspecialchars($rental['rent_id']) ?></td>
                        <td><?= htmlspecialchars($rental['user_name']) ?></td>
                        <td><?= htmlspecialchars($rental['product_name']) ?></td>
                        <td><?= ucfirst(htmlspecialchars($rental['plan_type'])) ?></td>
                        <td><?= date('d M Y', strtotime($rental['start_date'])) ?></td>
                        <td>
                            <button class="btn btn-sm btn-success" 
                                onclick="updateRentalStatus(<?= $rental['rent_id'] ?>, 'delivered')">
                                Mark Delivered
                            </button>
                            <button class="btn btn-sm btn-info" 
                                onclick="markDepositCollected(<?= $rental['rent_id'] ?>)">
                                Collect Deposit
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>