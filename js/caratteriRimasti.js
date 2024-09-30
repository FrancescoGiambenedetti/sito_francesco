document.addEventListener("DOMContentLoaded", function () {
    const testoInput = document.getElementById("testo");
    const paroleRimaste = document.getElementById("paroleRimaste");
    const resetButton = document.getElementById("resetButton");
    const caratteriMax = 100;
    function aggiornaCaratteriRimasti() {
        const testo = testoInput.value.trim();
        const caratteri = testo.length;
        const caratteriRimanenti = caratteriMax - caratteri;
        paroleRimaste.textContent = `Caratteri rimasti: ${caratteriRimanenti >= 0 ? caratteriRimanenti : 0}`;
    }
    testoInput.addEventListener('input', aggiornaCaratteriRimasti);
    resetButton.addEventListener('click', function (e) {
        paroleRimaste.textContent = `Caratteri rimasti: ${caratteriMax}`;
    });
    paroleRimaste.textContent = `Caratteri rimasti: ${caratteriMax}`;
});