<?php function draw_header(string $page) { ?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Pre-loved</title>    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <link href="layout.css" rel="stylesheet">
    <link href="responsive.css" rel="stylesheet">
    <link href="icons.css" rel="stylesheet">
    <script src="scripts/search.js" defer></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body> 
    <header>
        <a href="main.php"><img src="images/logo.png" id="logo" alt="logo"></a>
        <search class="search-container">
            <div class="dropdown-categories">
                <button class="dropbtn">All Categories<i class="material-symbols-outlined">expand_more</i></button>
                <div class="dropdown-content">
                    <a href="search.php?category=clothes">Clothes</a>
                    <a href="search.php?category=technology">Technology</a>
                    <a href="search.php?category=toys">Toys</a>
                    <a href="search.php?category=cars">Cars</a>
                    <a href="search.php?category=books">Books</a>
                    <a href="search.php?category=sport">Sport</a>
                </div>
            </div>
            <form class="search" method="GET" action="search.php">
                <label for="searchbar"><input name="q" type="search" id="searchbar" autocomplete="off"></label>
                <button type="submit" class="searchbtn" ><i class="material-symbols-outlined">search</i></button>
                <div id="suggestions"></div>
            </form>
        </search>
        <nav>
            <a href="cart.php"><i class="material-symbols-outlined <?= $page=="cart"? "filled": "big"?>"> local_mall </i></a>
            <?php if (isset($_SESSION['username'])) { ?>
                <a href="favorite.php"><i class="material-symbols-outlined <?= $page=="favorite"? "filled": "big"?>"> favorite </i></a>
                <a href="chat.php"><i class="material-symbols-outlined <?= $page=="chat"? "filled": "big"?>"> chat </i></a>
                <?= $_SESSION['username'] ?> |
                <a href="profile.php"><i class="material-symbols-outlined <?= $page=="profile"? "filled": "big"?>"> person </i> </a>
            <?php } else { ?>
                <a href="login.php" id="login">Log in</a>
            <?php } ?>
        </nav>
    </header>
    <main>
<?php } ?>

<?php function draw_footer() { ?>
    </main>
    <script src="header.js" defer></script>
    <footer>
        <p>© Pre-loved, 2024</p>
    </footer>
  </body>
</html>
<?php } ?>
