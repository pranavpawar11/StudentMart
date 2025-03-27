<?php
include('./conn.php');

$stmt = $pdo->prepare("SELECT r.*, CONCAT(u.fname, ' ', u.lname) AS user_name, p.product_name 
                      FROM rentals r
                      JOIN user u ON r.user_id = u.user_id
                      JOIN products p ON r.product_id = p.product_id
                      WHERE r.rental_status = 'return_initiated'");
$stmt->execute();
$rentals = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <h3 class="mb-4">Return Initiated Rentals</h3>
    <?php if(empty($rentals)): ?>
        <div class="alert alert-info">
            No return initiated rentals found.
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
                        <th>Return Date</th>
                        <th>Deposit Status</th>
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
                        <td><?= date('d M Y', strtotime($rental['end_date'])) ?></td>
                        <td><?= ucfirst(htmlspecialchars($rental['deposit_status'])) ?></td>
                        <td>
                            <button class="btn btn-sm btn-success" 
                                onclick="updateRentalStatus(<?= $rental['rent_id'] ?>, 'completed')">
                                Complete Return
                            </button>
                            <button class="btn btn-sm btn-primary" 
                                onclick="processDepositReturn(<?= $rental['rent_id'] ?>)">
                                Return Deposit
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>