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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
    <header>
        <a href="main.php"><img src="images/logo.png" id="logo" alt="logo"></a>
        <search class="search-container">
            <div class="dropdown-categories">
                <button class="dropbtn">All Categories<i class="material-symbols-outlined">expand_more</i></button>
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
                <button type="submit" class="searchbtn"><i class="material-symbols-outlined">search</i></button>
            </form>
        </search>
        <nav>
            <i class="material-symbols-outlined <?= $page=="cart"? "filled": "big"?>"> local_mall </i>
            <?php if (isset($_SESSION['username'])) { ?>
                <i class="material-symbols-outlined <?= $page=="favorite"? "filled": "big"?>"> favorite </i>
                <?= $_SESSION['username'] ?> |
                <a href="profile.php?>"><i class="material-symbols-outlined <?= $page=="profile"? "filled": "big"?>"> person </i> </a>
            <?php } else { ?>
                <a href="login.php" id="login">Log in</a>
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
