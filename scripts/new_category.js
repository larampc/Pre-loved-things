const op = document.querySelectorAll(".type")
let i = 0;

for (let i = 0; i< op.length;i++) {
    op[i].addEventListener("input", () => {
        selectionType(op[i])
    })
}

function addTag(opt, j) {
    const all = document.querySelectorAll(".new-option")
    const more = all[all.length-1]
    more.addEventListener("click", () => {
        const i = opt.parentElement.parentElement.parentElement.id.split("-")[2]
        opt.parentElement.parentElement.lastChild.removeChild(more);
        console.log(more)
        const label2 = document.createElement("label")
        label2.classList.add(i.toString());
        const tagop2 = document.createElement("input")
        tagop2.required = true
        tagop2.type = "text"
        tagop2.name = "option" + i + "[" + j +"]";
        const but2 = document.createElement("i")
        but2.classList.add("material-symbols-outlined")
        but2.classList.add("new-option")
        but2.innerHTML = "add"
        but2.title = "Add option"
        label2.appendChild(tagop2);
        label2.appendChild(but2)
        opt.parentElement.parentElement.appendChild(label2)
        j++
        addTag(opt, j)
    })
}

const addtag = document.querySelector(".add-tag")
addtag.addEventListener("click", () => {
    const tags = document.querySelectorAll(".new-tag").length
    const div = document.createElement("div")
    div.classList.add("new-tag")
    div.id = "new-tag-" + tags.toString()
    const label = document.createElement("label")
    const name = document.createElement("input")
    name.type = "text"
    name.name = "tags[" + tags.toString() + "]"
    name.required = true
    label.innerHTML = "Tag name"
    label.appendChild(name)
    div.appendChild(label)
    const type = document.createElement("label")
    const select = document.createElement("select")
    const optionFree = document.createElement("option")
    const optionSelect = document.createElement("option")
    select.classList.add("type")
    optionFree.value = "free";
    optionFree.innerHTML = "Free"
    optionSelect.innerHTML = "Select"
    optionSelect.value = "select";
    select.appendChild(optionFree);
    select.appendChild(optionSelect);
    type.appendChild(select)
    div.appendChild(type)
    document.querySelector(".new-tags").appendChild(div)
    select.addEventListener("input", () => {
        selectionType(select)
    })
})


function selectionType(option) {
    let j = 0;
    if (option.value === "select") {
        const i = option.parentElement.parentElement.id.split("-")[2]
        const label = document.createElement("label")
        label.classList.add(i.toString());
        const tagop = document.createElement("input")
        tagop.type = "text"
        tagop.required = true
        tagop.name = "option" + i + "[" + j +"]";
        const but = document.createElement("i")
        but.classList.add("material-symbols-outlined")
        but.classList.add("new-option")
        but.innerHTML = "add"
        but.title = "Add option"
        label.appendChild(tagop);
        label.appendChild(but);
        const options = document.createElement("section");
        options.classList.add("tag-options");
        options.appendChild(label);
        option.parentElement.parentElement.appendChild(options)
        j++
        addTag(tagop, j)
    }
    if (option.value === "free") {
        const lab = document.getElementsByClassName(i.toString())
        console.log(option)
        option.parentElement.parentElement.lastChild.remove();
    }
}