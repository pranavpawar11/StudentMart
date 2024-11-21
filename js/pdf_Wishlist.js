function toggleLike(pdfId) {
    console.log("Toggle PDF like button clicked");
    const likeIcon = document.getElementById(`like-icon-${pdfId}`);
    const isLiked = likeIcon.src.includes('icon-heart-02.png');

    if (isLiked) {
        removePdfFromWishlist(pdfId);
    } else {
        addPdfToWishlist(pdfId);
    }
}

function addPdfToWishlist(pdfId) {
    console.log("PDF added to wishlist: ", pdfId);
    fetch('php/add_pdf_to_wishlist.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ pdfId: pdfId }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`like-icon-${pdfId}`).src = 'images/icons/icon-heart-02.png'; // Liked icon
                Swal.fire("Success", "PDF added to wishlist!", "success");
            } else {
                console.error('Error:', data.message);
                Swal.fire("Error", data.message, "error");
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire("Error", "An error occurred while adding to wishlist.", "error");
        });
}


function removePdfFromWishlist(pdfId) {
    console.log("PDF removed from wishlist");
    fetch('php/remove_pdf_from_wishlist.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ pdfId: pdfId }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`like-icon-${pdfId}`).remove();
                Swal.fire("Success", "PDF removed from wishlist!", "success");
            } else {
                console.error('Error:', data.message);
                Swal.fire("Error", data.message, "error");
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire("Error", "An error occurred while removing from wishlist.", "error");
        });
}


function fetchPdfWishlist() {
    fetch('php/fetch_pdf_wishlist.php')
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => { throw new Error(text); });
            }
            return response.json();
        })
        .then(data => {
            const wishlistContainer = document.querySelector('.header-cart-wrapitem');
            const totalContainer = document.querySelector('.header-cart-total');

            if (data.length > 0) {
                let total = 0;

                // Create a document fragment to improve performance
                const fragment = document.createDocumentFragment();

                data.forEach(item => {
                    const { pdf_id, pdf_name, pdf_path, price, img1, upload_date } = item;
                    total += parseFloat(price);

                    // Create wishlist item element for PDF
                    const wishlistItem = document.createElement('li');
                    wishlistItem.classList.add('header-cart-item', 'flex-w', 'flex-t', 'm-b-12');
                    wishlistItem.id = `wishlist-item-${pdf_id}`;

                    wishlistItem.innerHTML = `
                        <div class="header-cart-item-img">
                            <a href="view_pdf.php?id=${pdf_id}">
                                <img src="${img1}" alt="${pdf_name}">
                            </a>
                        </div>
                        <div class="header-cart-item-details p-t-8">
                            <div class="header-cart-item-name m-b-5">
                                <a href="pdf-detail.php?id=${pdf_id}" class="hov-cl1 trans-04">${pdf_name}</a>
                            </div>
                            <div class="header-cart-item-info">
                                <span>Price: ₹${price}</span>
                            </div>
                        </div>
                        <div class="header-cart-item-actions">
                            <div class="wishlist-item-info">
                                <a href="pdf-detail.php?id=${pdf_id}" class="header-cart-item-info-btn">
                                    <i class="far fa-eye"></i> <!-- Icon for displaying PDF info -->
                                </a>
                            </div>
                            <div class="wishlist-item-delete">
                                <button class="header-cart-item-delete" onclick="removePdfFromWishlist(${pdf_id})">
                                    <i class="far fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    `;

                    fragment.appendChild(wishlistItem);
                });

                wishlistContainer.innerHTML = '';
                wishlistContainer.appendChild(fragment);

                // Update total
                // totalContainer.textContent = `Total: ₹${total.toFixed(2)}`;
            } else {
                // Display message if wishlist is empty
                wishlistContainer.innerHTML = '<li class="header-cart-item flex-w flex-t m-b-12">Your wishlist is empty.</li>';
                // totalContainer.textContent = 'Total: ₹0.00';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.querySelector('.header-cart-wrapitem').innerHTML = `<li class="header-cart-item flex-w flex-t m-b-12">Error loading wishlist: ${error.message}</li>`;
            document.querySelector('.header-cart-total').innerHTML = '';
        });
}
