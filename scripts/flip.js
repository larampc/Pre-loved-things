let rL = document.getElementsByClassName("rotateLogin");
let rR = document.getElementsByClassName("rotateRegister");
let fLR = document.getElementsByClassName("flipLoginRegister");



if (rL.length > 0) {
    rL[0].addEventListener("click", function() {
        fLR[0].style.transform = "rotateY(180deg)";
    });
}
if (rR.length > 0) {
    rR[0].addEventListener("click", function() {
        fLR[0].style.transform = "rotateY(0deg)";
    })
}
