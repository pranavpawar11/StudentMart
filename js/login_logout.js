function showConfirmLogout() {
    document.getElementById('confirmLogoutModal').style.display = 'flex';
}

function closeConfirmLogout() {
    document.getElementById('confirmLogoutModal').style.display = 'none';
}

function logout() {
    fetch('php/logout.php')
        .then(response => {
            if (response.ok) {
                // Redirect to login page or homepage after successful logout
                window.location.href = 'login.php';
            } else {
                console.error('Logout failed');
            }
        })
        .catch(error => console.error('Error:', error));
}



// 

function createPopup() {
    // console.log("Creating popup");
    const popup = document.createElement('div');
    popup.id = 'popup';
    popup.className = 'popup';
    popup.innerHTML = `
<div class="popup-content">
    <div class="popup-icon">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
        </svg>
    </div>
    <h2 class="popup-title">Success!</h2>
    <p id="popup-message"></p>
    <button class="popup-button" onclick="closePopup()">Continue</button>
</div>
`;
    document.body.appendChild(popup);
    setTimeout(() => {
        document.body.removeChild(popup)
    }, 2000);
}

function showPopup(message) {
    // console.log("Showing popup with message:", message);
    if (!document.getElementById('popup')) {
        createPopup();
    }
    document.getElementById('popup-message').textContent = message;
    document.getElementById('popup').style.display = 'flex';
    setTimeout(() => {
        document.getElementById('popup').classList.add('active');
    }, 10);
}

function closePopup() {
    // console.log("Closing popup");
    const popup = document.getElementById('popup');
    if (popup) {
        popup.classList.remove('active');
        setTimeout(() => {
            popup.style.display = 'none';
            popup.remove(); // Remove the popup from the DOM
        }, 300);
    }
    localStorage.setItem('popupShown', 'true');
}

function resetPopupState() {
    localStorage.removeItem('popupShown');
}