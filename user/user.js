// Global JS
document.addEventListener('DOMContentLoaded', () => {
    console.log("Dashboard ready!");
    // Future: Add sidebar toggle, notifications, etc.
});

// FOR CV //

const uploadInput = document.getElementById('uploadCV');

uploadInput.addEventListener('change', (event) => {

const file = event.target.files[0];

if (file) {
alert(`CV "${file.name}" selected for upload.`);
}

});