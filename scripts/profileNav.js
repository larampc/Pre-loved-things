function openNav(option) {
    let i;
    const x = document.querySelectorAll(".display-item .items");
    const buttons = document.querySelectorAll(".navbar *");
    for (j = 0; j < buttons.length; j++) {
        if (buttons[j].classList.contains(option)) buttons[j].style.border = "2px solid #D9D9D9";
        else buttons[j].style.border = "none";
    }

    for (i = 0; i < x.length; i++) {
        x[i].style.maxHeight = "0";
    }
    document.getElementById(option).style.transitionDelay = "0ms"
    document.getElementById(option).style.maxHeight = document.getElementById(option).scrollHeight+"px"
    document.getElementById(option).style.display = "flex";
}
