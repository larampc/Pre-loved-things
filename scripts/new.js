const category = document.querySelector('#category')

if (category) {
    category.addEventListener("change", (event) => {
        options = document.getElementsByClassName(event.target.value);
        allOptions = document.getElementsByClassName('tags');
        for (let i = 0; i < allOptions.length; i++) {
            allOptions[i].style.display = "none";
        }

        if (options.length !== 0) {
            options[0].style.display = "block";
        }
    })
}