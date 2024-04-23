let coll = document.getElementsByClassName("collapsible");

for (let i = 0; i < coll.length; i++) {
    coll[i].addEventListener("click", function() {
        let content = this.nextElementSibling;
        if (content.style.display === "block") {
            content.style.display = "none";
        } else {
            content.style.display = "block";
            for (let j = 0; j < coll.length; j++) {
                if (j !== i)  {
                    let content2 = coll[j].nextElementSibling;
                    content2.style.display = "none";
                }
            }
        }
    });
}

let next = document.getElementsByClassName("next");

for (let i = 0; i < next.length; i++) {
    next[i].addEventListener("click", function() {
        let content = this.parentElement;
        content.style.display = "none";
        for (let j = 0; j < coll.length; j++) {
            if (j === i+1)  {
                let content2 = coll[j].nextElementSibling;
                content2.style.display = "block";
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