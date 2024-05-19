const rL = document.getElementsByClassName("rotateLogin")
const rR = document.getElementsByClassName("rotateRegister")
const fLR = document.getElementsByClassName("flipLoginRegister")
const tLR = document.getElementsByClassName("toggleLoginRegister")
const cRL = document.getElementsByClassName("checkboxLoginRegister")


if (rL.length > 0) {
    rL[0].addEventListener("click", function() {
        fLR[0].style.transform = "rotateY(180deg)"
        if (!cRL[0].checked) cRL[0].checked = !cRL[0].checked
    })
}
if (rR.length > 0) {
    rR[0].addEventListener("click", function() {
        fLR[0].style.transform = "rotateY(0deg)"
        if (cRL[0].checked) cRL[0].checked = !cRL[0].checked
    })
}
if (tLR.length > 0) {
    tLR[0].addEventListener("click", function() {
        cRL[0].checked = !cRL[0].checked
        if (cRL[0].checked) {
            fLR[0].style.transform = "rotateY(180deg)"
        } else fLR[0].style.transform = "rotateY(0deg)"
    })
}
