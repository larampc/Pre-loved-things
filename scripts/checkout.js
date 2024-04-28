let coll = document.getElementsByClassName("collapsible");

if (coll.length > 0) {
    coll[0].style.borderRadius = "1rem 1rem 0 0";
    coll[0].nextElementSibling.style.maxHeight = coll[0].nextElementSibling.scrollHeight +"px"
}

for (let i = 0; i < coll.length; i++) {
    coll[i].addEventListener("click", function() {
        if (coll[i].parentElement.classList.contains("current") || coll[i].parentElement.classList.contains("done")) {
            let content = this.nextElementSibling;
            if (content.style.maxHeight !== "0px" && content.style.maxHeight) {
                this.style.transitionDelay = "500ms"
                content.style.maxHeight = "0"
                this.style.borderRadius = "1rem";
            } else {
                this.style.transitionDelay = "0ms"
                content.style.maxHeight = content.scrollHeight+"px"
                this.style.borderRadius = "1rem 1rem 0 0";
                for (let j = 0; j < coll.length; j++) {
                    if (j !== i)  {
                        let content2 = coll[j].nextElementSibling;
                        coll[j].style.transitionDelay = "500ms"
                        content2.style.maxHeight = "0"
                        coll[j].style.borderRadius = "1rem";
                    }
                }
            }
        }
    });
}

let next = document.getElementsByClassName("next");
let error = false;
for (let i = 0; i < next.length; i++) {
    next[i].addEventListener("click", function() {
        checkInput(this.parentElement)
        if (error) return;
        let content = this.parentElement;
        if (!content.parentElement.classList.contains("done")) content.parentElement.classList.add("done")
        content.previousElementSibling.style.transitionDelay = "500ms"
        content.style.maxHeight = "0"
        content.previousElementSibling.style.borderRadius = "1rem";
        for (let j = 0; j < coll.length; j++) {
            if (j === i+1)  {
                let content2 = coll[j].nextElementSibling;
                coll[j].parentElement.classList.add("current")
                coll[j].style.transitionDelay = "0ms"
                content2.style.maxHeight = content2.scrollHeight+"px"
                coll[j].style.borderRadius = "1rem 1rem 0 0";
            }
            else {
                if (content.parentElement.classList.contains("done")) coll[j].parentElement.classList.remove("current")
            }
        }
    });
}

let options = document.getElementsByClassName("option")

for (let k = 0; k < options.length; k++) {
    options[k].addEventListener("click", function () {
        let display = document.querySelector("div#" +  this.id);
        display.style.display = "block"
        for (let j = 0; j < options.length; j++) {
            let not = document.querySelector("div#" +  options[j].id).querySelectorAll("input")
            if (j !== k) {
                for (let i  = 0; i < not.length; i++) {
                    console.log(not[i])
                    not[i].required = false;
                }
                let display2 = document.querySelector("div#" +  options[j].id);
                display2.style.display = "none"
            }
            else {
                for (let i  = 0; i < not.length; i++) {
                    not[i].required = true
                }
            }
        }
    })
}

function checkInput(elem) {
    let inputs = elem.querySelectorAll("input")
    for (let j =0; j < inputs.length; j++) {
        if (inputs[j].required && inputs[j].value === "") {
            let article = document.createElement('article')
            article.classList.add("error")
            let icon = document.createElement('i')
            icon.classList.add("material-symbols-outlined")
            icon.classList.add("red")
            icon.innerHTML = "error"
            let progress = document.createElement("div")
            progress.classList.add("message-progress")
            article.appendChild(icon)
            let message = document.createElement("p")
            message.innerHTML = "Fields missing"
            article.appendChild(message)
            article.appendChild(progress)
            document.querySelector('#messages').appendChild(article)
            error = true;
            return;
        }
    }
    error = false;
}

document.addEventListener("invalid", (event) =>
{
    event.preventDefault()
    if (!error) checkInput(document.querySelector('.current'))
    if (!error) document.querySelector('.current .next').click()
}, {capture:true})