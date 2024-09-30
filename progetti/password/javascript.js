function scegliPassword () {
    const passwordInput = document.getElementById("password");
    const errore = document.getElementById("password-errore");
    const strengthImage = document.getElementById("efficacia-password-img");
    const strengthContainer = document.querySelector(".efficacia-password");
    let password = passwordInput.value;
    password = password.replace(/\s/g, '');
    if (password.length > 24) {
        password = password.substring(0, 24);
        passwordInput.value = password;
        errore.textContent = "Massimo 24 caratteri";
        return;
    } else {
        errore.textContent = "";
    }
    let strength = 0;
    if (password.length < 8) {
        strengthContainer.style.display = "none";
        strengthImage.src = "";
        if (password.length > 0) {
            errore.textContent = "Almeno 8 caratteri";
        }
        return;
    }
    strengthContainer.style.display = "block";
    const minuscole = /[a-z]/.test(password);
    const maiuscole = /[A-Z]/.test(password);
    const numeri = /\d/.test(password);
    const speciali = /[!@#$%^&*(),.?":{}|<>]/.test(password);
    const almeno8Caratteri = password.length >= 8;
    const almeno12Caratteri = password.length >= 12;
    if (almeno8Caratteri) {
        strength += 20;
        if (minuscole) strength += 20;
        if (maiuscole) strength += 20;
        if (speciali) strength += 20;
        if (almeno12Caratteri) strength += 20;
    }
    const img = [
        "./img/debole.png",
        "./img/sufficiente.png",
        "./img/sicura.png",
        "./img/forte.png",
        "./img/perfetta.png"
    ];
    strengthImage.src = img[Math.floor(strength / 20) - 1] || img[0];
}
function confermaPassword () {
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("conferma-password").value;
    const errore = document.getElementById("conferma-password-errore");
    if (password !== confirmPassword) {
        errore.textContent = "Le password non coincidono";
    } else {
        errore.textContent = "";
    }
}
function visualizzaPassword() {
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("conferma-password");
    const showPassword = document.getElementById("visualizza-password");
    if (showPassword.checked) {
        password.type = "text";
        confirmPassword.type = "text";
    } else {
        password.type = "password";
        confirmPassword.type = "password";
    }
}
function confermaButton () {
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("conferma-password").value;
    const passworderrore = document.getElementById("password-errore");
    const confirmPassworderrore = document.getElementById("conferma-password-errore");
    let valid = true;
    if (password.length < 8 || password.length > 24) {
        passworderrore.textContent = "Inserire una password valida";
        valid = false;
    } else {
        passworderrore.textContent = "";
    }
    if (password !== confirmPassword) {
        confirmPassworderrore.textContent = "Le password non coincidono";
        valid = false;
    } else {
        confirmPassworderrore.textContent = "";
    }
    if (valid) {
        alert("Form inviato con successo!");
    }
}
function annullaButton () {
    document.getElementById("password").value = "";
    document.getElementById("conferma-password").value = "";
    document.getElementById("password-errore").textContent = "";
    document.getElementById("conferma-password-errore").textContent = "";
    const strengthImage = document.getElementById("efficacia-password-img");
    const strengthContainer = document.querySelector(".efficacia-password");
    strengthImage.src = "";
    strengthContainer.style.display = "none";
}