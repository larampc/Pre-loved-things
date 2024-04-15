<?php function draw_header() { ?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Pre-loved</title>    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <link href="layout.css" rel="stylesheet">
    <link href="responsive.css" rel="stylesheet">
</head>
<body>
    <header>
        <a href="main.php"><img src="images/logo.png" id="logo" alt="logo"></a>
        <search class="search-container">
            <div class="dropdown-categories">
                <button class="dropbtn">All Categories<img src="images/dropdown.png" alt="categories dropdown button"></button>
                <div class="dropdown-content">
                    <a href="#">Clothes</a>
                    <a href="#">Technology</a>
                    <a href="#">Toys</a>
                    <a href="#">Cars</a>
                    <a href="#">Books</a>
                    <a href="#">Sport</a>
                </div>
            </div>
            <form action="/action_page.php">
                <label>
                    <input type="search" id="searchbar">
                </label>
                <button type="submit" class="searchbtn"><img src="images/search.png" alt="search button"></button>
            </form>
        </search>
        <nav>
            <a href="liked.php"><img src="images/heart.png" id="heart" alt="heart"></a>
            <a href="cart.php"><img src="images/cart.png" id="cart" alt="cart"></a>
            <a href="login.html" id="login">Log in</a>
            <?php if (isset($_SESSION['username'])) { ?>
                <?= $_SESSION['username'] ?> |
                <a href="user.php"><img src="images/user.png" id="user-pic" alt="user"></a>
            <?php } else { ?>
                <a href="login.php" id="login">Login</a>
            <?php } ?>
        </nav>
    </header>
    <main>
<?php } ?>

<?php function draw_footer() { ?>
    </main>
    <footer>
        <p>© Pre-loved, 2024</p>
    </footer>
  </body>
</html>
<?php } ?>
