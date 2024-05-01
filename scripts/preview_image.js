const uploadSection = document.querySelector('section.item-image-uploads')
const imageAdder = document.querySelector('.item-image-adder')

let lastLoadedImageId = 0;
let allImagesAreLoaded = false

imageAdder.addEventListener('click', () => {
    if (allImagesAreLoaded){
        uploadSection.insertBefore(createItemImageUploader(), imageAdder)
        allImagesAreLoaded = false
    }
})

function onchangeHandler() {
    upload(this.id);
}
function upload(imageId) {

    const fileUploadInput = document.querySelector('.item-uploader#' + imageId)
    const itemPicture = fileUploadInput.parentElement

    if (!fileUploadInput.value) {
        return;
    }
    const image = fileUploadInput.files[0]

    if (!image.type.includes('image')) {
        return alert('Only images are allowed!')
    }

    if (image.size > 10_000_000) {
        return alert('Maximum upload size is 10MB!')
    }

    const fileReader = new FileReader()
    fileReader.readAsDataURL(image)

    fileReader.onload = (fileReaderEvent) => {
        itemPicture.style.backgroundImage = `url(${fileReaderEvent.target.result})`
    }

    lastLoadedImageId = parseInt(imageId.substring(3))
    allImagesAreLoaded = true

    fileUploadInput.parentNode.appendChild(createRemoveButton(lastLoadedImageId))
}

function createRemoveButton(id) {
    const removeButton = document.createElement('i')
    removeButton.classList.add('material-symbols-outlined')
    removeButton.innerText = 'delete'
    removeButton.id = "delete" + id.toString()
    removeButton.addEventListener('click', shiftImages.bind(removeButton))

    return removeButton
}

function shiftImages() {
    const removedId = parseInt(this.id.substring(6))
    const fileUploadInputToRemove = document.querySelector('.item-uploader#img' + removedId)
    if(lastLoadedImageId === 1){
        fileUploadInputToRemove.value = ''
        fileUploadInputToRemove.parentElement.style.backgroundImage = ''
        return
    }

    uploadSection.removeChild(fileUploadInputToRemove.parentNode)

    for (let i = removedId + 1; i <= lastLoadedImageId; i++) {
        const fileUploadInputToShift = document.querySelector('.item-uploader#img' + i)
        const removeButtonToShift = fileUploadInputToShift.parentNode.querySelector('#delete' + i)
        fileUploadInputToShift.id = fileUploadInputToShift.name = "img" + (i-1)
        removeButtonToShift.id = 'delete' + (i-1)
    }
    lastLoadedImageId--

    if(removedId === 1){
        const mainImageHeader = document.createElement('h5')
        mainImageHeader.innerText = 'Main Image'
        const mainImageDiv = document.querySelector('.item-uploader#img1').parentElement
        mainImageDiv.classList.add('main-item-upload')
        mainImageDiv.insertBefore(mainImageHeader, mainImageHeader.querySelector('i.upload-icon'))
    }
}
function createItemImageUploader() {
    const itemUploadDiv = document.createElement('div')
    itemUploadDiv.classList.add('item-upload')

    const addPhotoIcon = document.createElement('i')
    addPhotoIcon.classList.add('material-symbols-outlined')
    addPhotoIcon.classList.add('upload-icon')
    addPhotoIcon.innerText = 'add_a_photo'

    const itemUploaderInput = document.createElement('input')
    itemUploaderInput.type = 'file'
    itemUploaderInput.name = itemUploaderInput.id = 'img' + (lastLoadedImageId+1) //changed
    itemUploaderInput.classList.add('item-uploader')
    itemUploaderInput.accept = 'image/*'
    itemUploaderInput.onchange = this.onchangeHandler

    itemUploadDiv.appendChild(addPhotoIcon)
    itemUploadDiv.appendChild(itemUploaderInput)

    return itemUploadDiv
}