document.addEventListener("DOMContentLoaded", () => {
  // === CONFIGURATION ===
  const API_URL          = "backend/word/index.php";
  const DEFAULT_TIME     = 60;    
  const HINT_INTERVAL    = 10;    
  const HINT_COST        = 10;  

  // === ÉTAT DU JEU ===
  let timeLeft          = DEFAULT_TIME;
  let timerIntervalId   = null;
  let hintIntervalId    = null;
  let score             = 0;
  let fullWord          = "";
  let maskedWordArray   = [];

  // === RÉFÉRENCES DOM ===
  const timerEl      = document.getElementById("timer");
  const scoreEl      = document.getElementById("score");
  const definitionEl = document.getElementById("definition");
  const maskedEl     = document.getElementById("masked-word");
  const hintEl       = document.getElementById("hint");
  const inputEl      = document.getElementById("proposition");
  const submitBtn    = document.getElementById("submit-btn");
  const feedbackEl   = document.getElementById("feedback");
  const nextBtn      = document.getElementById("next-btn");

  // === FONCTIONS ===

  // Démarre le chrono
  function startTimer() {
    timerEl.textContent = timeLeft;
    timerIntervalId = setInterval(() => {
      timeLeft--;
      timerEl.textContent = timeLeft;
      if (timeLeft <= 0) {
        endGame("Temps écoulé !");
      }
    }, 1000);
  }

  // Démarre le timer des indices
  function startHints() {
    hintIntervalId = setInterval(revealHint, HINT_INTERVAL * 1000);
  }

  // Arrête les timers
  function stopTimers() {
    clearInterval(timerIntervalId);
    clearInterval(hintIntervalId);
  }

  // Affiche un message d'erreur et bloque le bouton
  function endGame(message) {
    stopTimers();
    feedbackEl.innerHTML = `<div class="alert alert-danger">${message}</div>`;
    submitBtn.disabled = true;
    nextBtn.style.display = "inline-block";
  }

  // Charge un mot aléatoire depuis le serveur
  function loadDefinition() {
    const randomFrom = Math.floor(Math.random() * 14050) + 1;
    fetch(`${API_URL}?nb=1&from=${randomFrom}`)
      .then(res => res.json())
      .then(data => {
        if (!Array.isArray(data) || data.length === 0) {
          throw new Error("Pas de mot renvoyé");
        }
        const item = data[0];
        fullWord = item.word.toUpperCase();
        const definition = Array.isArray(item.def) ? item.def[0] : "";

        definitionEl.textContent = definition || "Définition non disponible";
        maskedWordArray = fullWord.split("").map(() => "_");
        maskedEl.textContent = maskedWordArray.join(" ");
        score = 10 * fullWord.length;
        scoreEl.textContent = score;

        hintEl.textContent = "[Indice non dévoilé]";
      })
      .catch(err => {
        console.error(err);
        definitionEl.textContent = "Erreur de chargement de la définition";
      });
  }

  // Dévoile une lettre au hasard encore cachée
  function revealHint() {
    const hiddenIndexes = maskedWordArray
      .map((c, i) => c === "_" ? i : null)
      .filter(i => i !== null);

    if (hiddenIndexes.length === 0) {
      stopTimers();
      feedbackEl.innerHTML = `<div class="alert alert-success">Bravo, vous avez trouvé le mot !</div>`;
      submitBtn.disabled = true;
      nextBtn.style.display = "inline-block";
      return;
    }

    const idx = hiddenIndexes[Math.floor(Math.random() * hiddenIndexes.length)];
    const letter = fullWord[idx];

    maskedWordArray[idx] = letter;
    maskedEl.textContent = maskedWordArray.join(" ");

    score = Math.max(0, score - HINT_COST);
    scoreEl.textContent = score;

    hintEl.textContent = `Indice : la lettre n°${idx+1} est "${letter}"`;

    if (!maskedWordArray.includes("_")) {
      stopTimers();
      feedbackEl.innerHTML = `<div class="alert alert-success">Bravo, vous avez trouvé le mot !</div>`;
      submitBtn.disabled = true;
      nextBtn.style.display = "inline-block";
    }
  }

  // Traite la proposition utilisateur (lettre ou mot)
  function validateProposition() {
    const val = inputEl.value.trim().toUpperCase();
    if (!val) {
      feedbackEl.innerHTML = `<div class="alert alert-warning">Veuillez entrer une lettre ou un mot.</div>`;
      return;
    }

    if (val.length === 1) {
      let found = false;
      fullWord.split("").forEach((c, i) => {
        if (c === val && maskedWordArray[i] === "_") {
          maskedWordArray[i] = c;
          score += 5;
          found = true;
        }
      });
      if (found) {
        feedbackEl.innerHTML = `<div class="alert alert-success">Bonne lettre !</div>`;
      } else {
        score = Math.max(0, score - 5);
        feedbackEl.innerHTML = `<div class="alert alert-danger">Lettre incorrecte.</div>`;
      }
    } else {
      if (val === fullWord) {
        maskedWordArray = fullWord.split("");
        feedbackEl.innerHTML = `<div class="alert alert-success">Bravo, mot trouvé !</div>`;
        stopTimers();
        submitBtn.disabled = true;
        nextBtn.style.display = "inline-block";
      } else {
        score = Math.max(0, score - 5);
        feedbackEl.innerHTML = `<div class="alert alert-danger">Proposition incorrecte.</div>`;
      }
    }

    maskedEl.textContent = maskedWordArray.join(" ");
    scoreEl.textContent  = score;
    inputEl.value         = "";

    if (!maskedWordArray.includes("_")) {
      stopTimers();
      submitBtn.disabled = true;
      nextBtn.style.display = "inline-block";
    }
  }

  // Réinitialise tout pour la partie suivante
  function resetGame() {
    feedbackEl.innerHTML = "";
    nextBtn.style.display = "none";
    timeLeft = DEFAULT_TIME;
    timerEl.textContent = timeLeft;
    submitBtn.disabled = false;
    loadDefinition();
    startTimer();
    startHints();
  }

  // === ÉCOUTEURS ===
  submitBtn.addEventListener("click", validateProposition);
  inputEl.addEventListener("keydown", e => {
    if (e.key === "Enter") validateProposition();
  });
  nextBtn.addEventListener("click", resetGame);

  // === LANCEMENT DU JEU ===
  loadDefinition();
  startTimer();
  startHints();
});
