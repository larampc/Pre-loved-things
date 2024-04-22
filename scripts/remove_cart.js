const cart_page = document.querySelector('.cartPage')
if (cart_page) {
    let item = cart_page.querySelectorAll('a.item')
    add_remove_carts(item)
    const remove = cart_page.querySelectorAll('i');
    remove.forEach(function (elem) {
        elem.addEventListener("click",  async function (event) {
            event.preventDefault()
            const response = await fetch('../api/api_remove_cart.php?' + encodeForAjax({item: elem.value}))
            const item = await response.json();
            const ele = document.getElementById(elem.value);
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
    for (let i = 0; i<item.length; i++) {
        const icon = document.createElement('i');
        icon.className = "material-symbols-outlined";
        icon.innerText = "shopping_cart_off";
        icon.value = item[i].id;
        item[i].appendChild(icon);
    }
}

//
// async function update(elem) {
//     const response = await fetch('remove_cart.php?' + encodeForAjax({item: elem.value}))
//     const items = await response.json();
//
//     items.sort(compare_items);
//     let sel = 0;
//     let num_items = 0;
//     let sum = 0;
//     cart_page.innerHTML = ''
//     const title = document.createElement('h2');
//     title.innerHTML = "Your cart"
//     cart_page.appendChild(title);
//     if (items.length === 0) {
//         const no_item = document.createElement('p')
//         no_item.innerHTML = 'You have no items'
//         cart_page.appendChild(no_item)
//         return;
//     }
//     let seller = document.createElement('section');
//     seller.className = "seller";
//     let seller_info = document.createElement('div');
//     seller_info.className = "seller-info";
//     let img = document.createElement('img');
//     img.className = "profile-pic";
//     img.src = "images/" + items[0].photoPath;
//     let par = document.createElement('p');
//     par.innerHTML = items[0].name;
//     seller_info.appendChild(img)
//     seller_info.appendChild(par)
//     seller.appendChild(seller_info);
//     title.innerHTML = "Your cart"
//     for (let i = 0; i < items.length; i++) {
//         if (sel === 0) sel = items[i].creator
//         if (items[i].creator !== sel && sel !== 0) {
//             seller.appendChild(get_total(num_items, sum))
//             cart_page.appendChild(seller)
//             seller = document.createElement('section');
//             seller.className = "seller";
//             seller_info = document.createElement('div');
//             seller_info.className = "seller-info";
//             img = document.createElement('img');
//             img.className = "profile-pic";
//             img.src = "images/" + items[i].photoPath;
//             par = document.createElement('p');
//             par.innerHTML = items[i].name;
//             seller_info.appendChild(img)
//             seller_info.appendChild(par)
//             seller.appendChild(seller_info);
//             sel = items[i].creator
//             num_items = 0
//             sum = 0
//         }
//         seller.appendChild(create_item(items[i]))
//         num_items++;
//         sum += items[i].price;
//     }
//     seller.appendChild(get_total(num_items, sum))
//     cart_page.appendChild(seller)
//     let item = cart_page.querySelectorAll('a.item')
//     add_remove_carts(item)
//     const remove = cart_page.querySelectorAll('i');
//     remove.forEach(function (elem) {
//         elem.addEventListener("click",  async function (event) {
//             event.preventDefault()
//             await update(elem)
//         })
//     })
// }
//
// function get_total(num_items, sum) {
//     const sum_class = document.createElement('div')
//     sum_class.className = "sum"
//     const item_n = document.createElement('p')
//     item_n.innerHTML = "Number items: " + num_items
//     sum_class.appendChild(item_n)
//     const sum_price = document.createElement('div')
//     sum_price.className = "sum-price"
//     const total = document.createElement('p')
//     total.innerHTML = "Total: "
//     const price = document.createElement('p')
//     price.innerHTML = sum
//     sum_price.appendChild(total)
//     sum_price.appendChild(price)
//     sum_class.appendChild(sum_price)
//     const buy_item = document.createElement('form')
//     buy_item.className = "buy-item"
//     const label = document.createElement('label')
//     const button = document.createElement('button')
//     button.className = "Buy"
//     button.type = "submit"
//     button.innerHTML = "Buy now!"
//     label.appendChild(button)
//     buy_item.appendChild(label)
//     sum_class.appendChild(buy_item)
//     return sum_class
// }
//

//
// function create_item(item) {
//     const a_item = document.createElement('a')
//     a_item.href = "item.php?id=" + item.id
//     a_item.className = "item"
//     a_item.id = item.id
//     const img_item = document.createElement('img')
//     img_item.src = "images/" + item.mainImage
//     a_item.appendChild(img_item)
//     const item_info = document.createElement('div')
//     item_info.className = "item-info"
//     const p_name = document.createElement('p')
//     p_name.className = 'name'
//     p_name.innerHTML = item.name
//     const p_price = document.createElement('p')
//     p_price.className = 'price'
//     p_price.innerHTML = item.price
//     item_info.appendChild(p_name)
//     item_info.appendChild(p_price)
//     a_item.appendChild(item_info)
//     return a_item
// }
//
// function compare_items(a, b) {
//     if (a.creator < b.creator) return -1;
//     else if (a.creator === b.creator) return 0;
//     else return 1;
// }