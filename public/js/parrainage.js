document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-parrainer').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            btn.classList.add('btn-animate');
            setTimeout(() => btn.classList.remove('btn-animate'), 500);
        });
    });
});