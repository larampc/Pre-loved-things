<?php
require_once ("../database/user.class.php");

function draw_header(string $page, Session $session, array $currencies) { ?>
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
    <script src="../scripts/suggestions.js" defer></script>
    <script src="../scripts/header.js" defer></script>
    <script src="../scripts/like.js" defer></script>
    <script src="../scripts/add_cart.js" defer></script>
    <script src="../scripts/remove_cart.js" defer></script>
    <script src="../scripts/flip.js" defer></script>
    <script src="../scripts/print.js" defer></script>
    <script src="../scripts/set_currency.js" defer></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
</head>
<body> 
    <header class="header-visible">
        <a href="../index.php"><img src="../resources/logo.png" id="logo" alt="logo"></a>
            <form class="search-container" method="GET" action="../pages/search.php">
                <label for="searchbar"><input name="search" type="search" id="searchbar" list="search-suggestions" autocomplete="off" value="<?=$page == "search"? $_GET['search'] : ""?>"></label>
                <button type="submit" class="search-btn" ><i class="material-symbols-outlined bold">search</i></button>
                <datalist id="search-suggestions"></datalist>
            </form>
        <nav>
            <form method="GET" action="../actions/action_change_currency.php">
                <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
                <select class="currency" name="currency" >
                    <?php foreach ($currencies as $currency) { ?>
                        <option id="<?=$currency['code']?>" value="<?=$currency['code']?>" <?=$currency['code'] == $session->getCurrency()? "selected":""?>><?=$currency['code']?></option>
                    <?php } ?>
                </select>
                <button type="submit" class="change-currency"></button>
            </form>
            <a href="../pages/cart.php"><i class="material-symbols-outlined big <?= $page=="cart"? "filled": ""?>"> local_mall </i></a>
            <?php if ($session->isLoggedIn()) { ?>
                <a href="../pages/favorite.php"><i class="material-symbols-outlined big <?= $page=="favorite"? "filled": ""?>"> favorite </i></a>
                <a href="../pages/inbox.php"><i class="material-symbols-outlined big <?= $page=="inbox"? "filled": ""?>"> chat </i></a>
                <a href="../pages/profile.php"><i class="material-symbols-outlined big <?= $page=="profile"? "filled": ""?>"> person </i> </a>
            <?php } else { ?>
                <a href="../pages/login.php" id="login">Log in</a>
            <?php } ?>
        </nav>
    </header>
        <section id="messages">
            <?php foreach ($session->getMessages() as $message) { ?>
                <article class="<?=$message['type']?>">
                    <?php
                    if ($message['type'] == "success") { ?>
                        <i class="material-symbols-outlined green"> check_circle</i>
                    <?php } else if ($message['type'] == "error") { ?>
                        <i class="material-symbols-outlined red"> error </i>
                    <?php } ?>
                    <p><?=$message['text']?></p>
                    <div class="message-progress"></div>
                </article>
            <?php } ?>
        </section>
    <main class=<?=$page . "-main"?>>
<?php } ?>

<?php function draw_footer() { ?>
    </main>
    <footer>
        <p>© Pre-loved, 2024</p>
    </footer>
  </body>
</html>
<?php } ?>

<?php function get_header(string $name,PDO $dbh, Session $session) {
    $currencies = User::get_currencies($dbh);
    draw_header($name, $session, $currencies);
}