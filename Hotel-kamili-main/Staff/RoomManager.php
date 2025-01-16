<?php

session_start();
require_once '../classes/DbConnector.php';
require_once '../classes/room.php';
require_once '../classes/staff.php';
require_once '../classes/faq.php';
require_once '../classes/Reservation.php';
require_once '../classes/RoomAmenity.php';
require_once '../classes/RoomImages.php';



use classes\DbConnector;
use classes\Room;
use classes\staff;
use classes\Reservation;
use classes\faq;


// Initialize $selectedDate with a default value if not set
$selectedDate = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');


$dbConnector = new DbConnector();
$con = $dbConnector->getConnection();

$rooms = Room::getAllRooms($con);

$availableRooms = Room::getAllRoomAvailableCountWithinASpecificDate($con, $selectedDate);
$reservedRooms = Room::getReservedRoomCountByDate($con, $selectedDate);


$staff = new staff('', '', '', '', '', '', '', '');
$staffList = $staff->getAllStaff($con);

$reservations = Reservation::getAllReservations($con);

$faq = new faq('', '');
$faqList = $faq->getAllFaq($con);

// pagination for reservation

$totalReservations = Reservation::getTotalReservations($con);
$limit = 10;
$totalPages = ceil($totalReservations / $limit);
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);
$offset = ($page - 1) * $limit;
$reservations = Reservation::getAllReservations($con, $limit, $offset);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="staff.css"> <!-- Link to your CSS file -->
    <title>Room Manager</title>
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="navbar-logo">
            <img src="../Assests/cropped-kamili-Copy-1.png" alt="Hotel Logo" class="logo">
            <h2 class="navbar-title">Kamili Beach Resort</h2>
        </div>
        <div class="navbar-profile">
            <span id="staffName">Room manager</span>
            <button class="logout-button">Logout</button>
        </div>
    </nav>
    <!-- NAVBAR -->

    <section id="content">
        <main class="main-content">
            <center>

                <h1 class="title">Event Handling</h1>

                <div>
                    <ul class="box-info">
                        <li>
                            <i id="calendar-icon" class='bx bxs-calendar-check'></i>
                            <span class="text">
                                <h3>Pick a date</h3>
                                <form method="post">
                                    <input type="date" name="date" id="date" value="<?php echo htmlspecialchars($selectedDate); ?>" onchange="updateDate()">
                                </form>
                            </span>
                            <script>
                                document.addEventListener('DOMContentLoaded', (event) => {
                                    // Function to update date input if not already set
                                    const updateDate = () => {
                                        const dateInput = document.getElementById('date');
                                        if (!dateInput.value) {
                                            const today = new Date().toISOString().split('T')[0];
                                            dateInput.value = today;
                                        }
                                    };

                                    // Call updateDate on DOMContentLoaded and when date input changes
                                    updateDate();

                                    // Function to submit form when date changes
                                    const dateInput = document.getElementById('date');
                                    dateInput.addEventListener('change', function() {
                                        this.form.submit();
                                    });
                                });
                            </script>

                        </li>
                        <li>
                            <i class='bx bxs-group'></i>
                            <span class="text">
                                <h3><?php echo $availableRooms; ?></h3>
                                <p>Available Rooms</p>
                            </span>
                        </li>
                        <li>
                            <i class='bx bx-registered'></i>
                            <span class="text">
                                <h3><?php echo $reservedRooms; ?></h3>
                                <p>Reserved Rooms</p>
                            </span>
                        </li>
                    </ul>
                </div>
            </center>

            <div class="table-data">
                <form action="" method="post">
                    

                            <!-- Pagination Links -->
                            <div class="pagination">
                                <?php if ($page > 1) : ?>
                                    <a href="?page=<?php echo $page - 1; ?>" class="pagination-link">Previous</a>
                                <?php endif; ?>
                                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                                    <a href="?page=<?php echo $i; ?>" <?php if ($i == $page) echo 'class="pagination-link active"';
                                                                        else echo 'class="pagination-link"'; ?>><?php echo $i; ?></a>
                                <?php endfor; ?>
                                <?php if ($page < $totalPages) : ?>
                                    <a href="?page=<?php echo $page + 1; ?>" class="pagination-link">Next</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
                    <!-- Reservation end -->

                </form>
            </div>

        </main>
    </section>

    <footer>
        <p>&copy; 2024 Kamili Beach Resort</p>
    </footer>

    <script src="../JS/Admin_script.js"></script>

</body>

</html>