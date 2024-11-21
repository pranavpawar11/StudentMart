// Call the function to create the modal element
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
            // console.log(allPDFs);
            loadMorePDFs(); // Load initial set of PDFs
        } catch (error) {
            console.error('Error fetching PDFs:', error);
        }
    }

    // Function to display PDFs
    function displayPDFs(pdfs) {
        const pdfContainer = document.getElementById('pdfContainer');
        pdfContainer.innerHTML = ''; // Clear current PDFs
        pdfs.forEach(pdf => {
            const pdfHtml = `
                <div class="col-sm-6 col-md-4 col-lg-3 p-b-35">
                    <div class="block2">
                        <div class="block2-pic hov-img0">
                            <img src="${pdf.img1}" alt="PDF Cover" style="width: 100%; height: 350px; object-fit: cover;">
                            <a href="pdf-detail.php?id=${pdf.pdf_id}" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04" >View Details</a>
                        </div>
                        <div class="block2-txt flex-w flex-t p-t-14">
                            <div class="block2-txt-child1 flex-col-l">
                                <a href="#" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">${pdf.pdf_name}</a>
                                <p class="stext-105 cl3">RS ${pdf.price}</p>
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

    // Function to toggle the wishlist status of a PDF
    async function toggleLike(pdfId) {
        // Implement the logic to toggle wishlist status (add/remove) via an AJAX request to your server
        console.log(`Toggling wishlist for PDF ID: ${pdfId}`);
        // You can send a request to your server to update the wishlist status for the given PDF ID
    }

    // Event listener for Load More button
    document.getElementById('load-more-btn').addEventListener('click', loadMorePDFs);

    // Initial fetch of all PDFs
    fetchAllPDFs();
});
