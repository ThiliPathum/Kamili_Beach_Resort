// Sidebar Menu Activation
const allSideMenu = document.querySelectorAll('#sidebar .side-menu.top li a');

allSideMenu.forEach(item => {
    const li = item.parentElement;

    item.addEventListener('click', function () {
        allSideMenu.forEach(i => {
            i.parentElement.classList.remove('active');
        });
        li.classList.add('active');

        // Update breadcrumb
        const breadcrumb = document.getElementById('breadcrumb');
        breadcrumb.textContent = item.textContent;
    });
});

// Toggle Sidebar
const menuBar = document.querySelector('#content nav .bx.bx-menu');
const sidebar = document.getElementById('sidebar');

menuBar.addEventListener('click', function () {
    sidebar.classList.toggle('hide');
});

// Date Picker Toggle for Mobile
const searchButton = document.querySelector('#content nav form .form-input button');
const searchButtonIcon = document.querySelector('#content nav form .form-input button .bx');
const searchForm = document.querySelector('#content nav form');

searchButton.addEventListener('click', function (e) {
    if (window.innerWidth < 576) {
        e.preventDefault();
        searchForm.classList.toggle('show');
        if (searchForm.classList.contains('show')) {
            searchButtonIcon.classList.replace('bx-search', 'bx-x');
        } else {
            searchButtonIcon.classList.replace('bx-x', 'bx-search');
        }
    }
});

// Initial Responsive Adjustments
function adjustResponsive() {
    if (window.innerWidth < 768) {
        sidebar.classList.add('hide');
    } else if (window.innerWidth > 576) {
        searchButtonIcon.classList.replace('bx-x', 'bx-search');
        searchForm.classList.remove('show');
    }
}

adjustResponsive();
window.addEventListener('resize', adjustResponsive);


// Add data-label attributes to table cells for responsiveness
document.addEventListener('DOMContentLoaded', () => {
    const tableHeaders = document.querySelectorAll('.table-data th');
    const tableRows = document.querySelectorAll('.table-data tbody tr');

    tableRows.forEach(row => {
        const cells = row.querySelectorAll('td');
        cells.forEach((cell, i) => {
            cell.setAttribute('data-label', tableHeaders[i].innerText);
        });
    });
});


// Toggle FAQ Answer
document.querySelectorAll('.faq-question').forEach(question => {
    question.addEventListener('click', () => {
        const answer = question.nextElementSibling;
        if (answer.style.display === 'none' || !answer.style.display) {
            answer.style.display = 'block';
        } else {
            answer.style.display = 'none';
        }
    });
});

// date


// document.addEventListener('DOMContentLoaded', (event) => {
// 	const today = new Date().toISOString().split('T')[0];
// 	document.getElementById('date').value = today;
// });


// Initial Hide All Sections Except Dashboard
function showDashboard() {
    document.getElementById('reservation').style.display = 'none';
    document.getElementById('roomDetails').style.display = 'none';
    document.getElementById('settings').style.display = 'none';
    document.getElementById('staff').style.display = 'none';
    document.getElementById('faq').style.display = 'none';
    document.getElementById('event').style.display = 'none';
    document.querySelector('.order').style.display = 'block';
    sessionStorage.setItem('activeSection', 'dashboard');
}

function showRoomDetails() {
    document.getElementById('roomDetails').style.display = 'block';
    document.querySelector('.order').style.display = 'none';
    document.getElementById('reservation').style.display = 'none';
    document.getElementById('settings').style.display = 'none';
    document.getElementById('staff').style.display = 'none';
    document.getElementById('event').style.display = 'none';
    document.getElementById('faq').style.display = 'none';
    sessionStorage.setItem('activeSection', 'roomDetails');
}

function showReservationDetails() {
    document.getElementById('roomDetails').style.display = 'none';
    document.querySelector('.order').style.display = 'none';
    document.getElementById('staff').style.display = 'none';
    document.getElementById('faq').style.display = 'none';
    document.getElementById('settings').style.display = 'none';
    document.getElementById('event').style.display = 'none';
    document.getElementById('reservation').style.display = 'block';
    sessionStorage.setItem('activeSection', 'reservation');
}

function showStaffDetails() {
    document.getElementById('reservation').style.display = 'none';
    document.getElementById('roomDetails').style.display = 'none';
    document.getElementById('faq').style.display = 'none';
    document.getElementById('settings').style.display = 'none';
    document.querySelector('.order').style.display = 'none';
    document.getElementById('event').style.display = 'none';
    document.getElementById('staff').style.display = 'block';
    sessionStorage.setItem('activeSection', 'staff');
}

function showEventDetails() {
    document.getElementById('reservation').style.display = 'none';
    document.getElementById('roomDetails').style.display = 'none';
    document.getElementById('faq').style.display = 'none';
    document.getElementById('settings').style.display = 'none';
    document.querySelector('.order').style.display = 'none';
    document.getElementById('staff').style.display = 'none';
    document.getElementById('event').style.display = 'block';
    sessionStorage.setItem('activeSection', 'event');
}

function showFAQs() {
    document.getElementById('reservation').style.display = 'none';
    document.getElementById('roomDetails').style.display = 'none';
    document.getElementById('staff').style.display = 'none';
    document.querySelector('.order').style.display = 'none';
    document.getElementById('settings').style.display = 'none';
    document.getElementById('event').style.display = 'none';
    document.getElementById('faq').style.display = 'block';
    sessionStorage.setItem('activeSection', 'faq');
}

function showSettings() {
    document.getElementById('settings').style.display = 'block';
    document.getElementById('reservation').style.display = 'none';
    document.getElementById('roomDetails').style.display = 'none';
    document.getElementById('staff').style.display = 'none';
    document.querySelector('.order').style.display = 'none';
    document.getElementById('faq').style.display = 'none';
    document.getElementById('event').style.display = 'none';
    sessionStorage.setItem('activeSection', 'settings');
}

function showNotification(){
    document.getElementById('settings').style.display = 'none';
    document.getElementById('reservation').style.display = 'none';
    document.getElementById('roomDetails').style.display = 'none';
    document.getElementById('staff').style.display = 'block';
    document.querySelector('.order').style.display = 'none';
    document.getElementById('faq').style.display = 'none';
    document.getElementById('event').style.display = 'none';
    sessionStorage.setItem('activeSection', 'staff');
}

// Initial Setup
function loadLastActiveSection() {
    const lastActiveSection = sessionStorage.getItem('activeSection');
    switch (lastActiveSection) {
        case 'roomDetails':
            showRoomDetails();
            break;
        case 'reservation':
            showReservationDetails();
            break;
        case 'staff':
            showStaffDetails();
            break;
        case 'faq':
            showFAQs();
            break;
        case 'event':
            showEventDetails();
            break;
        case 'settings':
            showSettings();
            break;
        default:
            showDashboard();
            break;
    }
}

window.onload = loadLastActiveSection;

// cancel reservation

function cancelReservation(reservationId) {
    console.log("Cancel reservation called for ID: " + reservationId);

    // Ask for confirmation
    var confirmCancel = confirm("Are you sure you want to cancel this reservation?");

    if (!confirmCancel) {
        console.log("Cancellation canceled by user.");
        return;
    }

    // Prepare a form dynamically
    var form = document.createElement('form');
    form.setAttribute('method', 'post');
    form.setAttribute('action', 'cancelReservation.php');

    // Create an input element to send reservation_id
    var inputReservationId = document.createElement('input');
    inputReservationId.setAttribute('type', 'hidden');
    inputReservationId.setAttribute('name', 'reservation_id');
    inputReservationId.setAttribute('value', reservationId);

    // Append the input to the form
    form.appendChild(inputReservationId);

    // Optionally, you can submit the form automatically
    document.body.appendChild(form);
    form.submit();
}







// DeleteItemsFunctions

function DeleteProcess(itemId, itemType) {
    if (confirm("Are you sure you want to delete this " + itemType + "?")) {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = 'deleteProcess.php';

        var inputId = document.createElement('input');
        inputId.type = 'hidden';
        inputId.name = 'deleteItemId';
        inputId.value = itemId;

        var inputType = document.createElement('input');
        inputType.type = 'hidden';
        inputType.name = 'itemType';
        inputType.value = itemType;

        form.appendChild(inputId);
        form.appendChild(inputType);
        document.body.appendChild(form);
        form.submit();
    }
}



