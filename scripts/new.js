const category = document.querySelector('#category')

if (category) {
    category.addEventListener("change", (event) => {
        if (event.target.value) options = document.getElementsByClassName(event.target.value)
        const allOptions = document.getElementsByClassName('tags')
        for (let i = 0; i < allOptions.length; i++) {
            if (!allOptions[i].classList.contains("other")) allOptions[i].style.display = "none"
        }
        if (options.length !== 0) {
            options[0].style.display = "block"
        }
    })
}