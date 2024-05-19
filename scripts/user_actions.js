const actions = document.querySelectorAll('.confirm-action ')

let interval
const progressBar = document.createElement("div")
progressBar.classList.add("message-progress")
actions.forEach((action) => {
    action.addEventListener("click", async (elem) => {
    if (action.classList.contains("wait")) {
        elem.preventDefault()
        actions.forEach((others) => {
            if (others !== action) {
                changeButtonText(others)
            }
        })
    }
    else if (action.classList.contains("confirm-action")) {
        elem.preventDefault()
        actions.forEach((others) => {
            if (others !== action) {
                changeButtonText(others)
            }
        })
        action.innerHTML += "Are you sure?"
        action.classList.add("wait")
        action.parentElement.appendChild(progressBar)
        action.style.width = "100%"
        await new Promise(r => setTimeout(r, 300))
        action.classList.remove("wait")
        action.classList.remove("confirm-action")
        interval = setInterval(function() { changeButtonText(action)  }, 2700)
    }
})})

function changeButtonText(currAction)
{
    if (!currAction.classList.contains("confirm-action")){
        currAction.innerHTML = currAction.innerHTML.replace("Are you sure?", "")
        currAction.classList.add("confirm-action")
        clearInterval(interval)
        currAction.parentElement.removeChild(progressBar)
        currAction.style.width = "auto"
    }
}