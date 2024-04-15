var prevScrollpos = window.scrollY;

const header = document.querySelector("header");
var headerBottom = header.offsetTop + header.offsetHeight;
var hidden = false;

window.onscroll = function() {
    header.style.transitionDuration = "0s";
    var currentScrollPos = window.scrollY;
  
    if (prevScrollpos <= currentScrollPos) {
        header.style.top = (prevScrollpos-currentScrollPos).toString() + "px";
        if (prevScrollpos <= currentScrollPos-(header.offsetHeight/2) && currentScrollPos > header.offsetHeight) hidden = true;
        else hidden = false;
    }
    else{  
        hidden = false;
        header.style.top = "0";
    }
}

window.onscrollend = function() {
    header.style.transitionDuration = "0.2s";
    if (hidden) {
        prevScrollpos = window.scrollY - header.offsetHeight;
        header.style.top = (-header.offsetHeight).toString() + "px";
    }
    else {
        prevScrollpos = window.scrollY;
        header.style.top = "0";
    }
}