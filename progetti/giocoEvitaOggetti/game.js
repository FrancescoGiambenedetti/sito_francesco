let player, scoreDisplay, attemptsDisplay, overlay;
let playerX = window.innerWidth / 2 - 20;
let score = 0;
let maxAttempts;
let attempts;
let gameInterval;
let objects = [];
const colors = ["#a2c2e0", "#a2e0a2", "#e0a2a2", "#e0e0a2"];
let touchStartX = 0;
fetch('config.json')
    .then(response => response.json())
    .then(config => {
        maxAttempts = config.maxAttempts;
        attempts = maxAttempts;
        document.getElementById("lives").innerText = maxAttempts;
        document.getElementById("attempts-value").innerText = attempts;
        document.getElementById("start-button").addEventListener("click", startGame);
    })
    .catch(error => console.error('Errore nel caricamento del file di configurazione:', error));
function startGame() {
    document.getElementById("start-screen").style.display = "none";
    document.getElementById("game-container").style.display = "block";
    player = document.getElementById('player');
    scoreDisplay = document.getElementById('score-value');
    attemptsDisplay = document.getElementById('attempts-value');
    overlay = document.getElementById('overlay');
    score = 0;
    objects = [];
    updateScore();
    gameInterval = setInterval(gameLoop, 20);
    document.addEventListener('keydown', handleMove);
    document.addEventListener('touchstart', handleTouchStart);
    document.addEventListener('touchmove', handleTouchMove);
    document.addEventListener('mousemove', handleMouseMove);
}
function handleMove(event) {
    if (event.code === 'ArrowLeft' && playerX > 0) {
        playerX -= 20;
    } else if (event.code === 'ArrowRight' && playerX < window.innerWidth - 40) {
        playerX += 20;
    }
    player.style.left = `${playerX}px`;
}
function handleTouchStart(event) {
    touchStartX = event.touches[0].clientX;
}
function handleTouchMove(event) {
    const touchX = event.touches[0].clientX;
    const moveDistance = touchX - touchStartX;
    playerX += moveDistance;
    if (playerX < 0) {
        playerX = 0;
    } else if (playerX > window.innerWidth - player.offsetWidth) {
        playerX = window.innerWidth - player.offsetWidth;
    }
    player.style.left = `${playerX}px`;
    touchStartX = touchX;
}
function handleMouseMove(event) {
    playerX = event.clientX - player.offsetWidth / 2;
    if (playerX < 0) {
        playerX = 0;
    } else if (playerX > window.innerWidth - player.offsetWidth) {
        playerX = window.innerWidth - player.offsetWidth;
    }
    player.style.left = `${playerX}px`;
}
function gameLoop() {
    let creationChance = 0.02 + score * 0.002;
    if (Math.random() < creationChance) {
        createObject();
    }
    moveObjects();
    checkCollisions();
}
function createObject() {
    const object = document.createElement('div');
    object.className = 'object';
    object.style.width = '30px';
    object.style.height = '30px';
    object.style.backgroundColor = getRandomColor();
    object.style.position = 'absolute';
    object.style.left = `${Math.random() * (window.innerWidth - 30)}px`;
    object.style.top = '0';
    object.speed = 3 + score * 0.05;
    document.getElementById('game-container').appendChild(object);
    objects.push(object);
}
function getRandomColor() {
    return colors[Math.floor(Math.random() * colors.length)];
}
function moveObjects() {
    objects.forEach((object, index) => {
        object.style.top = `${parseInt(object.style.top) + object.speed}px`;
        if (parseInt(object.style.top) + object.offsetHeight >= window.innerHeight) {
            object.remove();
            objects.splice(index, 1);
            score++;
            updateScore();
        }
    });
}
function checkCollisions() {
    objects.forEach((object, index) => {
        const playerRect = player.getBoundingClientRect();
        const objectRect = object.getBoundingClientRect();
        if (
            playerRect.left < objectRect.left + objectRect.width &&
            playerRect.left + playerRect.width > objectRect.left &&
            playerRect.top < objectRect.top + objectRect.height &&
            playerRect.top + playerRect.height > objectRect.top
        ) {
            handleCollision();
            object.remove();
            objects.splice(index, 1);
        }
    });
}
function handleCollision() {
    attempts--;
    updateAttempts();
    if (attempts <= 0) {
        gameOver();
    }
}
function updateScore() {
    scoreDisplay.innerText = score;
}
function updateAttempts() {
    attemptsDisplay.innerText = attempts;
}
function gameOver() {
    clearInterval(gameInterval);
    overlay.style.display = 'flex';
    overlay.innerHTML = `
        <div>
            <p>Game Over...<br>Hai ottenuto ${score} punti!</p>
            <button onclick="restartGame()">Ricomincia</button>
        </div>
    `;
}
function restartGame() {
    overlay.style.display = 'none';
    attempts = maxAttempts;
    score = 0;
    updateScore();
    updateAttempts();
    resetGame();
}
function resetGame() {
    objects.forEach(object => object.remove());
    objects = [];
    playerX = window.innerWidth / 2 - 20;
    player.style.left = `${playerX}px`;
    gameInterval = setInterval(gameLoop, 20);
}