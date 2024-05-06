function addTagFinal(section, first) {
    const tagNumber = section.id.split("-")[2]
    const optionNumber = document.getElementsByClassName(tagNumber.toString()).length
    if (!first) section.querySelector(".tag-options").lastChild.removeChild(section.querySelector(".tag-options").lastChild.lastChild)
    const label = document.createElement("label")
    label.classList.add(tagNumber.toString());
    const input = document.createElement("input")
    input.required = true
    input.type = "text"
    input.name = "option" + tagNumber + "[" + optionNumber +"]";
    const more = document.createElement("i")
    more.classList.add("material-symbols-outlined")
    more.classList.add("new-option")
    more.innerHTML = "add"
    more.title = "Add option"
    label.appendChild(input);
    label.appendChild(more)
    console.log(section.querySelector(".tag-options"))
    section.querySelector(".tag-options").appendChild(label)
    more.addEventListener("click", () => addTagFinal(label.parentElement.parentElement, false))
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
    const tagOptions = document.createElement("section")
    tagOptions.classList.add("tag-options")
    type.appendChild(select)
    div.appendChild(type)
    div.appendChild(tagOptions)
    document.querySelector(".new-tags").appendChild(div)
    select.addEventListener("input", () => {
        selectionType(select)
    })
})

function selectionType(option) {
    if (option.value === "select") {
        addTagFinal(option.parentElement.parentElement, true)
    }
    if (option.value === "free") {
        option.parentElement.parentElement.lastChild.remove();
    }
}