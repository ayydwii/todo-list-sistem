// Toggle Sidebar Function
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const content = document.querySelector('.content');
    sidebar.classList.toggle('open');
    
    if (sidebar.classList.contains('open')) {
        content.style.marginLeft = '260px';
    } else {
        content.style.marginLeft = '0';
    }
}

// Auto close sidebar on desktop
window.addEventListener('resize', function() {
    const sidebar = document.getElementById('sidebar');
    const content = document.querySelector('.content');
    if (window.innerWidth > 768) {
        sidebar.classList.remove('open');
        content.style.marginLeft = '260px';
    } else {
        content.style.marginLeft = '0';
    }
});

// Close sidebar when clicking outside
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const hamburger = document.querySelector('.hamburger');
    
    if (window.innerWidth <= 768 && 
        !sidebar.contains(event.target) && 
        !hamburger.contains(event.target)) {
        sidebar.classList.remove('open');
        document.querySelector('.content').style.marginLeft = '0';
    }
});

// Load active page in sidebar
document.addEventListener('DOMContentLoaded', function() {
    const currentPath = window.location.pathname;
    const links = document.querySelectorAll('.sidebar a');
    
    links.forEach(link => {
        if (link.href === currentPath || link.getAttribute('href') === currentPath.split('/').pop()) {
            link.classList.add('active');
        }
    });
});

