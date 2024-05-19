let pageNum = 1
let isLoading = false
const resultContainer =
    document.querySelector(".searchresult")
let categories = Array()
let price_range = Array()
let conditions = Array()
let tags = Array()
let order = "recent"
let request = 0
let controller = new AbortController()

const searchres = document.querySelector('#searchbar').value

let all = false
let waitingMin = 0
let waitingMax = 0

const selectOrder = document.querySelector("#order")
selectOrder.addEventListener("input", async () => {
    order = selectOrder.value
    await getFilteredItems(true)
})

async function getFilteredItems(clean) {
    //if (isLoading) return
    if (clean) cleanSearch()
    if (request > 0) {
        controller.abort()
        controller = new AbortController()
    }
    request++
    isLoading = true
    let response
    try {
        response = await fetch('../api/api_search_range.php?' + encodeForAjax({page: pageNum,cat: categories, cond: conditions, price: price_range})
            + '&' + encodeForAjaxArray({tag: tags}) + '&' + encodeForAjax({search: searchres, order: order}), {
            signal: controller.signal,
        })
    } catch (error) {
        request--
        return
    }
    if (waitingMin !== 0 || waitingMax !== 0){
        request--
        return
    }
    const items = await response.json()
    let loader = document.querySelector(".loader")
    if (items.length === 0) {
        all = true
        isLoading = false
        if (loader) loader.style.display = 'none'
        request--
        return;
    }
    const response_currency = await fetch('../api/api_get_currency.php')
    const currency = await response_currency.json();
    if (request === 1) {
        items.forEach(item => resultContainer.appendChild(createItem(item, currency)));
        loader = document.querySelector(".loader");
        if (loader) resultContainer.appendChild(loader);
        if (items.length < 20) loader.style.display = 'none';
        else loader.style.display = 'grid';
        isLoading = false;
    }
    request--;
}

document.onscroll = async () => {
    if (isLoading || all) return;
    if (
        window.scrollY > (document.body.offsetHeight - window.outerHeight)
    ) {
        pageNum++;
        await getFilteredItems(false);
    }
};

const optionsPrice = document.querySelector('.price-input');
if (optionsPrice) {
    const min = optionsPrice.querySelector('.price-input .min-input');
    price_range.push(min.value)
    const max = optionsPrice.querySelector('.price-input .max-input');
    price_range.push(max.value)
    min.addEventListener("input", async() => {
        waitingMin++;
        price_range[0] = min.value
        setTimeout(async () => {
            waitingMin--;
            if (waitingMin === 0) await getFilteredItems(true);
            else cleanSearch();
        }, 200)
    })
    max.addEventListener("input", async() => {
        waitingMax++;
        price_range[1] = max.value
        setTimeout(async () => {
            waitingMax--;
            if (waitingMax === 0) await getFilteredItems(true);
            else cleanSearch();
        }, 200)
    })
}

getFilteredItems(true);

const optionsConditions = document.querySelector('#Condition');
if (optionsConditions) {
    const input = optionsConditions.querySelectorAll('#Condition input');
    input.forEach(function(elem) {
        elem.addEventListener("input", async () => {
            const index = conditions.indexOf(elem.name);
            if (index === -1 && elem.checked) conditions.push(elem.name);
            else if (!elem.checked) conditions.splice(index, 1);
            await getFilteredItems(true);
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
    img.src = "../uploads/medium/" + item['mainImage'] + ".png"
    img.alt = "item image"
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
        const index = tags.findIndex((elem) => elem[0] === tag.name + '$'+(tag.parentElement.parentElement.parentElement.classList[0].split('-')[1])  && elem[1] === tag.value);
        if (index === -1 && tag.type === "checkbox" && tag.checked) {
            const indexTag = tags.findIndex((elem) => elem[0] === tag.name + '$'+(tag.parentElement.parentElement.parentElement.classList[0].split('-')[1]));
            if (indexTag === -1) tags.push(Array(tag.name+'$'+(tag.parentElement.parentElement.parentElement.classList[0].split('-')[1]), Array(tag.value)));
            else tags[indexTag][1].push(tag.value)
        }
        else if (!tag.checked && tag.type === "checkbox") {
            const indexTag = tags.findIndex((elem) => elem[0] === tag.name +'$'+(tag.parentElement.parentElement.parentElement.classList[0].split('-')[1]));
            const toRemove = tags[indexTag][1].findIndex((elem) => elem === tag.value);
            tags[indexTag][1].splice(toRemove, 1);
            if (tags[indexTag][1].length === 0) tags.splice(indexTag, 1);
        }
        if (tag.type === "text") {
            tags = tags.filter((elem) => elem[0] !== tag.name+'$'+(tag.parentElement.parentElement.parentElement.classList[0].split('-')[1]))
            if (tag.value) tags.push(Array(tag.name+'$'+(tag.parentElement.parentElement.parentElement.classList[0].split('-')[1]), tag.value))
        }
        await getFilteredItems(true);
    }))

const categorySelector = document.querySelectorAll(".select-category")
categorySelector.forEach(category => category.addEventListener("input", async () => {
    const index = categories.findIndex((elem) => elem[0] === category.name && elem[1] === category.value);
    if (index === -1 && category.type === "checkbox" && category.checked) {
        const indexCategory = categories.findIndex((elem) => elem[0] === category.name);
        if (indexCategory === -1) categories.push(category.value);
        else category[indexCategory][1].push(category.value)
        document.querySelector(".category-"+ category.value)?.classList.remove("hide");
    }
    else if (!category.checked && category.type === "checkbox") {
        const indexCategory = categories.findIndex((elem) => elem === category.value);
        categories.splice(indexCategory, 1);
        document.querySelector(".category-"+ category.value)?.classList.add("hide");
    }
    await getFilteredItems(true);
}))

function cleanSearch() {
    pageNum = 1;
    all = false;
    while (!resultContainer.firstElementChild.classList.contains("loader")) {
        resultContainer.removeChild(resultContainer.firstElementChild);
    }
    const loader = document.querySelector(".loader");
    loader.style.display = 'grid';
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
