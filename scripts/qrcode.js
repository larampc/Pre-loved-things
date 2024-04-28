let qrcode = new QRCode(
    document.querySelector(".qrcode")
);

// Initial QR code generation
// with a default message

// Function to generate QR
// code based on user input
function generateQr(link) {
        qrcode.makeCode(link);
    }