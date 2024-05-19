const uploadSection = document.querySelector('section.item-image-uploads')
const imageAdder = document.querySelector('.image-upload-adder')

let lastLoadedImageId = 0;
let allImagesAreLoaded = false
const maxImageNumber = 7;

const removeIcons = document.querySelectorAll('.delete-icon')
if (removeIcons) {
    lastLoadedImageId = removeIcons.length
    allImagesAreLoaded = true
}

if (lastLoadedImageId + 1 >= maxImageNumber) {
    imageAdder.style.display = "none";
}
if (lastLoadedImageId > 0 && uploadSection) {
    const mainPhotoInput = uploadSection.querySelector('.main-photo-upload input')
    if(mainPhotoInput) mainPhotoInput.required = false
}

if (imageAdder) {
    imageAdder.addEventListener('click', () => {
        if (allImagesAreLoaded) {
            uploadSection.insertBefore(createImageUploader(), imageAdder)
            allImagesAreLoaded = false
        }
        if (lastLoadedImageId + 1 === maxImageNumber) {
            imageAdder.style.display = "none";
        }
    })
}

async function onchangeHandler() {
    await previewImage(this.id);
}

async function previewImage(imageId) {
    const fileUploadInput = document.querySelector('.uploader#' + imageId)
    const imagePreviewer = fileUploadInput.parentElement
    if (!fileUploadInput.value) {
        return;
    }
    const image = fileUploadInput.files[0]

    if (!image.type.includes('image')) {
        addErrorMessage("Only images are allowed!")
        return;
    }

    if (image.size > 1_000_000) {
        fileUploadInput.value = ''
        addErrorMessage("Maximum upload size is 1MB!")
        return;
    }
    const addPhotoIcon = imagePreviewer.querySelector('i')
    imagePreviewer.removeChild(addPhotoIcon)

    const fileReader = new FileReader()
    fileReader.readAsDataURL(image)

    fileReader.onload = (fileReaderEvent) => {
        imagePreviewer.style.backgroundImage = `url(${fileReaderEvent.target.result})`
    }
    const id = parseInt(imageId.substring(3))
    if (id > lastLoadedImageId) lastLoadedImageId = id
    allImagesAreLoaded = true
    const removeButton = createRemoveButton(id);
    if (removeButton !== null) {
        fileUploadInput.parentNode.appendChild(removeButton)
        fileUploadInput.parentElement.draggable = true;
    }
}

function createRemoveButton(id) {
    if (document.querySelector('i#delete' + id) !== null) return null;
    const removeButton = document.createElement('i')
    removeButton.classList.add('material-symbols-outlined')
    removeButton.classList.add('bolder')
    removeButton.classList.add('delete-icon')
    removeButton.innerText = 'delete'
    removeButton.id = "delete" + id
    if(uploadSection){
        removeButton.addEventListener('click', shiftImages.bind(removeButton))
    }
    else {
        removeButton.addEventListener('click', removeUserImage)
    }

    return removeButton
}
function removeUserImage() {
    const fileUploadInputToRemove = document.querySelector('.uploader')
    fileUploadInputToRemove.value = ''
    fileUploadInputToRemove.parentElement.style.backgroundImage = ''
    fileUploadInputToRemove.parentElement.removeChild(fileUploadInputToRemove.parentElement.querySelector('i'))
    const hiddenInput = document.querySelector('input[type=hidden].image-data')
    if(hiddenInput) hiddenInput.parentElement.removeChild(hiddenInput)
    fileUploadInputToRemove.parentElement.insertBefore(createAddPhotoIcon(), fileUploadInputToRemove.parentElement.querySelector('.uploader'))
}

function shiftImages() {
    imageAdder.style.display = "block"
    const removedId = parseInt(this.id.substring(6))
    const fileUploadInputToRemove = document.querySelector('.uploader#img' + removedId)
    if (lastLoadedImageId === 1) {
        fileUploadInputToRemove.required = true
        fileUploadInputToRemove.value = ''
        fileUploadInputToRemove.parentElement.style.backgroundImage = ''
        fileUploadInputToRemove.parentElement.draggable = false
        fileUploadInputToRemove.parentElement.removeChild(fileUploadInputToRemove.parentElement.querySelector('i'))
        const hiddenInput = document.querySelector('input[type=hidden].image-data')
        if(hiddenInput) hiddenInput.parentElement.removeChild(hiddenInput)

        fileUploadInputToRemove.parentElement.insertBefore(createAddPhotoIcon(), fileUploadInputToRemove.parentElement.querySelector('input'))
        lastLoadedImageId = 0
        if (allImagesAreLoaded === false) {
            const uploadDivs = uploadSection.querySelectorAll('div.photo-upload')
            uploadSection.removeChild(uploadDivs[uploadDivs.length - 1])
        }
        allImagesAreLoaded = false
        return
    }

    uploadSection.removeChild(fileUploadInputToRemove.parentNode)

    for (let i = removedId + 1; i <= lastLoadedImageId; i++) {
        const hiddenInputToShift = document.querySelector('.image-data[name=hiddenimg' + i + ']')
        if (hiddenInputToShift) {
            hiddenInputToShift.name = "hiddenimg" + (i - 1)

        }
        const fileUploadInputToShift = document.querySelector('.uploader#img' + i)
        const removeButtonToShift = fileUploadInputToShift.parentNode.querySelector('#delete' + i)
        fileUploadInputToShift.id = fileUploadInputToShift.name = "img" + (i - 1)
        removeButtonToShift.id = 'delete' + (i - 1)
    }
    lastLoadedImageId--

    if (removedId === 1) {
        const mainImageHeader = document.createElement('h5')
        mainImageHeader.innerText = 'Main Image'
        const mainImageDiv = document.querySelector('.uploader#img1').parentElement
        mainImageDiv.classList.add('main-photo-upload')
        mainImageDiv.insertBefore(mainImageHeader, mainImageDiv.querySelector('.uploader'))
    }
}

function createAddPhotoIcon() {
    const addPhotoIcon = document.createElement('i')
    addPhotoIcon.classList.add('material-symbols-outlined')
    addPhotoIcon.classList.add('bolder')
    addPhotoIcon.classList.add('upload-icon')
    addPhotoIcon.innerText = 'add_a_photo'
    return addPhotoIcon;
}

function createImageUploader() {
    const uploadDiv = document.createElement('div')
    uploadDiv.classList.add('photo-upload')
    uploadDiv.addEventListener('dragstart', handleDragStart);
    uploadDiv.addEventListener('dragover', handleDragOver);
    uploadDiv.addEventListener('dragenter', handleDragEnter);
    uploadDiv.addEventListener('dragleave', handleDragLeave);
    uploadDiv.addEventListener('dragend', handleDragEnd);
    uploadDiv.addEventListener('drop', handleDrop);

    const addPhotoIcon = createAddPhotoIcon();

    const uploaderInput = document.createElement('input')
    uploaderInput.type = 'file'
    uploaderInput.name = uploaderInput.id = 'img' + (lastLoadedImageId + 1) //changed
    uploaderInput.classList.add('uploader')
    uploaderInput.accept = 'image/*'
    uploaderInput.onchange = this.onchangeHandler;

    const hiddenInput = document.createElement('input')
    hiddenInput.type = "hidden"
    hiddenInput.classList.add("image-data")
    hiddenInput.name = "hiddenimg" + (lastLoadedImageId + 1)
    hiddenInput.value = ''

    uploadDiv.appendChild(addPhotoIcon)
    uploadDiv.appendChild(uploaderInput)
    uploadDiv.appendChild(hiddenInput)
    return uploadDiv
}

if(uploadSection){
    uploadSection.querySelectorAll('div.photo-upload').forEach(m => {
        m.addEventListener('dragstart', handleDragStart);
        m.addEventListener('dragover', handleDragOver);
        m.addEventListener('dragenter', handleDragEnter);
        m.addEventListener('dragleave', handleDragLeave);
        m.addEventListener('dragend', handleDragEnd);
        m.addEventListener('drop', handleDrop);
    })

    let dragSrcEl;

    function handleDragStart(e) {
        this.style.opacity = '0.2';
        dragSrcEl = this;
        // e.dataTransfer.effectAllowed = 'move';
        // e.dataTransfer.setData('text/inputID', this.querySelector('.uploader').id);
        // e.dataTransfer.setData('text/inputName', this.querySelector('.uploader').name);
        // e.dataTransfer.setData('text/deleteID', this.querySelector('.delete-icon').id);
        // e.dataTransfer.setData('text/class', this.className);
        // const hiddenInput = this.querySelector('input[type=hidden]')
        // if(hiddenInput) {
        //     e.dataTransfer.setData('text/hiddenName', hiddenInput.name)
        //     e.dataTransfer.setData('text/hiddenValue', hiddenInput.value)
        // }
    }

    function handleDragEnd(e) {
        this.style.opacity = '0.75';
        let uploadDivs = uploadSection.querySelectorAll('div.photo-upload')
        uploadDivs.forEach(function (item) {
            item.classList.remove('over');
        });
    }

    function handleDragOver(e) {
        e.preventDefault();
        return false;
    }

    function handleDragEnter(e) {
        this.classList.add('over');
    }

    function handleDragLeave(e) {
        this.classList.remove('over');
    }

    function swap(node1, node2) {
        const afterNode2 = node2.nextElementSibling;
        const parent = node2.parentNode;
        node1.replaceWith(node2);
        parent.insertBefore(node1, afterNode2);
    }

    function handleDrop(e) {
        e.stopPropagation(); // stops the browser from redirecting.
        if (dragSrcEl !== this && this.draggable) {
            // dragSrcEl.querySelector('.uploader').id = this.querySelector('.uploader').id;
            // dragSrcEl.querySelector('.uploader').name = this.querySelector('.uploader').name;
            // dragSrcEl.querySelector('.delete-icon').id = this.querySelector('.delete-icon').id;
            // dragSrcEl.className = this.className;
            const position = dragSrcEl.compareDocumentPosition(this)
            if (position & Node.DOCUMENT_POSITION_FOLLOWING) {
                swap(dragSrcEl, this)
            } else if (position & Node.DOCUMENT_POSITION_PRECEDING) {
                swap(this, dragSrcEl)
            }

            // this.querySelector('.uploader').id = e.dataTransfer.getData('text/inputID');
            // this.querySelector('.uploader').name = e.dataTransfer.getData('text/inputName');
            // this.querySelector('.delete-icon').id = e.dataTransfer.getData('text/deleteID');
            // this.className = e.dataTransfer.getData('text/class');

            if (this.querySelector('h5')) {
                const header = this.querySelector('h5');
                this.removeChild(header)
                dragSrcEl.appendChild(header);
                dragSrcEl.classList.add( "main-photo-upload")
                this.classList.remove("main-photo-upload")
            } else if (dragSrcEl.querySelector('h5')) {
                const header = dragSrcEl.querySelector('h5')
                dragSrcEl.removeChild(header)
                this.appendChild(header);
                this.classList.add( "main-photo-upload")
                dragSrcEl.classList.remove("main-photo-upload")
            }
            //
            // for (let i = 1; i <= lastLoadedImageId; i++) {
            //     this.parentElement.appendChild(this.parentElement.querySelector('#img' + i).parentElement);
            // }
            //
            // if (!this.parentElement.querySelector('.photo-upload').draggable) {
            //     this.parentElement.appendChild(this.parentElement.querySelector('.photo-upload'));
            // }
            // this.parentElement.appendChild(this.parentElement.querySelector('.image-upload-adder'));
        }
        return false;
    }

}

function addErrorMessage(message) {
    const messageArticle = document.createElement('article')
    messageArticle.classList.add("error")
    const messageIcon = document.createElement('i')
    messageIcon.classList.add("material-symbols-outlined")
    messageIcon.classList.add("red")
    messageIcon.innerText = "error"
    const messageP = document.createElement('p')
    messageP.innerText = message
    const messageProgress = document.createElement('div')
    messageProgress.classList.add("message-progress")
    messageArticle.appendChild(messageIcon)
    messageArticle.appendChild(messageP)
    messageArticle.appendChild(messageProgress)

    document.getElementById("messages").appendChild(messageArticle)
}
