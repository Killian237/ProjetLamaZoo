document.addEventListener('DOMContentLoaded', function () {
    const passwordInput = document.getElementById('registration_form_plainPassword');
    if (!passwordInput) return;

    // Création de la liste des critères
    const criteria = [
        {
            id: 'length',
            label: 'Au moins 12 caractères',
            test: value => value.length >= 12
        },
        {
            id: 'uppercase',
            label: 'Au moins une majuscule',
            test: value => /[A-Z]/.test(value)
        },
        {
            id: 'lowercase',
            label: 'Au moins une minuscule',
            test: value => /[a-z]/.test(value)
        },
        {
            id: 'special',
            label: 'Au moins un caractère spécial',
            test: value => /[\W_]/.test(value)
        }
    ];

    // Création de l'affichage des critères
    let criteriaDiv = document.getElementById('password-criteria');
    if (!criteriaDiv) {
        criteriaDiv = document.createElement('ul');
        criteriaDiv.id = 'password-criteria';
        criteriaDiv.className = 'list-unstyled mb-2';
        criteria.forEach(c => {
            const li = document.createElement('li');
            li.id = 'criteria-' + c.id;
            li.innerHTML = '❌ ' + c.label;
            criteriaDiv.appendChild(li);
        });
        passwordInput.parentNode.insertBefore(criteriaDiv, passwordInput.nextSibling);
    }

    // Met à jour l'affichage à chaque saisie
    function updateCriteria(value) {
        criteria.forEach(c => {
            const li = document.getElementById('criteria-' + c.id);
            if (c.test(value)) {
                li.innerHTML = '✅ ' + c.label;
                li.classList.add('text-success');
                li.classList.remove('text-danger');
            } else {
                li.innerHTML = '❌ ' + c.label;
                li.classList.add('text-danger');
                li.classList.remove('text-success');
            }
        });
    }

    passwordInput.addEventListener('focus', function () {
        criteriaDiv.style.display = 'block';
        updateCriteria(passwordInput.value);
    });

    passwordInput.addEventListener('input', function () {
        updateCriteria(passwordInput.value);
    });

    passwordInput.addEventListener('blur', function () {
        // criteriaDiv.style.display = 'none'; // Décommente si tu veux masquer après blur
    });
});