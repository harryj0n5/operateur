<?php
$prefixes = $prefixes ?? [];
$prefixesStr = implode(',', $prefixes); // ex: "033,037"
$placeholder = !empty($prefixes)
        ? 'Ex: ' . $prefixes[0] . 'XXXXXXX'
        : 'Numéro de téléphone';
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vola - Connexion</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/2.44.0/iconfont/tabler-icons.min.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>

<div class="auth-shell">

    <div class="auth-hero">
        <svg class="ravinala-pattern" viewBox="0 0 200 200" fill="none">
            <g stroke="#fff" stroke-width="2">
                <path d="M100 100 L100 10"/>
                <path d="M100 100 L165 35"/>
                <path d="M100 100 L190 100"/>
                <path d="M100 100 L165 165"/>
                <path d="M100 100 L100 190"/>
                <path d="M100 100 L35 165"/>
                <path d="M100 100 L10 100"/>
                <path d="M100 100 L35 35"/>
            </g>
        </svg>
        <div class="brand">
            <span class="brand-mark">V</span>
            Vola
        </div>
        <p class="tagline">Votre argent, partout, en toute confiance.</p>
    </div>

    <div class="auth-card">
        <h1>Connexion</h1>
        <p class="sub">Entrez votre numéro pour accéder à votre compte.</p>

        <form id="loginForm">
            <div class="field">
                <label for="telephone">Numéro de téléphone</label>
                <input
                        type="tel"
                        id="telephone"
                        name="telephone"
                        placeholder="<?= esc($placeholder) ?>"
                        data-prefixes="<?= esc($prefixesStr) ?>"
                        required
                >
                <div class="hint">
                    <span class="chip-hint"><i class="ti ti-shield-check"></i> Préfixes acceptés : <?= esc(implode(', ', $prefixes)) ?></span>
                </div>
            </div>

            <div id="errorMessage" class="alert error" style="display:none;"></div>

            <button type="submit" class="btn btn-primary">
                <i class="ti ti-login-2"></i> Se connecter
            </button>
        </form>
    </div>

</div>

<script>
    const telephoneInput = document.getElementById('telephone');
    const prefixesAutorises = telephoneInput.dataset.prefixes
        ? telephoneInput.dataset.prefixes.split(',')
        : [];
    const errorBox = document.getElementById('errorMessage');

    function showError(message) {
        errorBox.textContent = message;
        errorBox.style.display = message ? 'flex' : 'none';
    }

    document.getElementById('loginForm').addEventListener('submit', async function (event) {
        event.preventDefault();
        const telephone = telephoneInput.value.trim();

        // Vérification côté client avant même d'appeler l'API
        const prefixeValide = prefixesAutorises.some(p => telephone.startsWith(p));
        if (prefixesAutorises.length && !prefixeValide) {
            showError('Le numéro doit commencer par : ' + prefixesAutorises.join(', '));
            return;
        }

        try {
            const response = await fetch('/user/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({telephone})
            });

            const data = await response.json();

            if (response.ok) {
                window.location.href = Number(data.type_user_id) === 1 ? '/operateur/dashboard' : '/client/dashboard';
            } else {
                showError(data.error);
            }
        } catch (error) {
            showError('Une erreur est survenue. Veuillez réessayer.');
        }
    });
</script>

</body>
</html>
