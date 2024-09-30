document.addEventListener('DOMContentLoaded', function () { /* evento parte quando è stata caricata la pagina ed il dom è disponibile */
    const modale = document.getElementById("projectModal"); /* seleziona l'elemento della modale */
    const bottoneChiudi = document.getElementsByClassName("chiudi")[0];
    const visitaProgettoLink = document.getElementById("link-modale");
    document.querySelectorAll(".scopri-di-piu").forEach(link => { /* seleziona tutti gli elementi con la classe "scopri-di-piu" */
        link.addEventListener("click", function (event) {
            event.preventDefault();
            const idProgetto = this.dataset.projectId;
            apriProgetto(idProgetto);
        });
    });
    function apriProgetto(idProgetto) {
        fetch(`fetch_projects.php?id=${idProgetto}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Errore nella rete: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (data && !data.error) {
                    document.getElementById("titolo-modale").textContent = data.titolo_card;
                    document.getElementById("descrizione-modale").textContent = data.testo_card;
                    document.getElementById("immagine-modale").src = `../img/progetti/${data.img_card}`;
                    document.getElementById("data-modale").textContent = data.data ? data.data : 'Data non disponibile';
                    document.getElementById("cliente-modale").textContent = data.cliente ? data.cliente : 'Cliente non disponibile';
                    document.getElementById("strumenti-modale").textContent = data.programmi ? data.programmi : 'Programmi non disponibili';
                    document.getElementById("titolo-card-modale").textContent = data.titolo_card;
                    document.getElementById("testo-card-modale").textContent = data.testo_card;
                    visitaProgettoLink.href = data.link ? data.link : '#';
                    modale.style.display = "block";
                } else {
                    console.error("Errore nel caricamento del progetto:", data.error);
                }
            })
            .catch(error => console.error('Errore nel caricamento del progetto:', error));
    }
    bottoneChiudi.onclick = function () {
        modale.style.display = "none";
    };
    window.onclick = function (event) {
        if (event.target == modale) {
            modale.style.display = "none";
        }
    };
});