<?php
include 'conn.php'; // Include the database connection file

try {
    // SQL query to fetch 12 random product details
    $sql = "SELECT * FROM products WHERE product_status = 'available' ORDER BY RAND() LIMIT 8";
    $stmt = $pdo->query($sql);

    // Initialize a variable to hold the HTML markup for the products
    $products_html = '';

    // Loop through each row in the result set
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        // Construct HTML markup for each product
        $products_html .= '<div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item " id="productContainer">';
        $products_html .= '<div class="block2 '.$row['category'].'">';
        $products_html .= '<div class="block2-pic hov-img0">';
        // Set fixed dimensions for the image
        $products_html .= '<img src="' . $row['img1'] . '" alt="IMG-PRODUCT" style="width: 100%; height: 350px; object-fit: cover;">';
        $products_html .= '<a href="product-detail.php?id=' . $row['product_id'] . '" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04">Quick View</a>';
        $products_html .= '</div>';
        $products_html .= '<div class="block2-txt flex-w flex-t p-t-14">';
        $products_html .= '<div class="block2-txt-child1 flex-col-l">';
        $products_html .= '<a href="#" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">' . $row['product_name'] . '</a>';
        $products_html .= '<span class="stext-105 cl3">' . $row['product_price'] . '</span>';
        $products_html .= '</div>';
        $products_html .= '<div class="block2-txt-child2 flex-r p-t-3">';
        $products_html .= '<a href="#" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">';
        $products_html .= '<img class="icon-heart1 dis-block trans-04" src="images/icons/icon-heart-01.png" alt="ICON">';
        $products_html .= '<img class="icon-heart2 dis-block trans-04 ab-t-l" src="images/icons/icon-heart-02.png" alt="ICON">';
        $products_html .= '</a>';
        $products_html .= '</div>';
        $products_html .= '</div>';
        $products_html .= '</div>';
        $products_html .= '</div>';
    }

    // Echo the HTML markup for the products
    echo $products_html;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>