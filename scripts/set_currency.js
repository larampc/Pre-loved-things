const selection = document.querySelector(".currency")

selection.addEventListener("change", (event) => {
    const button = document.querySelector(".change-currency")
    button.click()
})