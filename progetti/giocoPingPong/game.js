let ball, paddle, scoreDisplay, attemptsDisplay, overlay, description;
let ballX, ballY, ballSpeedX, ballSpeedY;
let paddleX;
let score = 0;
let attempts;
let maxAttempts;
let speedScale;
let ballInPlay = false;
fetch('config.json')
    .then(response => response.json())
    .then(config => {
        maxAttempts = config.maxAttempts;
        speedScale = config.speedScale;
        description = document.getElementById("description");
        description.innerText = `Fai rimbalzare più volte possibile la pallina: hai ${maxAttempts} vite totali!`;
        document.getElementById("start-button").addEventListener("click", startGame);
    })
    .catch(error => console.error('Errore nel caricamento del file di configurazione:', error));
function startGame() {
    document.getElementById("start-screen").style.display = "none";
    document.getElementById("game-container").style.display = "block";
    ball = document.getElementById('ball');
    paddle = document.getElementById('paddle');
    scoreDisplay = document.getElementById('score-value');
    attemptsDisplay = document.getElementById('attempts-value');
    overlay = document.getElementById('overlay');
    attempts = maxAttempts;
    updateAttempts();
    resetBall();
    gameLoop();
}
function resetBall() {
    const ballWidth = ball.offsetWidth;
    ballX = window.innerWidth / 2 - ballWidth / 2;
    ballY = window.innerHeight / 2 - ballWidth / 2;
    ballSpeedX = 4;
    ballSpeedY = 4;
    ballInPlay = false;
}
function moveBall() {
    const ballWidth = ball.offsetWidth;
    const paddleWidth = paddle.offsetWidth;
    ballX += ballSpeedX;
    ballY += ballSpeedY;
    if (ballX <= 0 || ballX >= window.innerWidth - ballWidth) {
        ballSpeedX = -ballSpeedX;
    }
    if (ballY <= 0) {
        ballSpeedY = -ballSpeedY;
    }
    let paddleTop = window.innerHeight - paddle.offsetHeight - 10;
    let paddleLeft = paddleX;
    let paddleRight = paddleX + paddleWidth;

    if (
        ballY + ballWidth >= paddleTop &&
        ballX + ballWidth >= paddleLeft &&
        ballX <= paddleRight
    ) {
        if (!ballInPlay) {
            ballSpeedY = -ballSpeedY;
            score++;
            updateScore();
            increaseSpeed();
            ballInPlay = true;
        }
    } else {
        ballInPlay = false;
    }
    if (ballY > window.innerHeight) {
        attempts--;
        updateAttempts();
        if (attempts > 0) {
            resetBall();
        } else {
            gameOver();
        }
    }
    ball.style.left = ballX + 'px';
    ball.style.top = ballY + 'px';
}
function increaseSpeed() {
    const speedIncrement = 0.5;
    if (ballSpeedX > 0) {
        ballSpeedX += speedIncrement;
    } else {
        ballSpeedX -= speedIncrement;
    }
    if (ballSpeedY > 0) {
        ballSpeedY += speedIncrement;
    } else {
        ballSpeedY -= speedIncrement;
    }
}
function updateScore() {
    scoreDisplay.innerText = score;
}
function updateAttempts() {
    attemptsDisplay.innerText = attempts;
}
function gameOver() {
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
    resetBall();
    gameLoop();
}
function gameLoop() {
    moveBall();
    if (attempts > 0) {
        requestAnimationFrame(gameLoop);
    }
}
document.addEventListener('mousemove', (event) => {
    const paddleWidth = paddle.offsetWidth;
    paddleX = event.clientX - paddleWidth / 2;
    if (paddleX < 0) paddleX = 0;
    if (paddleX > window.innerWidth - paddleWidth) paddleX = window.innerWidth - paddleWidth;
    paddle.style.left = paddleX + 'px';
});
document.addEventListener('touchmove', (event) => {
    let touch = event.touches[0];
    const paddleWidth = paddle.offsetWidth;
    paddleX = touch.clientX - paddleWidth / 2;
    if (paddleX < 0) paddleX = 0;
    if (paddleX > window.innerWidth - paddleWidth) paddleX = window.innerWidth - paddleWidth;
    paddle.style.left = paddleX + 'px';
});