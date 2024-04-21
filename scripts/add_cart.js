const cart = document.querySelector('.buy-item')

if (cart) {
    cart.addEventListener("click", () => {
        const buy = cart.querySelector('button')
        if (buy.innerHTML === "Buy now!") {
            fetch('addCart.php?' + encodeForAjax({item: buy.value}));
            buy.innerHTML = "Already in cart"
        }
    })
}