document.addEventListener("DOMContentLoaded", function () {
    let allPDFs = []; // Array to store all PDFs
    let displayedPDFs = []; // Array to store currently displayed PDFs
    const pdfsPerLoad = 8; // Number of PDFs to load each time
    let pdfOffset = 0; // Offset to track PDFs loaded

    // Function to fetch all PDFs
    async function fetchAllPDFs() {
        try {
            const response = await fetch('fetch_pdfs.php'); // Update this to your PDF fetch endpoint
            const data = await response.json();
            allPDFs = data;
            loadMorePDFs(); // Load initial set of PDFs
        } catch (error) {
            console.error('Error fetching PDFs:', error);
        }
    }

    function clearAllFilters() {
        // Reset the displayed products and PDFs to the full list
        displayedPDFs = allPDFs.slice(0, pdfOffset + pdfsPerLoad);
    
        // Display the products and PDFs again
        displayPDFs(displayedPDFs);
    }

    // Function to display PDFs
    function displayPDFs(pdfs) {
        console.log(pdfs)
        const pdfContainer = document.getElementById('pdfContainer');
        pdfContainer.innerHTML = ''; // Clear current PDFs
        pdfs.forEach(pdf => {
            const pdfHtml = `
                <div class="col-sm-6 col-md-4 col-lg-3 p-b-35">
                    <div class="block2">
                        <div class="block2-pic hov-img0">
                            <img src="${pdf.img1}" alt="PDF Cover" style="width: 100%; height: 350px; object-fit: cover;">
                            <a href="pdf-detail.php?id=${pdf.pdf_id}" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04">View Details</a>
                        </div>
                        <div class="block2-txt flex-w flex-t p-t-14">
                            <div class="block2-txt-child1 flex-col-l">
                                <a href="#" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">${pdf.pdf_name}</a>
                                <span class="stext-105 cl3">₹${pdf.price}</span>
                            </div>
                            <div class="block2-txt-child2 flex-r p-t-3">
                                <a href="#" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">
                                    <img class="icon-heart1 dis-block trans-04" src="${pdf.wishlist_icon}" alt="ICON" id="like-icon-${pdf.pdf_id}" onclick="toggleLike(${pdf.pdf_id})">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            pdfContainer.insertAdjacentHTML('beforeend', pdfHtml);
        });
    }

    // Function to load more PDFs
    function loadMorePDFs() {
        const newPDFs = allPDFs.slice(pdfOffset, pdfOffset + pdfsPerLoad);
        if (newPDFs.length > 0) {
            displayedPDFs = displayedPDFs.concat(newPDFs);
            displayPDFs(displayedPDFs);
            pdfOffset += pdfsPerLoad;
        } else {
            alert('No more PDFs to load.');
        }
    }

    // Function to filter displayed PDFs
    function filterDisplayedPDFs(searchTerm, category = '*', price = '*', place = '*') {
        let filteredPDFs = allPDFs;

        if (searchTerm.trim() !== '') {
            filteredPDFs = filteredPDFs.filter(pdf =>
                pdf.pdf_name.toLowerCase().includes(searchTerm.toLowerCase())
            );
        }

        if (category !== '*') {
            filteredPDFs = filteredPDFs.filter(pdf => pdf.category.toLowerCase().replace(/\s+/g, '-') === category);
        }

        if (price !== '*') {
            const [minPrice, maxPrice] = price.split('-').map(Number);
            filteredPDFs = filteredPDFs.filter(pdf => pdf.price >= minPrice && pdf.price <= maxPrice);
        }

        if (place !== '*') {
            filteredPDFs = filteredPDFs.filter(pdf => pdf.available_area === place);
        }

        displayedPDFs = filteredPDFs; // Update displayed PDFs array
        displayPDFs(displayedPDFs);
    }

    // Function to sort PDFs by price
    function sortPDFs(sortBy) {
        let sortedPDFs = [...displayedPDFs];

        if (sortBy === 'low-high') {
            sortedPDFs.sort((a, b) => a.price - b.price);
        } else if (sortBy === 'high-low') {
            sortedPDFs.sort((a, b) => b.price - a.price);
        }

        displayPDFs(sortedPDFs);
    }

    // Event listener for search input
    document.getElementById('searchInput').addEventListener('input', function () {
        const searchTerm = this.value;
        const categoryFilter = document.querySelector('.filter-btn.how-active1')?.getAttribute('data-filter') || '*';
        const priceFilter = document.querySelector('.filter-link[data-price].filter-link-active')?.getAttribute('data-price') || '*';
        const placeFilter = document.querySelector('.filter-link[data-place].filter-link-active')?.getAttribute('data-place') || '*';
        filterDisplayedPDFs(searchTerm, categoryFilter, priceFilter, placeFilter);
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
            filterDisplayedPDFs(searchTerm, category, priceFilter, placeFilter);
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
            filterDisplayedPDFs(searchTerm, categoryFilter, priceFilter, placeFilter);
        });
    });

    // Event listener for Sort links (Default, Price: Low to High, Price: High to Low)
    document.querySelectorAll('.sort-link').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const sortBy = this.getAttribute('data-sort');
            document.querySelectorAll('.sort-link').forEach(lnk => lnk.classList.remove('filter-link-active'));
            this.classList.add('filter-link-active');
            sortPDFs(sortBy);
        });
    });

    // Event listener for Load More button
    document.getElementById('load-more-btn').addEventListener('click', loadMorePDFs);
    document.getElementById('clear-filter-btn').addEventListener('click', clearAllFilters);

    // Initial fetch of all PDFs
    fetchAllPDFs();
});
