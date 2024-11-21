    
// // Call the function to create the modal element
document.addEventListener("DOMContentLoaded", function () {
    let allProducts = []; // Array to store all products
    let displayedProducts = []; // Array to store currently displayed products
    const productsPerLoad = 8; // Number of products to load each time
    let offset = 0; // Offset to track products loaded

    // Function to fetch all products
    async function fetchAllProducts() {
        try {
            const response = await fetch('fetch_products.php');
            const data = await response.json();
            allProducts = data;
            console.log(allProducts);
            loadMoreProducts(); // Load initial set of products
        } catch (error) {
            console.error('Error fetching products:', error);
        }
    }

    // Function to display products
    function displayProducts(products) {
        const productContainer = document.getElementById('productContainer');
        productContainer.innerHTML = ''; // Clear current products
        products.forEach(product => {
            const categoryClass = product.category.toLowerCase().replace(/\s+/g, '-');
            const productHtml = `
                <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item ${categoryClass}" data-price="${product.product_price}">
                    <div class="block2">
                        <div class="block2-pic hov-img0">
                            <img src="${product.img1}" alt="IMG-PRODUCT" style="width: 100%; height: 350px; object-fit: cover;">
                            <a href="product-detail.php?id=${product.product_id}" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04">Quick View</a>
                        </div>
                        <div class="block2-txt flex-w flex-t p-t-14">
                            <div class="block2-txt-child1 flex-col-l">
                                <a href="#" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">${product.product_name}</a>
                                <span class="stext-105 cl3">${product.product_price} ₹</span>
                            </div>
                            <div class="block2-txt-child2 flex-r p-t-3">
                                <a href="#" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">
                                    <img class="icon-heart1 dis-block trans-04" src="${product.wishlist_icon}" alt="ICON" id="like-icon-${product.product_id}" onclick="toggleLike(${product.product_id})">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            productContainer.insertAdjacentHTML('beforeend', productHtml);
        });
    }

    // Function to load more products
    function loadMoreProducts() {
        const newProducts = allProducts.slice(offset, offset + productsPerLoad);
        if (newProducts.length > 0) {
            displayedProducts = displayedProducts.concat(newProducts);
            displayProducts(displayedProducts);
            offset += productsPerLoad;
        } else {
            alert('No more products to load.');
        }
    }

    // Function to filter displayed products
    function filterDisplayedProducts(searchTerm, category = '*', price = '*', place = '*') {
        let filteredProducts = allProducts;

        if (searchTerm.trim() !== '') {
            filteredProducts = filteredProducts.filter(product =>
                product.product_name.toLowerCase().includes(searchTerm.toLowerCase())
            );
        }

        if (category !== '*') {
            filteredProducts = filteredProducts.filter(product => product.category.toLowerCase().replace(/\s+/g, '-') === category);
        }

        if (price !== '*') {
            const [minPrice, maxPrice] = price.split('-').map(Number);
            filteredProducts = filteredProducts.filter(product => product.product_price >= minPrice && product.product_price <= maxPrice);
        }

        if (place !== '*') {
            filteredProducts = filteredProducts.filter(product => product.available_area === place);
        }

        displayedProducts = filteredProducts; // Update displayed products array
        displayProducts(displayedProducts);
    }

    // Function to sort products by price
    function sortProducts(sortBy) {
        let sortedProducts = [...displayedProducts];

        if (sortBy === 'low-high') {
            sortedProducts.sort((a, b) => a.product_price - b.product_price);
        } else if (sortBy === 'high-low') {
            sortedProducts.sort((a, b) => b.product_price - a.product_price);
        }

        displayProducts(sortedProducts);
    }

    // Event listener for search input
    document.getElementById('searchInput').addEventListener('input', function () {
        const searchTerm = this.value;
        const categoryFilter = document.querySelector('.filter-btn.how-active1')?.getAttribute('data-filter') || '*';
        const priceFilter = document.querySelector('.filter-link[data-price].filter-link-active')?.getAttribute('data-price') || '*';
        const placeFilter = document.querySelector('.filter-link[data-place].filter-link-active')?.getAttribute('data-place') || '*';
        filterDisplayedProducts(searchTerm, categoryFilter, priceFilter, placeFilter);
    });

    // Event listener for filter buttons (categories)
    document.querySelectorAll('.filter-btn').forEach(button => {
        button.addEventListener('click', function () {
            const category = this.getAttribute('data-filter');
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('how-active1'));
            this.classList.add('how-active1');
            const priceFilter = document.querySelector('.filter-link[data-price].filter-link-active')?.getAttribute('data-price') || '*';
            const placeFilter = document.querySelector('.filter-link[data-place].filter-link-active')?.getAttribute('data-place') || '*';
            const searchTerm = document.getElementById('searchInput').value;
            filterDisplayedProducts(searchTerm, category, priceFilter, placeFilter);
        });
    });

    // Event listener for filter links (price and place)
    document.querySelectorAll('.filter-link').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const filterType = this.getAttribute('data-price') ? 'price' : 'place';
            const filterValue = this.getAttribute(`data-${filterType}`);
            document.querySelectorAll(`.filter-link[data-${filterType}]`).forEach(lnk => lnk.classList.remove('filter-link-active'));
            this.classList.add('filter-link-active');
            const categoryFilter = document.querySelector('.filter-btn.how-active1')?.getAttribute('data-filter') || '*';
            const priceFilter = document.querySelector('.filter-link[data-price].filter-link-active')?.getAttribute('data-price') || '*';
            const placeFilter = document.querySelector('.filter-link[data-place].filter-link-active')?.getAttribute('data-place') || '*';
            const searchTerm = document.getElementById('searchInput').value;
            filterDisplayedProducts(searchTerm, categoryFilter, priceFilter, placeFilter);
        });
    });

    // Event listener for Sort links (Default, Price: Low to High, Price: High to Low)
    document.querySelectorAll('.sort-link').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const sortBy = this.getAttribute('data-sort');
            document.querySelectorAll('.sort-link').forEach(lnk => lnk.classList.remove('filter-link-active'));
            this.classList.add('filter-link-active');
            sortProducts(sortBy);
        });
    });

    // Event listener for Load More button
    document.getElementById('load-more-btn').addEventListener('click', loadMoreProducts);

    // Initial fetch of all products
    fetchAllProducts();
});
