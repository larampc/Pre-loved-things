let prevScrollpos = window.scrollY

const header = document.querySelector("header")
let hidden = false

window.onscroll = function() {
    header.style.transitionDuration = "0s"
    let currentScrollPos = window.scrollY
  
    if (prevScrollpos <= currentScrollPos) {
        header.style.top = (prevScrollpos-currentScrollPos).toString() + "px"
        if (prevScrollpos <= currentScrollPos-(header.offsetHeight/2) && currentScrollPos > header.offsetHeight) {
            hidden = true
            header.className = "header-hidden"
        }
        else {
            hidden = false
            header.className = "header-visible"
        }
    }
    else{  
        hidden = false
        header.style.top = "0"
        header.className = "header-visible"
    }
}

window.onscrollend = function() {
    header.style.transitionDuration = "0.2s"
    if (hidden) {
        prevScrollpos = window.scrollY - header.offsetHeight
        header.style.top = (-header.offsetHeight).toString() + "px"
        header.className = "header-hidden"
    }
    else {
        prevScrollpos = window.scrollY
        header.style.top = "0"
        header.className = "header-visible"
    }
}