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

<<<<<<< Updated upstream
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
=======
<<<<<<< HEAD
function setActiveMenu(link) {
    document.querySelectorAll('.sidebar-menu a').forEach(a => a.classList.remove('active'));
    if(link) link.classList.add('active');
}

// Load header
fetch('slidebar.html')
  .then(response => response.text())
  .then(data => document.getElementById('slidbar').innerHTML = data)
  .catch(err => console.error('Header load error:', err));


=======
});
>>>>>>> 253c9df25905ed996392888320114c169267eb35
>>>>>>> Stashed changes
