<?php function draw_header(string $page, Session $session) { ?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Pre-loved</title>    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/layout.css" rel="stylesheet">
    <link href="../css/responsive.css" rel="stylesheet">
    <link href="../css/icons.css" rel="stylesheet">
    <script src="../scripts/search.js" defer></script>
    <script src="../scripts/header.js" defer></script>
    <script src="../scripts/like.js" defer></script>
    <script src="../scripts/add_cart.js" defer></script>
    <script src="../scripts/remove_cart.js" defer></script>
    <script src="../scripts/chatroom.js" defer></script>
    <script src="../scripts/checkout.js" defer></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body> 
    <header>
        <a href="../index.php"><img src="../images/logo.png" id="logo" alt="logo"></a>
            <form class="search-container" method="GET" action="../pages/search.php">
                <select name="category" class="dropdown-content">
                    <option value="">All Categories</option>
                    <option value="clothes">Clothes</option>
                    <option value="technology">Technology</option>
                    <option value="toys">Toys</option>
                    <option value="cars">Cars</option>
                    <option value="books">Books</option>
                    <option value="sport">Sport</option>
                </select>
                <label for="searchbar"><input name="q" type="search" id="searchbar" autocomplete="off"></label>
                <button type="submit" class="searchbtn" ><i class="material-symbols-outlined">search</i></button>
                <div id="suggestions"></div>
            </form>
        <nav>
            <a href="../pages/cart.php"><i class="material-symbols-outlined <?= $page=="cart"? "filled": "big"?>"> local_mall </i></a>
            <?php if ($session->isLoggedIn()) { ?>
                <a href="../pages/favorite.php"><i class="material-symbols-outlined <?= $page=="favorite"? "filled": "big"?>"> favorite </i></a>
                <a href="../pages/inbox.php"><i class="material-symbols-outlined <?= $page=="chat"? "filled": "big"?>"> chat </i></a>
                <a href="../pages/profile.php"><i class="material-symbols-outlined <?= $page=="profile"? "filled": "big"?>"> person </i> </a>
            <?php } else { ?>
                <a href="../pages/login.php" id="login">Log in</a>
            <?php } ?>
        </nav>
    </header>
    <main class=<?=$page . "Main"?>>
<?php } ?>

<?php function draw_footer() { ?>
    </main>
    <footer>
        <p>© Pre-loved, 2024</p>
    </footer>
  </body>
</html>
<?php } ?>
