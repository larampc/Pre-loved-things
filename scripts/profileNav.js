let currentNav = "my"
let page = 1
let isLoading = false
let maxPage = 0
const urlParams = new URLSearchParams(window.location.search)
const user = urlParams.get("user_id")

async function openNav(option) {
    currentNav = option
    page = 1
    const buttons = document.querySelectorAll(".navbar *")
    for (let j = 0; j < buttons.length; j++) {
        if (buttons[j].classList.contains(option)) buttons[j].style.border = "2px solid #D9D9D9"
        else buttons[j].style.border = "none"
    }
    await get_pagination()
    await updatePage()
}

async function get_pagination() {
    const response_max = await fetch('../api/api_get_max_page.php?' + encodeForAjax({nav: currentNav, user: (user?user:"")}))
    maxPage = await response_max.json()
}

async function draw_pagination() {
    document.querySelector(".display-item nav")?.remove()
    const nav = document.createElement("nav")
    nav.className = "pagination"
    if (page-2 > 1) {
        const number = document.createElement("a")
        number.innerHTML = "1"
        number.className = "page-number"
        number.id = "1"
        number.addEventListener("click", async () => {
            page = 1
            await updatePage()
        })
        nav.appendChild(number)
        const ellips = document.createElement("a")
        ellips.innerHTML = "..."
        nav.appendChild(ellips)
    }
    for (let i = Math.max(1, page-2); i <= Math.min(maxPage, page+2); i++) {
        const number = document.createElement("a")
        number.innerHTML = i.toString()
        number.className = "page-number"
        number.id = i.toString()
        if (i === page) number.classList.add("current")
        number.addEventListener("click", async () => {
            page = i
            await updatePage()
        })
        nav.appendChild(number)
    }
    if (page+2 < maxPage) {
        const ellips = document.createElement("a")
        ellips.innerHTML = "..."
        nav.appendChild(ellips)
        const number = document.createElement("a")
        number.innerHTML = maxPage.toString()
        number.className = "page-number"
        number.id = maxPage.toString()
        number.addEventListener("click", async () => {
            page = maxPage
            await updatePage()
        })
        nav.appendChild(number)

    }
    document.querySelector(".display-item").appendChild(nav)
}

const previous = document.querySelector("#prev-btn")
const next = document.querySelector("#next-btn")
previous.addEventListener("click", async () => {
    if (page > 1) page--
    await updatePage()
})

next.addEventListener("click", async () => {
    if (page < maxPage) page++
    await updatePage()
})

async function updatePage() {
    await draw_pagination()
    if (page === 1) previous.style.visibility = "hidden"
    if (page >= maxPage) next.style.visibility = "hidden"
    if (page < maxPage) next.style.visibility = "visible"
    if (page > 1) previous.style.visibility = "visible"
    document.querySelectorAll(".page-number").forEach((elem) => {
        if (elem.id === page.toString())  elem.classList.add("current")
        else elem.classList.remove("current")
    } )

    isLoading = true
    const response = await fetch('../api/api_get_user_items.php?' + encodeForAjax({page:page, nav:currentNav, user:(user?user:"")}))
    const items = await response.json()
    if (items.length < 5) {
        isLoading = false
        if (items === 0) return
    }
    document.querySelector(".items").replaceWith( await getItems(items))
    isLoading = false
}

async function getItems(items) {
    const response_currency = await fetch('../api/api_get_currency.php')
    const currency = await response_currency.json()
    const result = document.createElement("div")
    result.classList.add("items")
    for (const item of items) {
        result.appendChild(await drawItem(item, currency))
    }
    return result
}

async function drawItem(item, currency) {
    const main = document.createElement('a')
    if (currentNav !== "my") {
        const response = await fetch('../api/api_get_purchase.php?item=' + item.id)
        const purchase_id = await response.json()
        main.href = "track_item.php?purchase=" + purchase_id
    }
    else main.href = "item.php?id=" + item.id
    main.className = "item"
    const img = document.createElement("img")
    img.src = "../uploads/medium/" + item['mainImage'] + ".png"
    img.alt = "item image"
    main.appendChild(img)
    const div = document.createElement("div")
    div.className = "item-info"
    const name = document.createElement("p")
    name.innerText = item.name
    name.className = "name"
    div.appendChild(name)
    const price = document.createElement("p")
    price.innerText = item.price + currency
    price.className = "price"
    div.appendChild(price)
    main.appendChild(div)
    return main
}

get_pagination().then(() => draw_pagination().then(() => updatePage()))

function setFadeNavbar(navbar) {
    const isScrollable = navbar.scrollWidth > navbar.clientWidth
    if (!isScrollable) {
        navbar.classList.remove('is-right-overflowing', 'is-left-overflowing')
        return
    }
    const isScrolledToRight = navbar.scrollWidth < navbar.clientWidth + navbar.scrollLeft + 1
    const isScrolledToLeft = isScrolledToRight ? false : navbar.scrollLeft === 0
    navbar.classList.toggle('is-right-overflowing', !isScrolledToRight)
    navbar.classList.toggle('is-left-overflowing', !isScrolledToLeft)
}

document.querySelector('.navbar')?.addEventListener('scroll', (e) => {
    const navbar = e.currentTarget
    setFadeNavbar(navbar)
});

if (document.querySelector('.navbar')) setFadeNavbar(document.querySelector('.navbar'))
