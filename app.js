<<<<<<< HEAD
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
=======
const toggleButton = document.getElementById('toggle-btn')
const sidebar = document.getElementById('sidebar')

function toggleSidebar(){
    sidebar.classList.toggle('close')
    toggleButton.classList.toggle('rotate')   

    Array.from(sidebar.getElementsByClassName('show')).forEach(ul =>{
        ul.classList.remove('show')
        ul.previousElementSibling.classList.remove('rotate')
    })
}

function toggleSubMenu(button){
    button.nextElementSibling.classList.toggle('show')
    button.classList.toggle('rotate')

    if(sidebar.classList.contains('close')){
        sidebar.classList.toggle('close')
        toggleButton.classList.toggle('rotate')
    }
}
>>>>>>> b218cc57b39be7e5a5c323e07d87a6b98a21dfb0
