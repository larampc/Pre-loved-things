const cart = document.querySelector('.buy-item')

if (cart) {
    cart.addEventListener("click", () => {
        const buy = cart.querySelector('button')
        if (buy.innerHTML === "Buy now!") {
            fetch('../api/api_add_cart.php?' + encodeForAjax({item: buy.value}));
            buy.innerHTML = "Already in cart"
        }
    })
}