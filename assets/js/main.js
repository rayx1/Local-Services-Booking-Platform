document.addEventListener('DOMContentLoaded', function () {
    var menuToggle = document.getElementById('menuToggle');
    var siteNav = document.getElementById('siteNav');

    if (menuToggle && siteNav) {
        menuToggle.addEventListener('click', function () {
            siteNav.classList.toggle('open');
        });
    }

    var forms = document.querySelectorAll('[data-validate="true"]');

    forms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
            var requiredFields = form.querySelectorAll('[required]');
            var isValid = true;

            requiredFields.forEach(function (field) {
                if (!field.value.trim()) {
                    field.style.borderColor = '#dc2626';
                    isValid = false;
                } else {
                    field.style.borderColor = '#dbe3ef';
                }
            });

            var password = form.querySelector('input[name="password"]');
            var confirmPassword = form.querySelector('input[name="confirm_password"]');

            if (password && confirmPassword && password.value !== confirmPassword.value) {
                confirmPassword.style.borderColor = '#dc2626';
                alert('Password and confirm password must match.');
                isValid = false;
            }

            if (!isValid) {
                event.preventDefault();
                if (!password || !confirmPassword || password.value === confirmPassword.value) {
                    alert('Please fill in all required fields.');
                }
            }
        });
    });
});
