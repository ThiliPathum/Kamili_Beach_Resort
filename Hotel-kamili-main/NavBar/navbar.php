<!DOCTYPE html>
<html lang="en" dir="ltr">

    <head>
        <meta charset="utf-8">
        <link rel="shortcut icon" type="png" href="../Assests/cropped-kamili-Copy-1.png">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>KAMILI BEACH RESORT</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css" integrity="sha512-UTNP5BXLIptsaj5WdKFrkFov94lDx+eBvbKyoe1YAfjeRPC+gT5kyZ10kOHCfNZqEui1sxmqvodNUx3KbuYI/A==" crossorigin="anonymous"
            referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" integrity="sha512-sMXtMNL1zRzolHYKEujM2AqCLUR9F2C4/05cdbxjjLSRvMQIciEPCQZo++nk7go3BtSuK9kfa/s+a4f4i5pLkw=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="kamili-navbar">
            <header>
                <div class="content flex_space">
                    <div class="logo">
                        <img src="../Assests/cropped-kamili-Copy-1.png" alt="Image" width="70px" height="70px">
                    </div>

                    <div class="navlinks">
                        <ul id="menulist">
                            <li><a href="./index.php">Home</a></li>
                            <li><a href="./about-us.php">About</a></li>
                            <li><a href="./rooms.php">Rooms</a></li>
                            <li><a href="./services.php">Services</a></li>
                            <!-- <li><a href="#">FAQ</a></li> -->
                            <li><a href="../contact-us.php">Contact</a></li>
                            <li><button onclick="window.location.href='../filterd_room.php'" class="primary-btn">BOOK NOW</button></li>
                        </ul>
                        <span class="fa fa-bars" onclick="menutoggle()"></span>
                    </div>
                </div>
            </header>

            <script>
                var menulist = document.getElementById('menulist');
                menulist.style.maxHeight = "0px";

                function menutoggle() {
                    if (menulist.style.maxHeight == "0px") {
                        menulist.style.maxHeight = "100vh";
                    } else {
                        menulist.style.maxHeight = "0px";
                    }
                }
            </script>
        </div>
    </body>
</html>