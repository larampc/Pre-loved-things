let pageNum = 1;
let isLoading = false;
const container = document;
const resultContainer =
    document.querySelector(".searchresult");

async function fetchData() {
    if (isLoading) return;
    isLoading = true;
    const response = await fetch('../api/api_get_items.php?page=' + pageNum);
    const items = await response.json();
    if (items.length === 0) return;
    items.forEach(item => resultContainer.appendChild(createItem(item)));
    isLoading = false;
}

container.onscroll = () => {
    if (isLoading) return;
    if (
        Math.ceil(window.outerHeight) >=
        window.scrollY
    ) {
        console.log("hyy")
        pageNum++;
        fetchData();
    }
};

fetchData();