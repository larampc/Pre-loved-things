<?php
declare(strict_types = 1); ?>

<?php function draw_new_category_form()
{ ?>
    <script src="../scripts/new_category.js" defer></script>
    <article class="newCategoryPage">
        <h2>New Category</h2>
        <form action="../actions/action_new_category.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
            <label> Category name
                <input type="text" name="category" required>
            </label>
            <i class="material-symbols-outlined add-tag" title="Add tag">add</i>
            <section class="new-tags">
            </section>
            <button type="submit">Submit</button>
        </form>
    </article>
<?php } ?>

<?php function draw_edit_category_form(PDO $dbh, string $category)
{ ?>
    <script src="../scripts/new_category.js" defer></script>
    <article class="newCategoryPage">
        <h2>Edit Category</h2>
        <form action="../actions/action_edit_category.php?id=<?=$category?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">

            <label> Category name
                <input type="text" value="<?=$category?>" name="category" required>
            </label>
            <i class="material-symbols-outlined add-tag" title="Add tag">add</i>
            <section class="new-tags">
                <?php $tags = Tag::get_category_tags($dbh, $category);
                for ($i = 0; $i < count($tags); $i++) { ?>
                    <div class="new-tag" id="new-tag-<?=$i?>">
                        <label>Tag name<input type="text" name="tags[<?=$i?>]" value="<?=$tags[$i]['tag']?>" required>
                        </label>
                        <i class="material-symbols-outlined delete-option" title="Delete tag">delete</i>
                        <?php $tag_options = Tag::get_tag_options($dbh, $category, $tags[$i]['tag']);?>
                        <label>
                            <select class="type" name="select-type">
                                <option value="free">Free</option>
                                <option value="select" <?=$tag_options ? "selected" : ""?>>Select</option>
                            </select>
                        </label>
                        <?php
                        if ($tag_options) { ?>
                            <section class="tag-options">
                                <?php foreach ($tag_options as $key => $tag) {?>
                                    <label class="<?=$i?>">
                                        <input required type="text" name="option<?=$i?>[<?=$key?>]" value="<?=$tag['value']?>">
                                        <i class="material-symbols-outlined remove-option" title="Remove option">close</i>
                                    </label>
                                <?php }?>
                            </section>
                            <i class="material-symbols-outlined new-option" title="Add option">add</i>
                        <?php } ?>
                    </div>
                <?php } ?>

            </section>
            <button type="submit">Submit</button>
        </form>
    </article>
<?php }

function draw_category_tags(PDO $dbh, $category) {
    $tags = Tag::get_category_tags($dbh, $category);
    if (!empty($tags)) { ?>
        <div class="category-<?=$category?> category-box <?=$category == ""?: "hide"?>" >
            <p><?=$category?></p>
            <?php foreach ($tags as $tag) { ?>
                <div class="options tag">
                    <p><?=$tag['tag']?></p>
                    <?php
                    $options = Tag::get_tag_options($dbh, $category, $tag['tag']);
                    if ($options) {
                        foreach ($options as $option) {?>
                            <label><input type="checkbox" name="<?=$tag['tag']?>" value="<?=$option['value']?>">
                                <?=$option['value']?>
                            </label>
                        <?php }}
                    else { ?>
                        <label><input type="text" name="<?=$tag['tag']?>"></label>
                    <?php } ?>
                </div>
            <?php } ?> </div> <?php }}

function draw_page_filters(array $categories, PDO $dbh, Session $session) { ?>
    <script src="../scripts/search.js" defer></script>
    <section class="filter">
        <div class="filter_header">
            <h2>Filters</h2>
            <button id="close-filters" onclick="closeFilters()"><i class="material-symbols-outlined big filled">close</i></button>
        </div>
        <p>Categories
            <?php if ($session->is_admin()) {?>
                <a href="../pages/new_category.php" class="material-symbols-outlined">add_circle</a>
            <?php }?>
        </p>
        <div class="categories">
            <script src="../scripts/user_actions.js" defer></script>
            <?php foreach ($categories as $category) {
                if ($category['category'] !== "") { ?>
                    <div class="category">
                        <label><input type="checkbox" class="select-category" id="<?=$category['category']?>" value="<?=$category['category']?>"><?=$category['category'] ?: "All categories"?></label>
                        <?php if ($session->is_admin()) {?>
                            <div class="category-actions">
                                <a href="../pages/edit_category.php?category=<?=$category['category']?>" class="material-symbols-outlined">edit</a>
                                <form method="post" action="../actions/action_remove_category.php" class="confirmation">
                                    <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
                                    <button title="Remove category" type="submit" value="<?=$category['category']?>" name="remove-category" class="role confirm-action"><i class="material-symbols-outlined big"> delete </i>
                                    </button>
                                </form>
                            </div>
                        <?php }?>
                    </div>
                <?php }} ?>
        </div>
        <p>Order by</p>
        <label for="order"></label>
        <select id="order">
            <option value="recent">Most recent</option>
            <option value="price-asc">Ascending Price</option>
            <option value="price-desc">Descending Price</option>
        </select>
        <p>Price</p>
        <div class="price-input">
            <label> Minimum
                <input name="minimum" type="number"
                       class="min-input"
                       value="0">
            </label>
            <label> Maximum
                <input name="maximum" type="number"
                       class="max-input"
                       value="<?=ceil((8500 * Currency::get_currency_conversion($dbh, $session->get_currency()))/10)*10?>">
            </label>
        </div>
        <?php
        foreach ($categories as $category) {
            draw_category_tags($dbh, $category['category']);
        }
        ?>
    </section>
    <button id="open-filters" onclick="openFilters()"><i class="material-symbols-outlined big filled">filter_list</i></button>
    <div class="items searchresult">
        <div class="loader"></div>
    </div>
<?php } ?>