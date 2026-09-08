document.addEventListener('DOMContentLoaded', function () {
    var toggle = document.getElementById('navToggle');
    var nav = document.getElementById('siteNav');

    if (toggle && nav) {
        toggle.addEventListener('click', function () {
            var isOpen = nav.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    }

    document.querySelectorAll('.dropdown__toggle').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            btn.closest('.dropdown').classList.toggle('is-open');
        });
    });
});
