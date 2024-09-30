let levelsCompleted = 0;
let attemptsLeft;
let maxAttempts;
let timer;
let timeRemaining;
let initialTimeLimit;
let gameOverFlag = false;
let baseColors = [];
let numberOfSquares;
fetch('config.json')
    .then(response => response.json())
    .then(config => {
        initialTimeLimit = config.timeLimit;
        timeRemaining = initialTimeLimit;
        attemptsLeft = config.attempts;
        maxAttempts = config.attempts;
        baseColors = config.colors;
        numberOfSquares = config.numberOfSquares;
        document.getElementById("lives").innerText = maxAttempts;
        document.getElementById("start-screen").querySelector("p").innerText =
            `${initialTimeLimit} secondi ad ogni turno per trovare il quadrato più grande: hai ${maxAttempts} vite totali!`;
        document.getElementById("start-button").addEventListener("click", startGame);
    })
    .catch(error => console.error('Errore nel caricamento del file di configurazione:', error));
function startGame() {
    if (gameOverFlag) return;
    document.getElementById("start-screen").style.display = "none";
    document.getElementById("game-container").style.display = "block";
    updateAttemptsDisplay();
    startLevel();
}
function startLevel() {
    if (gameOverFlag) return;
    clearTimeout(timer);
    const container = document.getElementById("squares-container");
    container.innerHTML = '';
    document.getElementById("message").innerText = '';
    timeRemaining = initialTimeLimit;
    updateTimerDisplay();
    const sizes = generateSizes(levelsCompleted, numberOfSquares);
    const levelColors = generateColors(numberOfSquares);
    sizes.forEach((size, index) => {
        const square = document.createElement("div");
        square.className = "square";
        square.style.width = `${size}px`;
        square.style.height = `${size}px`;
        square.style.backgroundColor = levelColors[index];
        square.addEventListener("click", () => checkChoice(size, Math.max(...sizes)));
        container.appendChild(square);
    });
    timer = setInterval(() => {
        timeRemaining--;
        updateTimerDisplay();
        if (timeRemaining <= 0) {
            clearInterval(timer);
            gameOver();
        }
    }, 1000);
}
function updateTimerDisplay() {
    const timeDisplay = timeRemaining === 1 ? "1 s" : `${timeRemaining} s`;
    document.getElementById("time-remaining").innerText = timeDisplay;
}
function updateAttemptsDisplay() {
    document.getElementById("attempts-remaining").innerText = attemptsLeft;
}
function generateSizes(level, numberOfSquares) {
    const baseSize = 60;
    const sizeVariation = Math.max(5, 30 - level * 2);
    return Array.from({ length: numberOfSquares }, () =>
        baseSize + Math.floor(Math.random() * sizeVariation)
    ).sort(() => Math.random() - 0.5);
}
function generateColors(numberOfSquares) {
    let colors = [];
    while (colors.length < numberOfSquares) {
        colors = colors.concat(baseColors);
    }
    return colors.slice(0, numberOfSquares).sort(() => Math.random() - 0.5);
}
function checkChoice(selectedSize, maxSize) {
    if (gameOverFlag) return;
    clearInterval(timer);
    if (selectedSize === maxSize) {
        levelsCompleted++;
        startLevel();
    } else {
        attemptsLeft--;
        updateAttemptsDisplay();
        if (attemptsLeft > 0) {
            startLevel();
        } else {
            gameOver();
        }
    }
}
function gameOver() {
    clearInterval(timer);
    gameOverFlag = true;
    console.log('Game Over triggered');
    attemptsLeft = 0;
    updateAttemptsDisplay();
    document.getElementById("game-container").style.display = "block";
    document.getElementById("overlay").style.display = "flex";
    const levelText = levelsCompleted === 1 ? "1 livello" : `${levelsCompleted} livelli`;
    document.getElementById("overlay").innerHTML = `
        <div>
            <p>Game Over...<br>Hai superato ${levelText}!</p>
            <button onclick="restartGame()">Ricomincia</button>
        </div>
    `;
    levelsCompleted = 0;
}
function restartGame() {
    gameOverFlag = false;
    document.getElementById("overlay").style.display = "none";
    attemptsLeft = maxAttempts;
    updateAttemptsDisplay();
    startGame();
}
document.getElementById("start-button").addEventListener("click", restartGame);