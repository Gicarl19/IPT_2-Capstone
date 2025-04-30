function showForm(formId) {
    document.querySelectorAll('.form-box').forEach(form => {
        form.classList.remove('active');
    });
    
    document.getElementById(formId).classList.add('active');
}

document.addEventListener('DOMContentLoaded', function() {
    const activeForm = document.querySelector('.form-box.active');
    if (!activeForm) {
        showForm('login-form');
    }
});