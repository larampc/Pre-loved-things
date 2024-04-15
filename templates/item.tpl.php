<?php declare(strict_types = 1); ?>

<?php function draw_items(array $items) { ?>
    <h2>Last Added Items</h2>
    <section class="items">
        <?php foreach($items as $item) { ?>
        <div class="item-main">
            <img src="<?="images/" . $item['imagePath']?>" alt="<?= explode($item['imagePath'],'.')[0]?>"  >
                <div class="item-info">
                    <p class="name"><?=$item['Name']?></p>
                    <p class="price"><?=$item['Price']?></p>
                </div>
        </div>
       <?php } ?>
    </section>
    <h2>Most liked</h2>
    <section class="items">
    <?php foreach($items as $item) { ?>
        <div class="item-main">
            <img src="<?="images/" . $item['imagePath']?>" alt="<?= explode($item['imagePath'],'.')[0]?>"  >
            <div class="item-info">
                <p class="name"><?=$item['Name']?></p>
                <p class="price"><?=$item['Price']?></p>
            </div>
        </div>
       <?php } ?>
    </section> 
<?php } ?>

