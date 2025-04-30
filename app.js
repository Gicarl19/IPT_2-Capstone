function toggleSubMenu(button) {
    const subMenu = button.nextElementSibling;
    
    subMenu.classList.toggle('active');
    
    const dropdownIcon = button.querySelector('.bi-chevron-compact-down');
    if (dropdownIcon) {
        if (subMenu.classList.contains('active')) {
            dropdownIcon.style.transform = 'rotate(180deg)';
        } else {
            dropdownIcon.style.transform = 'rotate(0)';
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const currentLocation = window.location.pathname;
    const sidebarLinks = document.querySelectorAll('#sidebar a');
    
    sidebarLinks.forEach(link => {
        if (link.getAttribute('href') === currentLocation) {
            link.style.backgroundColor = '#f0f4ff';
            link.style.color = '#5c7cfa';
            link.style.borderLeft = '3px solid #5c7cfa';
            
            const parentSubMenu = link.closest('.sub-menu');
            if (parentSubMenu) {
                parentSubMenu.classList.add('active');
                const dropdownBtn = parentSubMenu.previousElementSibling;
                if (dropdownBtn && dropdownBtn.classList.contains('dropdown-btn')) {
                    const dropdownIcon = dropdownBtn.querySelector('.bi-chevron-compact-down');
                    if (dropdownIcon) {
                        dropdownIcon.style.transform = 'rotate(180deg)';
                    }
                }
            }
        }
    });
});
