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
    <title>Mobile Money - Login</title>
</head>
<body>

<h1>Connexion</h1>
<form id="loginForm">
    <label for="telephone">Numéro de téléphone :</label>
    <input
            type="text"
            id="telephone"
            name="telephone"
            placeholder="<?= esc($placeholder) ?>"
            data-prefixes="<?= esc($prefixesStr) ?>"
            required
    >
    <small>Préfixes acceptés : <?= esc(implode(', ', $prefixes)) ?></small>
    <button type="submit">Se connecter</button>
</form>

<div id="errorMessage" style="color: red;"></div>

<script>
    const telephoneInput = document.getElementById('telephone');
    const prefixesAutorises = telephoneInput.dataset.prefixes
        ? telephoneInput.dataset.prefixes.split(',')
        : [];

    document.getElementById('loginForm').addEventListener('submit', async function (event) {
        event.preventDefault();
        const telephone = telephoneInput.value.trim();

        // Vérification côté client avant même d'appeler l'API
        const prefixeValide = prefixesAutorises.some(p => telephone.startsWith(p));
        if (prefixesAutorises.length && !prefixeValide) {
            document.getElementById('errorMessage').textContent =
                'Le numéro doit commencer par : ' + prefixesAutorises.join(', ');
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
                window.location.href = Number(data.type_user_id) === 1 ? '/users/' : '/client/dashboard';
            } else {
                document.getElementById('errorMessage').textContent = data.error;
            }
        } catch (error) {
            document.getElementById('errorMessage').textContent = 'Une erreur est survenue. Veuillez réessayer.';
        }
    });
</script>

</body>
</html>