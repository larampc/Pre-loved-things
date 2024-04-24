let coll = document.getElementsByClassName("collapsible");

if (coll.length > 0) {
    coll[0].style.borderRadius = "1rem 1rem 0 0";
    coll[0].nextElementSibling.style.maxHeight = coll[0].nextElementSibling.scrollHeight +"px"
}

for (let i = 0; i < coll.length; i++) {
    coll[i].addEventListener("click", function() {
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
    });
}

let next = document.getElementsByClassName("next");

for (let i = 0; i < next.length; i++) {
    next[i].addEventListener("click", function() {
        let content = this.parentElement;
        content.previousElementSibling.style.transitionDelay = "500ms"
        content.style.maxHeight = "0"
        content.previousElementSibling.style.borderRadius = "1rem";
        for (let j = 0; j < coll.length; j++) {
            if (j === i+1)  {
                let content2 = coll[j].nextElementSibling;
                coll[j].style.transitionDelay = "0ms"
                content2.style.maxHeight = content2.scrollHeight+"px"
                coll[j].style.borderRadius = "1rem 1rem 0 0";
            }
        }
    });
}

let options = document.getElementsByClassName("option")

for (let k = 0; k < options.length; k++) {
    options[k].addEventListener("click", function () {
        let display = document.querySelector("div#" +  this.id);
        console.log(display)
        display.style.display = "block"
        for (let j = 0; j < options.length; j++) {
            if (j !== k) {
                let display2 = document.querySelector("div#" +  options[j].id);
                display2.style.display = "none"
            }
        }
    })
}