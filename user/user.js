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


    function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const windowWidth = window.innerWidth;

    if (windowWidth <= 768) {
        // Mobile: Slide in/out
        sidebar.classList.toggle('active');
    } else {
        // Desktop: Shrink/Expand
        sidebar.classList.toggle('collapsed');
    }
}

// Optional: Close sidebar when clicking outside on mobile
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.querySelector('.mobile-toggle');
    
    if (window.innerWidth <= 768) {
        if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target) && sidebar.classList.contains('active')) {
            sidebar.classList.remove('active');
        }
    }
});
