// JavaScript to display image previews
document.getElementById('image1').addEventListener('change', function(e) {
    displayImagePreview(e.target, 'preview1');
});

document.getElementById('image2').addEventListener('change', function(e) {
    displayImagePreview(e.target, 'preview2');
});

document.getElementById('image3').addEventListener('change', function(e) {
    displayImagePreview(e.target, 'preview3');
});

function displayImagePreview(input, previewId) {
    const preview = document.getElementById(previewId);
    const file = input.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.style.backgroundImage = `url('${e.target.result}')`;
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.backgroundImage = '';
    }
}
