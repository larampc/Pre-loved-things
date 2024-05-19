document.querySelector(".forgot-password").addEventListener("click", () => {
    document.querySelector(".forgot-popup").showModal()
    console.log("hey")
})

function onClick(event) {
    if (event.target === dialog) {
        dialog.close()
    }
}

const dialog = document.querySelector(".forgot-popup")
dialog.addEventListener("click", onClick)