function loadSection(file, link) {
    fetch(file)
        .then(response => response.text())
        .then(html => {
            const content = document.getElementById('content');
            
            // Fade out current content
            content.classList.add('fade-out');
            
            setTimeout(() => {
                content.innerHTML = html;       // Replace content
                content.classList.remove('fade-out');
                content.classList.add('fade-in');  // Fade in new content

                // Remove fade-in class after animation completes
                setTimeout(() => content.classList.remove('fade-in'), 500);
            }, 300);

            setActiveMenu(link);
        })
        .catch(err => console.error('Error loading section:', err));
}

function setActiveMenu(link) {
    document.querySelectorAll('.sidebar-menu a').forEach(a => a.classList.remove('active'));
    if(link) link.classList.add('active');
}
