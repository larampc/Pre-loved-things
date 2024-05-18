let pageNum = 1;
let isLoading = false;
const container = document;
const resultContainer =
    document.querySelector(".user-result");
const userSearch = document.querySelector(".user-search");
let tags = Array();
let request = 0;
let controller = new AbortController();

let searchres = document.querySelector('#search-user').value;

let all = false;
document.querySelector('#search-user').addEventListener("input", () => {
    searchres = document.querySelector('#search-user').value;
    getFilteredUsers(true);
})

async function getFilteredUsers(clean) {
    //if (isLoading) return;
    if (clean) cleanSearch();
    if (request > 0) {
        controller.abort();
        controller = new AbortController();
    }
    request++;
    isLoading = true;
    let response;
    try {
        response = await fetch('../api/api_get_users.php?page='+pageNum + "&search=" + searchres, {
            signal: controller.signal,
        });
    } catch (error) {
        request--;
        return;
    }
    console.log(response.url)
    const users = await response.json();
    let loader = document.querySelector(".loader-users");
    if (users.length === 0) {
        all = true;
        isLoading = false;
        if (loader) loader.style.display = 'none';
        request--;
        return;
    }
    if (request === 1) {
        users.forEach(user => resultContainer.appendChild(createUser(user)));
        loader = document.querySelector(".loader-users");
        if (loader) resultContainer.appendChild(loader);
        if (users.length < 3) loader.style.display = 'none';
        else loader.style.display = 'grid';
        isLoading = false;
    }
    request--;
}

document.onscroll = async () => {
    if (isLoading || all) return;
    if (
        window.scrollY > (document.body.offsetHeight - window.outerHeight - parseInt(window.getComputedStyle(userSearch).marginBottom, 10))
    ) {
        pageNum++;
        await getFilteredUsers(false);
    }
};

getFilteredUsers(true);

function createUser(user) {
    const main = document.createElement('a');
    main.href = "user.php?user_id=" + user['user_id'];
    main.className = "user-details";
    const img = document.createElement("img")
    img.src = "../uploads/profile_pics/" + user['image'] + ".png"
    main.appendChild(img);
    const div = document.createElement("div")
    div.className = "user-info"
    const name = document.createElement("p")
    name.innerText = user['name'];
    name.className = "name"
    div.appendChild(name);
    const email = document.createElement("p")
    email.innerText = user['email'];
    email.className = "email"
    div.appendChild(email);
    const phone = document.createElement("p")
    phone.innerText = user['phone'];
    phone.className = "phone"
    div.appendChild(phone);
    const sold = document.createElement("p")
    sold.innerText = user['sold'];
    sold.className = "sold"
    div.appendChild(sold);
    const purchase = document.createElement("p")
    purchase.innerText = user['buy'];
    purchase.className = "purchase"
    div.appendChild(purchase);
    main.appendChild(div);
    return main;
}

function cleanSearch() {
    pageNum = 1;
    all = false;
    const users = resultContainer.children;
    while (!resultContainer.firstElementChild.classList.contains("loader-users")) {
        resultContainer.removeChild(resultContainer.firstElementChild);
    }
    const loader = document.querySelector(".loader-users");
    loader.style.display = 'grid';
}
