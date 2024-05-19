let currentNav = "my"
let page = 1;
let isLoading = false;
let maxPage = 0;

async function openNav(option) {
    currentNav = option;
    page = 1;
    const x = document.querySelectorAll(".display-item .items");
    const buttons = document.querySelectorAll(".navbar *");
    for (let j = 0; j < buttons.length; j++) {
        if (buttons[j].classList.contains(option)) buttons[j].style.border = "2px solid #D9D9D9";
        else buttons[j].style.border = "none";
    }
    document.querySelector(".display-item").lastElementChild.remove()
    await draw_pagination();
    await updatePage();
}

async function draw_pagination() {
    const response_max = await fetch('../api/api_get_max_page.php?nav=' + currentNav);
    maxPage = await response_max.json();
    const nav = document.createElement("nav");
    nav.className = "pagination"
    for (let i = 1; i <= maxPage; i++) {
        const number = document.createElement("a");
        number.innerHTML = i.toString();
        number.addEventListener("click", async () => {
            page = i;
            await updatePage();
        })
        nav.appendChild(number);
    }
    document.querySelector(".display-item").appendChild(nav);
}

const previous = document.querySelector("#prev-btn");
const next = document.querySelector("#next-btn");
console.log(previous)
previous.addEventListener("click", async () => {
    if (page > 1) page--;
    await updatePage();
})

next.addEventListener("click", async () => {
    if (page < maxPage) page++;
    await updatePage();
})

async function updatePage() {
    if (page === 1) previous.style.visibility = "hidden";
    if (page === maxPage) next.style.visibility = "hidden"
    if (page < maxPage)next.style.visibility = "visible"
    if (page > 1) previous.style.visibility = "visible"
    isLoading = true;
    const response = await fetch('../api/api_get_user_items.php?page=' + page + "&nav=" + currentNav);
    const items = await response.json();
    if (items.length < 5) {
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
    img.src = "../uploads/medium/" + item['mainImage'] + ".png"
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

updatePage();
draw_pagination();
