function addTagFinal(section, first) {
    const tagNumber = section.id.split("-")[2]
    const optionNumber = document.getElementsByClassName(tagNumber.toString()).length
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
    console.log(section.querySelector(".tag-options"))
    section.querySelector(".tag-options").appendChild(label)
    remove.addEventListener("click", () => {if (remove.parentElement.parentElement.children.length > 1) remove.parentElement.remove()})
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
        tagOptions.appendChild(more)
        more.addEventListener("click", () => addTagFinal(option.parentElement.parentElement, false))
        option.parentElement.parentElement.appendChild(tagOptions)
        addTagFinal(option.parentElement.parentElement, true)
    }
    if (option.value === "free") {
        option.parentElement.parentElement.lastChild.remove();
    }
}