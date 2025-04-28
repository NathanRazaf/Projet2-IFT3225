<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Jeu des définitions – IFT3225</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
<main class="container py-5">
    <div class="text-center mb-4">
        <h1 class="display-5 fw-bold">Trouve des définitions !</h1>
        <p class="lead">Propose un max de définitions en <span id="time-left" class="fw-bold">60</span> s</p>
        <p class="h5">Mot : <span id="word" class="text-primary"></span></p>
    </div>

    <form id="def-form" class="needs-validation" novalidate>
        <div class="mb-3">
            <label for="definition-field" class="form-label">Ta définition</label>
            <textarea id="definition-field"
                      required
                      minlength="5"
                      maxlength="200"
                      class="form-control"
                      placeholder="Entre ta définition ici..."></textarea>
            <div class="form-text">Entre une définition et soumets-la. Tu pourras en ajouter autant que tu veux.</div>
        </div>

        <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-primary">
                Soumettre cette définition
            </button>
            <div class="ms-auto">
                Points : <span id="points-earned" class="fw-bold">0</span>
            </div>
        </div>
    </form>

    <div id="result" class="alert alert-success mt-4 d-none" role="alert"></div>
</main>

<script>
    // Pass PHP variables to JavaScript
    const LANG = <?= json_encode($language) ?>;
    const LIMIT = <?= json_encode($time) ?>;
</script>
<script src="/assets/js/jeudef.js"></script>
</body>
</html>