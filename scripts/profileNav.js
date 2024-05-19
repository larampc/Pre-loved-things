let currentNav = "my"
let page = 1;
let all = false;
let isLoading = false;

updatePage();

async function openNav(option) {
    currentNav = option;
    page = 1;
    const x = document.querySelectorAll(".display-item .items");
    const buttons = document.querySelectorAll(".navbar *");
    for (let j = 0; j < buttons.length; j++) {
        if (buttons[j].classList.contains(option)) buttons[j].style.border = "2px solid #D9D9D9";
        else buttons[j].style.border = "none";
    }
    await updatePage();
}

const previous = document.querySelector("#prev-btn");
const next = document.querySelector("#next-btn");
console.log(previous)
previous.addEventListener("click", async () => {
    if (page > 1) page--;
    await updatePage();
})

next.addEventListener("click", async () => {
    if (!all) page++;
    await updatePage();
})

async function updatePage() {
    isLoading = true;
    const response = await fetch('../api/api_get_user_items.php?page=' + page + "&nav=" + currentNav);
    const items = await response.json();
    if (items.length < 5) {
        all = true;
        isLoading = false;
        if (items === 0) return;
    }
    document.querySelector(".items").replaceWith( await getItems(items));
    isLoading = false;
}

async function getItems(items) {
    const response_currency = await fetch('../api/api_get_currency.php')
    const currency = await response_currency.json();
    const result = document.createElement("div")
    result.classList.add("items")
    for (const item of items) {
        console.log(item)
        result.appendChild(await drawItem(item, currency));
    }
    return result
}

async function drawItem(item, currency) {
    const main = document.createElement('a');
    if (currentNav !== "my") {
        const response = await fetch('../api/api_get_purchase.php?item=' + item.id);
        const purchase_id = await response.json();
        main.href = "track_item.php?purchase=" + purchase_id;
    }
    else main.href = "item.php?id=" + item.id;
    main.className = "item";
    const img = document.createElement("img")
    img.src = "../uploads/thumbnails/" + item['mainImage'] + ".png"
    main.appendChild(img);
    const div = document.createElement("div")
    div.className = "item-info"
    const name = document.createElement("p")
    name.innerText = item.name;
    name.className = "name"
    div.appendChild(name);
    const price = document.createElement("p")
    price.innerText = item.price + currency;
    price.className = "price"
    div.appendChild(price);
    main.appendChild(div);
    console.log(main)
    return main;
}