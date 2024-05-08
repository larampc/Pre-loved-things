let pageNum = 1;
let isLoading = false;
const container = document;
const resultContainer =
    document.querySelector(".searchresult");
let categories = Array();
let price_range = Array();
let conditions = Array();
let tags = Array();
let order = "recent"
const mainCat = document.querySelector('.category-search');
categories.push(mainCat.innerHTML)
if (mainCat.innerHTML) {
    const categorySelector = document.querySelector('.dropdown-content ' +'#' + mainCat.innerHTML)
    categorySelector.selected = true;
}

console.log(document.querySelector('#searchbar').value)
const searchres = document.querySelector('#searchbar').value;

let all = false;

const selectOrder = document.querySelector("#order")
selectOrder.addEventListener("input", async () => {
    order = selectOrder.value;
    console.log(order)
    cleanSearch();
    await getFilteredItems();
})

async function getFilteredItems() {
    if (isLoading) return;
    isLoading = true;
    const response = await fetch('../api/api_search_range.php?page=' + pageNum + '&' + encodeForAjax({cat: categories, cond: conditions, price: price_range}) + '&' + encodeForAjaxArray({tag: tags}) + '&search=' + searchres + '&order=' + order)
    const items = await response.json();
    let loader = document.querySelector(".loader");
    if (items.length === 0) {
        all = true;
        isLoading = false;
        if (loader) loader.style.display = 'none';
        return;
    }
    const response_currency = await fetch('../api/api_get_currency.php')
    const currency = await response_currency.json();
    items.forEach(item => resultContainer.appendChild(createItem(item, currency)));
    loader = document.querySelector(".loader");
    if (loader) {
        resultContainer.appendChild(loader);
        console.log("AAAAAAAAAAAAAAAAAA")
        console.log(loader)
    }
    if (items.length < 18) loader.style.display = 'none';
    else loader.style.display = 'grid';
    isLoading = false;
}

document.onscroll = async () => {
    if (isLoading || all) return;
    if (
        window.scrollY > (document.body.offsetHeight - window.outerHeight)
    ) {
        pageNum++;
        await getFilteredItems();
    }
};

const optionsPrice = document.querySelector('.price-input');
if (optionsPrice) {
    const min = optionsPrice.querySelector('.price-input .min-input');
    price_range.push(min.value)
    const max = optionsPrice.querySelector('.price-input .max-input');
    price_range.push(max.value)
    min.addEventListener("input", async() => {
        price_range[0] = min.value
        cleanSearch()
        await getFilteredItems();
    })
    max.addEventListener("input", async() => {
        price_range[1] = max.value
        cleanSearch()
        await getFilteredItems();
    })
}

getFilteredItems();

const optionsConditions = document.querySelector('#Condition');
if (optionsConditions) {
    const input = optionsConditions.querySelectorAll('#Condition input');
    input.forEach(function(elem) {
        elem.addEventListener("input", async () => {
            const index = conditions.indexOf(elem.name);
            if (index === -1 && elem.checked) conditions.push(elem.name);
            else if (!elem.checked) conditions.splice(index, 1);
            cleanSearch()
            await getFilteredItems();
        });
    });
}

function encodeForAjaxArray(data) {
    return Object.keys(data).map(function (k) {
        let str = ""
        data[k].forEach((elem) => str += "-"+elem)
        return encodeURIComponent(k) + '=' + encodeURIComponent(str)
    }).join('&')
}

function createItem(item, symbol) {
    const main = document.createElement('a');
    main.href = "item.php?id=" + item.id;
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
    price.innerText = item.price + symbol;
    price.className = "price"
    div.appendChild(price);
    main.appendChild(div);
    return main;
}

const tags2 = document.querySelectorAll('.tag input');

tags2.forEach(tag => tag.addEventListener("input", async () => {
        const index = tags.findIndex((elem) => elem[0] === tag.name && elem[1] === tag.value);
        if (index === -1 && tag.type === "checkbox" && tag.checked) {
            const indexTag = tags.findIndex((elem) => elem[0] === tag.name);
            if (indexTag === -1) tags.push(Array(tag.name, Array(tag.value)));
            else tags[indexTag][1].push(tag.value)
        }
        else if (!tag.checked && tag.type === "checkbox") {
            const indexTag = tags.findIndex((elem) => elem[0] === tag.name);
            const toRemove = tags[indexTag][1].findIndex((elem) => elem === tag.value);
            tags[indexTag][1].splice(toRemove, 1);
            if (tags[indexTag][1].length === 0) tags.splice(indexTag, 1);
        }
        if (tag.type === "text") {
            tags = tags.filter((elem) => elem[0] !== tag.name)
            if (tag.value) tags.push(Array(tag.name, tag.value))
        }
        cleanSearch()
        await getFilteredItems();
    }))

function cleanSearch() {
    pageNum = 1;
    all = false;
    resultContainer.innerHTML = "";
    let loader = document.querySelector(".loader");
    const loaderDIV = document.createElement("div");
    loaderDIV.classList.add("loader");
    if (loader == null) resultContainer.appendChild(loaderDIV);
}

const filter = document.getElementsByClassName("filter");

function openFilters() {
    filter[0].style.width = "12rem";
    filter[0].style.padding = "1rem 1rem 2rem";
    filter[0].style.marginRight = "1rem";
}

function closeFilters() {
    filter[0].style.width = "0";
    filter[0].style.padding = "0";
    filter[0].style.margin = "0";
}
