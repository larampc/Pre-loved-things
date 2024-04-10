<?php declare(strict_types = 1); ?>

<?php function drawItems(array $items) { ?>
  <h2>Last Added Items</h2>
  <div class="item-main">
    <?php foreach($items as $item) { ?> 
        <img src="<?="images/" . $item['imagePath']?>" alt="<?= explode($item['imagePath'],'.')[0]?>"  >
        <div class="item-info">
            <p class="name"><?=$item['Name']?></p>
            <p class="price"><?=$item['Price']?></p>
        </div>
       <?php } ?>
    <h2>Most liked</h2>
    <section class="items">
    <?php foreach($items as $item) { ?>
        <img src="<?="images/" . $item['imagePath']?>" alt="<?= explode($item['imagePath'],'.')[0]?>"  >
        <div class="item-info">
            <p class="name"><?=$item['Name']?></p>
            <p class="price"><?=$item['Price']?></p>
        </div>
       <?php } ?>
    </section> 
<?php } ?>

