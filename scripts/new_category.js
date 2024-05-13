const selectType = document.querySelectorAll("select.type")
selectType.forEach((select) => select.addEventListener("input", () => {
    selectionType(select)
}))

function addTagFinal(section) {
    const tagNumber = section.id.split("-")[2]
    const options = document.getElementsByClassName(tagNumber.toString())
    let optionNumber = 0;
    if (options.length > 0) {
        const lastOption = options[options.length - 1].querySelector("input").name
        optionNumber = parseInt(lastOption[lastOption.length-2]) + 1;
    }
    const label = document.createElement("label")
    label.classList.add(tagNumber.toString());
    const input = document.createElement("input")
    input.required = true
    input.type = "text"
    input.name = "option" + tagNumber + "[" + optionNumber +"]";
    const remove = document.createElement("i")
    remove.classList.add("material-symbols-outlined")
    remove.classList.add("remove-option")
    remove.innerHTML = "close"
    remove.title = "Remove option"
    label.appendChild(input);
    label.appendChild(remove)
    section.querySelector(".tag-options").appendChild(label)
    remove.addEventListener("click", () => {remove.parentElement.remove()})
}

const addtag = document.querySelector(".add-tag")
addtag.addEventListener("click", () => {
    const tags = document.querySelectorAll(".new-tag")
    const lastTag = tags[tags.length-1]
    let tagNumber = 0;
    if (tags.length > 0) tagNumber = parseInt(lastTag.id[lastTag.id.length-1]) +1
    const div = document.createElement("div")
    div.classList.add("new-tag")
    div.id = "new-tag-" + tagNumber.toString()
    const label = document.createElement("label")
    const name = document.createElement("input")
    name.type = "text"
    name.name = "tags[" + tagNumber.toString() + "]"
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
    const deleteTag = document.createElement("i")
    deleteTag.classList.add("material-symbols-outlined")
    deleteTag.classList.add("delete-option")
    deleteTag.innerHTML = "delete"
    deleteTag.title = "Delete tag"
    select.appendChild(optionFree);
    select.appendChild(optionSelect);
    type.appendChild(select)
    div.appendChild(deleteTag)
    div.appendChild(type)
    document.querySelector(".new-tags").appendChild(div)
    select.addEventListener("input", () => {
        selectionType(select)
    })
    deleteTag.addEventListener("click", () => deleteTag.parentElement.remove())
})

function selectionType(option) {
    if (option.value === "select") {
        const tagOptions = document.createElement("section")
        tagOptions.classList.add("tag-options")
        const more = document.createElement("i")
        more.classList.add("material-symbols-outlined")
        more.classList.add("new-option")
        more.innerHTML = "add"
        more.title = "Add option"
        //tagOptions.appendChild(more)
        more.addEventListener("click", () => addTagFinal(option.parentElement.parentElement))
        option.parentElement.parentElement.appendChild(tagOptions)
        option.parentElement.parentElement.appendChild(more)
        addTagFinal(option.parentElement.parentElement)
    }
    if (option.value === "free") {
        option.parentElement.parentElement.lastElementChild.remove();
        option.parentElement.parentElement.lastElementChild.remove();
    }
}

const removes = document.querySelectorAll(".delete-option")
removes.forEach((elem) => elem.addEventListener("click", () => elem.parentElement.remove()))

const optionRemove = document.querySelectorAll(".remove-option")
optionRemove.forEach((elem) => elem.addEventListener("click", () => elem.parentElement.remove()))

const addOption = document.querySelectorAll(".new-option")
addOption.forEach((elem) => elem.addEventListener("click", () => addTagFinal(elem.parentElement, false)))
