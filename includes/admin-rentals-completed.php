<?php
include('./conn.php');

$stmt = $pdo->prepare("SELECT r.*, CONCAT(u.fname, ' ', u.lname) AS user_name, p.product_name 
                      FROM rentals r
                      JOIN user u ON r.user_id = u.user_id
                      JOIN products p ON r.product_id = p.product_id
                      WHERE r.rental_status = 'completed'");
$stmt->execute();
$rentals = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <h3 class="mb-4">Completed Rentals</h3>
    <?php if(empty($rentals)): ?>
        <div class="alert alert-info">
            No completed rentals found.
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
                        <th>Duration</th>
                        <th>Total Cost</th>
                        <th>Deposit Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rentals as $rental): 
                        $days = floor((strtotime($rental['end_date']) - strtotime($rental['start_date'])) / (60 * 60 * 24));
                        $cost = $days * ($rental['plan_type'] == 'daily' ? 20 : ($rental['plan_type'] == 'weekly' ? 130 : 200));
                    ?>
                    <tr>
                        <td>#<?= htmlspecialchars($rental['rent_id']) ?></td>
                        <td><?= htmlspecialchars($rental['user_name']) ?></td>
                        <td><?= htmlspecialchars($rental['product_name']) ?></td>
                        <td><?= ucfirst(htmlspecialchars($rental['plan_type'])) ?></td>
                        <td><?= $days ?> days</td>
                        <td>₹<?= number_format($cost, 2) ?></td>
                        <td><?= ucfirst(htmlspecialchars($rental['deposit_status'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>