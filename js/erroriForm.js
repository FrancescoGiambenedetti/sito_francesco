document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("contattamiForm");
    const resetButton = document.getElementById("resetButton");
    const testo = document.getElementById('testo');
    const paroleRimaste = document.getElementById('paroleRimaste');
    testo.addEventListener('input', function () {
        const remaining = 100 - this.value.length;
        paroleRimaste.textContent = `Caratteri rimasti: ${remaining}`;
        if (remaining < 0) {
            this.value = this.value.slice(0, 100);
        }
    });
    function resetFormStyles() {
        form.querySelectorAll('.form-errore').forEach(field => {
            field.classList.remove('form-errore');
            field.style.color = '';
        });
    }
    form.querySelectorAll('input, textarea, select').forEach(input => {
        input.addEventListener('input', function () {
            if (this.classList.contains('form-errore')) {
                this.classList.remove('form-errore');
                this.style.color = '';
            }
        });
    });
    form.addEventListener("submit", function (e) {
        e.preventDefault();
        let hasError = false;
        form.querySelectorAll('input, textarea, select').forEach(input => {
            if (input.id !== 'telefono' && !input.value.trim()) {
                input.classList.add('form-errore');
                input.style.color = '#ff0000';
                hasError = true;
            }
            if (input.id === 'email' && input.value.trim() && !validateEmail(input.value)) {
                input.classList.add('form-errore');
                input.style.color = '#ff0000';
                input.value = '';
                input.placeholder = 'Indirizzo email non valido';
                hasError = true;
            }
        });
        if (!document.getElementById('privacy').checked) {
            alert('Devi accettare la Privacy Policy per inviare il modulo.');
            hasError = true;
        }
        if (hasError) return;
        const formData = new FormData(form);
        fetch("messaggiForm.php", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(result => {
                const formResult = document.getElementById("formResult");
                formResult.innerHTML = '';
                if (result.success) {
                    formResult.innerHTML = `<p style="color: green;">${result.message}</p>`;
                    form.reset();
                    resetFormStyles();
                    paroleRimaste.textContent = "Caratteri rimasti: 100";
                } else if (result.errors) {
                    Object.keys(result.errors).forEach(function (field) {
                        formResult.innerHTML += `<p style="color: red;">Errore nel campo ${field}: ${result.errors[field]}</p>`;
                        document.getElementById(field).classList.add('form-errore');
                        document.getElementById(field).style.color = '#ff0000';
                    });
                } else {
                    formResult.innerHTML = `<p style="color: red;">${result.message}</p>`;
                }
            })
            .catch(error => {
                console.error("Errore:", error);
                document.getElementById("formResult").innerHTML = `<p style="color: red;">Errore durante l'invio del form. Riprova più tardi.</p>`;
            });
    });
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(String(email).toLowerCase());
    }
    resetButton.addEventListener('click', function () {
        form.reset();
        resetFormStyles();
        paroleRimaste.textContent = "Caratteri rimasti: 100";
    });
});