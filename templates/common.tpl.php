<?php function drawHeader() { ?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Pre-loved</title>    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <link href="main-items.css" rel="stylesheet">
</head>
<body>
    <header>
        <a href="main.php"><img src="images/logo.png" id="logo"></a>
        <div class="search-container">
            <div class="dropdown-categories">
                <button class="dropbtn">All Categories<img src="images/dropdown.png"></button>
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
              <input type="text" name="search">
              <button type="submit" class="searchbtn"><img src="images/search.png"></button>
            </form>
        </div>
        <div class="nav">
            <a href="liked.php"><img src="images/heart.png" id="heart"></a>
            <a href="cart.php"><img src="images/cart.png" id="cart"></a>
            <a href="login.html" id="login">Log in</a>
        </div> 
    </header>
<?php } ?>

<?php function drawFooter() { ?>
    </main>

    <footer>
        <p>© Pre-loved, 2024</p>
    </footer>
  </body>
</html>
<?php } ?>
