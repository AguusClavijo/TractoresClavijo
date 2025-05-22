const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');

const startScreen = document.getElementById('start-screen');
const gameOverScreen = document.getElementById('game-over-screen');
const startButton = document.getElementById('startButton');
const restartButton = document.getElementById('restartButton');
const scoreDisplay = document.getElementById('score-display');
const finalScoreDisplay = document.getElementById('final-score');
const bestScoreStartDisplay = document.getElementById('best-score-start');
const bestScoreGameOverDisplay = document.getElementById('best-score-gameover');

const explosionContainer = document.getElementById('explosion-container');
const explosionSprite = document.getElementById('explosion-sprite'); // Si usas un sprite sheet, necesitarás más lógica

// Configuración del juego
let gameWidth = 320; // Ancho del juego
let gameHeight = 480; // Alto del juego
canvas.width = gameWidth;
canvas.height = gameHeight;

// Tractor
const tractorImg = new Image();
tractorImg.src = 'img/tractor.png'; // <<< REEMPLAZA CON LA RUTA A TU IMAGEN DEL TRACTOR
let tractor = {
    x: 50,
    y: gameHeight / 2 - 15,
    width: 50,  // Ajusta al tamaño de tu imagen de tractor
    height: 30, // Ajusta al tamaño de tu imagen de tractor
    velocityY: 0,
    gravity: 0.3, // Más bajo para un tractor más "pesado"
    lift: -6,     // Menos salto que un pájaro
    rotation: 0,
    maxRotation: 25,
    minRotation: -45,
    rotationSpeed: 5
};

// Tuberías (Obstáculos)
let pipes = [];
const pipeWidth = 50;
const pipeGap = 120; // Espacio para que pase el tractor
let pipeSpawnTimer = 0;
const pipeSpawnInterval = 150; // Tiempo entre aparición de tuberías (en frames)
const pipeSpeed = 1.5;

// Puntuación
let score = 0;
let bestScore = localStorage.getItem('flappyTractorBestScore') || 0;

// Estado del juego
let gameStarted = false;
let gameOver = false;

// Cargar imagen de la tubería (opcional, puedes dibujarlas)
const pipeImg = new Image();
pipeImg.src = 'img/pipe.png'; // Si tienes una imagen para las tuberías

// Cargar imagen de explosión (si es un GIF simple o una sola imagen)
// Si es un sprite sheet, la animación se maneja de forma diferente
const explosionImg = new Image();
explosionImg.src = 'img/explosion.png'; // <<< REEMPLAZA CON LA RUTA A TU EXPLOSIÓN (o sprite sheet)


function drawTractor() {
    ctx.save();
    ctx.translate(tractor.x + tractor.width / 2, tractor.y + tractor.height / 2);
    ctx.rotate(tractor.rotation * Math.PI / 180);
    ctx.drawImage(tractorImg, -tractor.width / 2, -tractor.height / 2, tractor.width, tractor.height);
    ctx.restore();
}

function updateTractor() {
    tractor.velocityY += tractor.gravity;
    tractor.y += tractor.velocityY;

    // Rotación basada en la velocidad vertical
    if (tractor.velocityY < 0) { // Subiendo
        tractor.rotation = Math.max(tractor.minRotation, tractor.rotation - tractor.rotationSpeed);
    } else if (tractor.velocityY > 1) { // Cayendo rápido
        tractor.rotation = Math.min(tractor.maxRotation, tractor.rotation + tractor.rotationSpeed);
    }


    // Colisión con el suelo o el techo
    if (tractor.y + tractor.height > gameHeight || tractor.y < 0) {
        endGame();
    }
}

function flap() {
    if (!gameOver) {
        tractor.velocityY = tractor.lift;
        tractor.rotation = tractor.minRotation; // Rotar hacia arriba al saltar
    }
}

function drawPipes() {
    pipes.forEach(pipe => {
        ctx.fillStyle = '#38761d'; // Color verde tubería
        // Tubería superior
        ctx.fillRect(pipe.x, 0, pipeWidth, pipe.topHeight);
        // Tubería inferior
        ctx.fillRect(pipe.x, pipe.topHeight + pipeGap, pipeWidth, gameHeight - (pipe.topHeight + pipeGap));
        
        // Opcional: si usas imágenes para las tuberías
        // ctx.drawImage(pipeImg, pipe.x, 0, pipeWidth, pipe.topHeight);
        // ctx.drawImage(pipeImg, pipe.x, pipe.topHeight + pipeGap, pipeWidth, gameHeight - (pipe.topHeight + pipeGap));
    });
}

function updatePipes() {
    pipeSpawnTimer++;
    if (pipeSpawnTimer > pipeSpawnInterval) {
        pipeSpawnTimer = 0;
        let topHeight = Math.random() * (gameHeight / 2 - pipeGap / 2) + (gameHeight / 4); // Altura aleatoria para la tubería superior
        pipes.push({ x: gameWidth, topHeight: topHeight });
    }

    pipes.forEach(pipe => {
        pipe.x -= pipeSpeed;
    });

    // Eliminar tuberías que salen de la pantalla
    pipes = pipes.filter(pipe => pipe.x + pipeWidth > 0);
}

function checkCollisions() {
    pipes.forEach(pipe => {
        // Colisión con tubería superior
        if (tractor.x < pipe.x + pipeWidth &&
            tractor.x + tractor.width > pipe.x &&
            tractor.y < pipe.topHeight) {
            endGame();
        }
        // Colisión con tubería inferior
        if (tractor.x < pipe.x + pipeWidth &&
            tractor.x + tractor.width > pipe.x &&
            tractor.y + tractor.height > pipe.topHeight + pipeGap) {
            endGame();
        }

        // Incrementar puntuación
        if (pipe.x + pipeWidth < tractor.x && !pipe.passed) {
            score++;
            pipe.passed = true;
            updateScoreDisplay();
        }
    });
}

function updateScoreDisplay() {
    scoreDisplay.textContent = score;
}

function showExplosion() {
    explosionContainer.style.left = (tractor.x + tractor.width / 2 - explosionContainer.offsetWidth / 2) + 'px';
    explosionContainer.style.top = (tractor.y + tractor.height / 2 - explosionContainer.offsetHeight / 2) + 'px';
    explosionContainer.style.display = 'block';
    explosionSprite.src = explosionImg.src; // Si es un GIF, esto lo reinicia

    // Si es un sprite sheet, necesitarías una animación aquí.
    // Por ahora, solo lo mostramos y lo ocultamos después de un tiempo.
    setTimeout(() => {
        explosionContainer.style.display = 'none';
    }, 500); // Duración de la explosión visible (ajusta según tu GIF/animación)
}


function gameLoop() {
    if (gameOver) return;

    ctx.clearRect(0, 0, gameWidth, gameHeight); // Limpiar canvas

    if (gameStarted) {
        updateTractor();
        updatePipes();
        checkCollisions();
    }

    drawPipes();
    drawTractor();
    
    requestAnimationFrame(gameLoop);
}

function startGame() {
    // Resetear variables del juego
    tractor.y = gameHeight / 2 - 15;
    tractor.velocityY = 0;
    tractor.rotation = 0;
    pipes = [];
    score = 0;
    pipeSpawnTimer = 0;
    gameOver = false;
    gameStarted = true;

    updateScoreDisplay();
    scoreDisplay.style.display = 'block';
    startScreen.style.display = 'none';
    gameOverScreen.style.display = 'none';
    explosionContainer.style.display = 'none'; // Asegurar que la explosión esté oculta

    gameLoop();
}

function endGame() {
    if (gameOver) return; // Evitar múltiples llamadas
    gameOver = true;
    gameStarted = false;

    showExplosion(); // Mostrar explosión

    // Esperar un poco para que se vea la explosión antes de mostrar Game Over
    setTimeout(() => {
        if (score > bestScore) {
            bestScore = score;
            localStorage.setItem('flappyTractorBestScore', bestScore);
        }
        finalScoreDisplay.textContent = score;
        bestScoreGameOverDisplay.textContent = bestScore;
        bestScoreStartDisplay.textContent = bestScore; // Actualizar en pantalla de inicio también
        gameOverScreen.style.display = 'block';
        scoreDisplay.style.display = 'none';
    }, 300); // Pequeño delay
}


// Event Listeners
canvas.addEventListener('click', flap);
document.addEventListener('keydown', function(e) {
    if (e.code === 'Space' || e.code === 'ArrowUp' || e.key === ' ') { // Barra espaciadora o flecha arriba
        if (!gameStarted && !gameOver) { // Si estamos en la pantalla de inicio
            startGame();
        } else if (gameStarted && !gameOver) { // Si el juego está corriendo
            flap();
        }
    }
});
// Para táctil
canvas.addEventListener('touchstart', function(e) {
    e.preventDefault(); // Evitar scroll y otros comportamientos por defecto
    if (!gameStarted && !gameOver) {
        startGame();
    } else if (gameStarted && !gameOver) {
        flap();
    }
});


startButton.addEventListener('click', startGame);
restartButton.addEventListener('click', startGame);

// Inicializar pantalla de mejor puntuación
bestScoreStartDisplay.textContent = bestScore;

// Ajustar tamaño del canvas y game-container si la ventana cambia
function resizeGame() {
    // Podrías hacer el juego responsivo aquí, ajustando gameWidth, gameHeight y canvas.width/height
    // Por ahora, lo mantenemos fijo.
}
window.addEventListener('resize', resizeGame);
// resizeGame(); // Llamar una vez al inicio si es necesario