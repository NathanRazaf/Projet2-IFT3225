(() => {
    const $timer = document.getElementById('time-left');
    const $word = document.getElementById('word');
    const $form = document.getElementById('def-form');
    const $defField = document.getElementById('definition-field');
    const $score = document.getElementById('points-earned');
    const $res = document.getElementById('result');

    let wordId = null;
    let points = 0;
    let secondsLeft = LIMIT; // Use PHP-passed LIMIT
    let timer;

    const startTimer = () => {
        $timer.textContent = secondsLeft;
        timer = setInterval(() => {
            secondsLeft--;
            $timer.textContent = secondsLeft;
            if (secondsLeft <= 0) {
                clearInterval(timer);
                showFinalResult();
                $form.classList.add('d-none'); // Hide the form when time is up
            }
        }, 1000);
    };

    const fetchWord = async () => {
        try {
            // Get player_id from cookies
            const cookies = document.cookie.split(';');
            const playerId = cookies.find(cookie => cookie.trim().startsWith('player_id='));
            const playerIdValue = playerId ? playerId.split('=')[1] : null;
            if (!playerIdValue) throw new Error('Player ID not found in cookies');
            await fetch(`/api/score/${playerIdValue}/0`); // To add 1 to his play count
            const response = await fetch(`/word/random?language=${LANG}`);
            const [wordData] = await response.json();
            if (!wordData) throw new Error('No words found');

            wordId = wordData.id;
            $word.textContent = wordData.word.toUpperCase();
        } catch (err) {
            console.error(err);
            alert(err.message);
        }
    };

    const postDefinition = async (definition) => {
        try {
            const response = await fetch('/api/add_def', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ word: $word.textContent.toLowerCase(), new_def: definition })
            });
            return await response.json();
        } catch (err) {
            console.error(err);
            return { status: 'error', message: 'Failed to submit definition', points: 0 };
        }
    };

    const showFinalResult = () => {
        $res.textContent = `🎉 Bravo ! Tu as gagné ${points} points au total.`;
        $res.classList.remove('d-none');
        $res.classList.remove('alert-success');
        $res.classList.add('alert-info');
    };

    window.addEventListener('DOMContentLoaded', async () => {
        await fetchWord();
        startTimer();
    });

    $form.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (!$form.checkValidity()) return;

        const definition = $defField.value.trim();
        if (definition.length < 5) return;

        const res = await postDefinition(definition);
        if (res.status === 'success') {
            points += 5;
            $score.textContent = points;

            // Show success message
            $res.textContent = `Définition ajoutée ! +5 points. Tu peux continuer à ajouter des définitions jusqu'à la fin du temps.`;
            $res.classList.remove('d-none');
            $res.classList.remove('alert-info');
            $res.classList.add('alert-success');

            // Clear the field for the next definition
            $defField.value = '';
            $defField.focus();
        } else {
            $res.textContent = `Erreur : ${res.message}`;
            $res.classList.remove('d-none');
            $res.classList.remove('alert-success');
            $res.classList.add('alert-danger');
            setTimeout(() => {
                $res.classList.add('d-none');
            }, 3000);
        }
    });
})();