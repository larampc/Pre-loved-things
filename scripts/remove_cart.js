const cart_page = document.querySelector('.cart-main')
if (cart_page) {
    const item = cart_page.querySelectorAll('a.item')
    add_remove_carts(item)
    const remove = cart_page.querySelectorAll('i')
    remove.forEach(function (elem) {
        elem.addEventListener("click",  async function (event) {
            event.preventDefault()
            const response = await fetch('../api/api_remove_cart.php?' + encodeForAjax({item: elem.value}))
            const item = await response.json()
            const ele = document.getElementById(elem.value)
            const seller = ele.parentElement.parentElement
            ele.remove()
            const sum = seller.querySelector('.num-items')
            const text = sum.innerHTML
            const arr = text.split(":")
            let number = Number(arr[1])
            sum.innerHTML = "Number items: " + String(number - 1)
            if (number === 1) seller.remove()
            const total = seller.querySelector('.total')
            const total_p = Number(total.innerHTML)
            total.innerHTML = total_p - Number(item.price)
            if (!document.querySelector('.seller')) {
                const no_item = document.createElement('p')
                no_item.innerHTML = 'You have no items'
                cart_page.appendChild(no_item)
            }
        })
    })

}

function add_remove_carts(item) {
    for (let i = 0; i < item.length; i++) {
        const icon = document.createElement('i')
        icon.className = "material-symbols-outlined"
        icon.innerText = "shopping_cart_off"
        item[i].id = item[i].href.split("=")[1]
        icon.value = item[i].href.split("=")[1]
        item[i].appendChild(icon);
    }
}