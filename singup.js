function checkName(event) {
    const input = event.currentTarget;

    if (formStatus[input.name] = input.value.length > 0) {
        input.parentNode.classList.remove('errore');
    } else {
        input.parentNode.classList.add('errore');
    }
}

function checkSurname(event) {
    const input = event.currentTarget;

    if (formStatus[input.surname] = input.value.length > 0) {
        input.parentNode.classList.remove('errore');
    } else {
        input.parentNode.classList.add('errore');
    }
}

function jsonCheckEmail(json) {
    if (formStatus.email = !json.exists) {
        document.querySelector('.email').classList.remove('errore');
    } else {
        document.querySelector('.email').classList.add('errore');
    }
}

function fetchResponse(response) {
    if (!response.ok) return null;
    return response.json();
}

function checkEmail(event) {
    const emailInput = document.querySelector('.email input');
    if (!/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(String(emailInput.value).toLocaleLowerCase())) {
        document.querySelector('.email').classList.add('errore');
        formStatus.email = false;
    } else {
        fetch("check_email.php?=" + encodeURIComponent(String(emailInput.value).toLocaleLowerCase())).then(fetchResponse).then(jsonCheckEmail);
    }
}

function checkPassword(event) {
    const passwordInput = document.querySelector('.password input');
    if (formStatus.formStatus.password = passwordInput.value.length >= 8) {
        document.querySelector('.password').classList.remove('errore');
    } else {
        document.querySelector('.password').classList.add('errore');
    }
}

function checkSignup(event) {
    if (Object.keys(formStatus).length !== 5 || Object.values(formStatus).includes(false)) {
        event.prventDefault();
    }
}

const formStatus = { 'upload': true };
document.querySelector('.name input').addEventListener('blur', checkName);
document.querySelector('.surname input').addEventListener('blur', checkSurname);
document.querySelector('.e-mail input').addEventListener('blur', checkEmail);
document.querySelector('.password input').addEventListener('blur', checkPassword);
document.querySelector('form').addEventListener('submit', checkSingup);