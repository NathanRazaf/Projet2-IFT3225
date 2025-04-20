(() => {

    /* config -------------------------------------------------------------- */
    const LANG   = new URLSearchParams(location.search).get('lg')   ?? 'fr';
    const LIMIT = parseInt(
        new URLSearchParams(location.search).get('time'),
        10
     ) || 60;
    const $timer = document.getElementById('time-left');
    const $word  = document.getElementById('word');
    const $form  = document.getElementById('def-form');
    const $wrap  = document.getElementById('inputs-wrapper');
    const $add   = document.getElementById('add-field');
    const $score = document.getElementById('points-earned');
    const $res   = document.getElementById('result');

    let wordId      = null;
    let points      = 0;
    let secondsLeft = LIMIT;
    let timer;

    /* helpers ------------------------------------------------------------- */
    const createField = () => {
        const idx = $wrap.children.length + 1;
        const group = document.createElement('div');
        group.className = 'mb-3';
        group.innerHTML = `
            <label class="form-label">Définition ${idx}</label>
            <textarea required
                      minlength="5"
                      maxlength="200"
                      class="form-control definition-field"
                      name="def[]"
                      placeholder="Ta définition…"></textarea>`;
        $wrap.appendChild(group);
    };

    const startTimer = () => {
        $timer.textContent = secondsLeft;  
        timer = setInterval(() => {
            secondsLeft--;
            $timer.textContent = secondsLeft;
            $timer.textContent = secondsLeft;
            if (secondsLeft <= 0) {
                clearInterval(timer);
                $form.requestSubmit();     
            }
        }, 1000);
    };

    const fetchWord = async () => {
        try {
            const r   = await fetch(`/functions/word/get_random_word.php?language=${LANG}`);
            const arr = await r.json();   
            if (!Array.isArray(arr) || arr.length === 0) {
                throw new Error('empty array');
            }
            const js  = arr[0];          
    
            wordId = js.id;
            $word.textContent = js.word.toUpperCase();
        } catch (err) {
            console.error(err);
            alert("Impossible de récupérer un mot. Réessaie plus tard.");
        }
    };
    
    

    const postDefinitions = async (defs) => {
        try {
            const r = await fetch('/functions/word/submit_def.php', {
                method : 'POST',
                headers: {'Content-Type': 'application/json'},
                body   : JSON.stringify({ wordId, definitions: defs })
            });
            return await r.json();   
        } catch (err) {
            console.error(err);
            return { points: 0 };
        }
    };

    /* init ---------------------------------------------------------------- */
    window.addEventListener('DOMContentLoaded', async () => {
        createField();
        await fetchWord();
        startTimer();
    });

    /* events -------------------------------------------------------------- */
    $add.addEventListener('click', () => createField());

    $form.addEventListener('submit', async (e) => {
        e.preventDefault();
        $form.classList.add('was-validated');
        if (!$form.checkValidity()) return;

        const defs = [...$form.querySelectorAll('textarea')]
                      .map(t => t.value.trim())
                      .filter(t => t.length >= 5 && t.length <= 200);

        if (defs.length === 0) {
            alert("Ajoute au moins une définition !");
            return;
        }

        clearInterval(timer);   

        const res = await postDefinitions(defs);
        points    = res.points ?? 0;
        $score.textContent = points;

        // feedback
        $res.textContent = `🎉 Bravo ! Tu as gagné ${points} points.`;
        $res.classList.remove('d-none');
        $form.querySelectorAll('textarea').forEach(t => t.disabled = true);
        $add.disabled = true;
    });

})();  
