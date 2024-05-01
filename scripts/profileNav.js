function openNav(option) {
    let i;
    const x = document.querySelectorAll(".display-item .items");
    for (i = 0; i < x.length; i++) {
        x[i].style.maxHeight = "0"
        x[i].style.borderRadius = "1rem";
    }
    document.getElementById(option).style.transitionDelay = "0ms"
    document.getElementById(option).style.maxHeight = document.getElementById(option).scrollHeight+"px"
    document.getElementById(option).style.display = "flex";
}
