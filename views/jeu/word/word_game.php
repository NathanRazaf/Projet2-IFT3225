<?php
// The variables $language, $time, and $hint_interval are passed from the router
// Default values are set in the router, but we'll check them here just to be safe
$language = isset($language) ? $language : 'en';
$time = isset($time) ? intval($time) : 60;
$hint_interval = isset($hint_interval) ? intval($hint_interval) : 10;

// Get player information from cookies if available
$player_id = isset($_COOKIE['player_id']) ? $_COOKIE['player_id'] : null;
$player_name = isset($_COOKIE['player_login']) ? $_COOKIE['player_login'] : 'Invité';

// Base path for API calls (adjust as needed)
$base_path = '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Jeu Word - Trouve le mot</title>
  <link rel="stylesheet" href="<?= $base_path ?>/assets/css/bootstrap.css">
  <link rel="stylesheet" href="<?= $base_path ?>/assets/css/style.css">
</head>
<body>
<div class="container my-5">

  <!-- SECTION SCOREBOARD -->
  <div class="row align-items-center mb-4">
    <div class="col-auto">
      🐊
    </div>
    <div class="col-auto">
      <strong id="playerName">Joueur : <?= htmlspecialchars($player_name) ?></strong>
    </div>
    <div class="col-auto ms-3">
      <span>Score : <strong id="score">60</strong></span>
    </div>
    <div class="col-auto ms-3">
      <span>Temps : <strong id="timer"><?= $time ?></strong> s</span>
    </div>
  </div>

  <!-- CONTENU DU JEU -->
  <h1 class="mb-3">Jeu Word</h1>
  <p class="mb-4">Devinez le mot correspondant à la définition ci-dessous.</p>

  <div class="mb-3">
    <strong>Définition :</strong>
    <span id="definition">Chargement de la définition…</span>
  </div>

  <div class="mb-3">
    <strong>Mot à deviner :</strong>
    <span id="masked-word">_ _ _ _ _ _</span>
  </div>

  <div class="mb-3">
    <strong>Indice :</strong>
    <span id="hint">[Indice non dévoilé]</span>
  </div>

  <div class="mb-3">
    <label for="proposition" class="form-label">Votre proposition :</label>
    <input type="text" id="proposition" class="form-control" placeholder="Tapez votre réponse ici">
  </div>

  <button id="submit-btn" class="btn btn-primary">Valider</button>

  <!-- Zone de feedback -->
  <div id="feedback" class="mt-3"></div>
  <button id="next-btn" class="btn btn-secondary mt-3" style="display:none">
    Suivant
  </button>
</div>

<script>
  // Pass PHP variables to JavaScript
  const GAME_CONFIG = {
    language: "<?= $language ?>",
    time: <?= $time ?>,
  hint_interval: <?= $hint_interval ?>,
  player_id: "<?= $player_id ?>",
          player_name: "<?= htmlspecialchars($player_name) ?>",
          api_url: "<?= $base_path ?>/word"
  };
</script>
<script src="<?= $base_path ?>/assets/js/jeu_word.js"></script>
</body>
</html>