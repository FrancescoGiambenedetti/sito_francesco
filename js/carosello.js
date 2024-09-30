document.addEventListener("DOMContentLoaded", function () { /* attende il caricamento dell'html prima di eseguire */
    const caroselloContainer = document.getElementById("carosello"); /*  */
    let index = 0; /* 0 sarà il primo elemento visualizzato */
    const items = document.querySelectorAll('.carosello-item');
    function startCarosello() {
        function showNextImage() {
            items.forEach((item, i) => {
                item.style.display = 'none';
            });
            items[index].style.display = 'block';
            index = (index + 1) % items.length;
        }
        showNextImage();
        setInterval(showNextImage, 3000);
    }
    startCarosello();
});