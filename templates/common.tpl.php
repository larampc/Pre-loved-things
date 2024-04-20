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
            <form class="search-container" method="GET" action="search.php">
                <label>
                    <select name="category" class="dropdown-content">
                        <option value="">All Categories</option>
                        <option value="clothes">Clothes</option>
                        <option value="technology">Technology</option>
                        <option value="toys">Toys</option>
                        <option value="cars">Cars</option>
                        <option value="books">Books</option>
                        <option value="sport">Sport</option>
                    </select>
                </label>
                <label for="searchbar"><input name="q" type="search" id="searchbar" autocomplete="off"></label>
                <button type="submit" class="searchbtn" ><i class="material-symbols-outlined">search</i></button>
                <div id="suggestions"></div>
            </form>
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
