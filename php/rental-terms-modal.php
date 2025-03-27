<?php
// This should be included inside the rental plan modal
?>
<div class="terms-section mt-4">
    <h6>Rental Terms & Conditions</h6>
    <div class="terms-content" style="max-height: 200px; overflow-y: auto; border: 1px solid #eee; padding: 15px; margin-bottom: 15px;">
        <ol class="small">
            <li class="mb-2">A refundable deposit of ₹500 is required for all rentals (to be paid physically during delivery)</li>
            <li class="mb-2">Rental period begins when product is marked as delivered in system</li>
            <li class="mb-2">Daily plan minimum rental period is 7 days</li>
            <li class="mb-2">Late returns will incur additional charges (₹50 per day)</li>
            <li class="mb-2">Damaged items may result in partial or complete deposit forfeiture</li>
            <li class="mb-2">Returns must be requested at least 24 hours before physical return</li>
            <li class="mb-2">Deposit refunds processed within 7 working days after return verification</li>
        </ol>
    </div>
    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="agreeTerms" required>
        <label class="form-check-label" for="agreeTerms">
            I agree to the terms and conditions
        </label>
    </div>
    <div class="d-grid gap-2">
        <button class="btn btn-dark" type="button" onclick="proceedToRent()" id="confirmRentBtn" disabled>
            Confirm Rental Request
        </button>
    </div>
</div>

<script>
document.getElementById('agreeTerms').addEventListener('change', function() {
    document.getElementById('confirmRentBtn').disabled = !this.checked;
});
</script>