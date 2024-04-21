const like = document.querySelector('.like')

if (like) {
    const input = like.querySelector('button')
    input.addEventListener("click", () => {
        if (input.classList.contains("filled")) {
            fetch('dislike.php?' + encodeForAjax({item: input.value}));
            input.classList.remove("filled");
            input.classList.add("big");
        }
        else{
            fetch('like.php?'+ encodeForAjax({item: input.value}));
            input.classList.add("filled");
            input.classList.remove("big");
        }
    })
}