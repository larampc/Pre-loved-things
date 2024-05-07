const uploadSection = document.querySelector('section.item-image-uploads')
const imageAdder = document.querySelector('.image-upload-adder')

let lastLoadedImageId = 0;
let allImagesAreLoaded = false

if(imageAdder){
    imageAdder.addEventListener('click', () => {
        if (allImagesAreLoaded){
            uploadSection.insertBefore(createImageUploader(), imageAdder)
            allImagesAreLoaded = false
        }
    })
}

function onchangeHandler() {
    previewImage(this.id);
}
function previewImage(imageId) {

    const fileUploadInput = document.querySelector('.uploader#' + imageId)
    const imagePreviewer = fileUploadInput.parentElement

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
        imagePreviewer.style.backgroundImage = `url(${fileReaderEvent.target.result})`
    }
    const id = parseInt(imageId.substring(3))
    if(id > lastLoadedImageId) lastLoadedImageId = id
    allImagesAreLoaded = true
    const removeButton = createRemoveButton(id);
    if(removeButton !== null)
        fileUploadInput.parentNode.appendChild(removeButton)
}

function createRemoveButton(id) {
    if(document.querySelector('i#delete' + id) !== null) return null;
    const removeButton = document.createElement('i')
    removeButton.classList.add('material-symbols-outlined')
    removeButton.classList.add('bolder')
    removeButton.innerText = 'delete'
    removeButton.id = "delete" + id
    removeButton.addEventListener('click', shiftImages.bind(removeButton))

    return removeButton
}

function shiftImages() {
    const removedId = parseInt(this.id.substring(6))
    const fileUploadInputToRemove = document.querySelector('.uploader#img' + removedId)
    if(lastLoadedImageId === 1){
        fileUploadInputToRemove.value = ''
        fileUploadInputToRemove.parentElement.style.backgroundImage = ''
        return
    }

    uploadSection.removeChild(fileUploadInputToRemove.parentNode)

    for (let i = removedId + 1; i <= lastLoadedImageId; i++) {
        const fileUploadInputToShift = document.querySelector('.uploader#img' + i)
        const removeButtonToShift = fileUploadInputToShift.parentNode.querySelector('#delete' + i)
        fileUploadInputToShift.id = fileUploadInputToShift.name = "img" + (i-1)
        removeButtonToShift.id = 'delete' + (i-1)
    }
    lastLoadedImageId--

    if(removedId === 1){
        const mainImageHeader = document.createElement('h5')
        mainImageHeader.innerText = 'Main Image'
        const mainImageDiv = document.querySelector('.uploader#img1').parentElement
        mainImageDiv.classList.add('main-photo-upload')
        mainImageDiv.insertBefore(mainImageHeader, mainImageHeader.querySelector('i.upload-icon'))
    }
}
function createImageUploader() {
    const uploadDiv = document.createElement('div')
    uploadDiv.classList.add('photo-upload')

    const addPhotoIcon = document.createElement('i')
    addPhotoIcon.classList.add('material-symbols-outlined')
    addPhotoIcon.classList.add('bolder')
    addPhotoIcon.classList.add('upload-icon')
    addPhotoIcon.innerText = 'add_a_photo'

    const uploaderInput = document.createElement('input')
    uploaderInput.type = 'file'
    uploaderInput.name = uploaderInput.id = 'img' + (lastLoadedImageId+1) //changed
    uploaderInput.classList.add('uploader')
    uploaderInput.accept = 'image/*'
    uploaderInput.onchange = this.onchangeHandler

    uploadDiv.appendChild(addPhotoIcon)
    uploadDiv.appendChild(uploaderInput)

    return uploadDiv
}