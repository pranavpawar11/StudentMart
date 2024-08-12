<?php
// Assume we have a function to fetch requests from the database
$requests = fetchRequests();
?>

<div class="container">
    <div class="row">
        <?php foreach ($requests as $request): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <img src="<?= $request['image_url']; ?>" alt="Product Image">
                        <div class="details">
                            <h5><?= $request['title']; ?></h5>
                            <p><?= $request['price']; ?> USD</p>
                        </div>
                        <div class="actions">
                            <button class="btn btn-primary" onclick="toggleCardDetails(<?= $request['id']; ?>)">Show Details</button>
                            <button id="button_<?= $request['id']; ?>" class="btn <?= $request['status'] === 'available' ? 'btn-success' : 'btn-danger'; ?>"
                                onclick="updateProductStatus(<?= $request['id']; ?>, '<?= $request['status'] === 'available' ? 'sold' : 'available'; ?>')">
                                <?= $request['status'] === 'available' ? 'Make Unavailable' : 'Make Available'; ?>
                            </button>
                        </div>
                    </div>
                    <div id="details_<?= $request['id']; ?>" class="card-details">
                        <p><strong>Description:</strong> <?= $request['description']; ?></p>
                        <p><strong>Seller:</strong> <?= $request['seller']; ?></p>
                        <p><strong>Contact:</strong> <?= $request['contact']; ?></p>
                        <button class="btn btn-secondary" onclick="toggleCardDetails(<?= $request['id']; ?>)">Close</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
