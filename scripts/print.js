const button = document.getElementById("print")

function setPrint() {
    const closePrint = () => {
        document.body.removeChild(this)
    }
    this.contentWindow.onbeforeunload = closePrint
    this.contentWindow.onafterprint = closePrint
    this.contentWindow.print()
}

if (button) {
    button.addEventListener("click", () => {
        const hideFrame = document.createElement("iframe")
        hideFrame.onload = setPrint
        hideFrame.style.display = "none" // hide iframe
        hideFrame.src = button.className
        document.body.appendChild(hideFrame)
    })
}
