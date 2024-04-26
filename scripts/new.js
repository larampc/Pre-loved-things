const category = document.querySelector('#category')

if (category) {
    category.addEventListener("change", (event) => {
        if (event.target.value) options = document.getElementsByClassName(event.target.value);
        else options = document.getElementsByClassName("other");
        console.log(options[0])
        allOptions = document.getElementsByClassName('tags');
        for (let i = 0; i < allOptions.length; i++) {
            allOptions[i].style.display = "none";
        }

        if (options.length !== 0) {
            options[0].style.display = "block";
        }
    })
}