function loadSection(file, link) {
    const contentLoader = document.getElementById('content-loader');
    
    // Add a simple fade-out effect
    contentLoader.style.opacity = '0';

    setTimeout(() => {
        fetch(file)
            .then(response => {
                if (!response.ok) throw new Error('Page not found');
                return response.text();
            })
            .then(html => {
                contentLoader.innerHTML = html;
                contentLoader.style.opacity = '1';
                setActiveMenu(link);
            })
            .catch(err => {
                contentLoader.innerHTML = `<div class="alert alert-danger">Error: ${err.message}</div>`;
                contentLoader.style.opacity = '1';
            });
    }, 200);
}

function setActiveMenu(link) {
    document.querySelectorAll('.sidebar-menu a').forEach(a => a.classList.remove('active'));
    if(link) link.classList.add('active');
}

// Load default page on start
window.onload = () => {
    loadSection('pages/emp-dashboard.html', document.querySelector('.sidebar-menu a.active'));
};

function logout() {
    if(confirm("Are you sure you want to logout?")) {
        window.location.href = "../index.html"; // Go back to main site
    }
}