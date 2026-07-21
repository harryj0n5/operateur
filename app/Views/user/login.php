<?php
$prefixes = $prefixes ?? [];
$prefixesStr = implode(',', $prefixes);

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
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/bootstrap/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/app.css">

</head>


<body>


<div class="container-fluid">
    <div class="row vola-auth-wrapper">

        <!-- Hero -->
        <div class="col-lg-6 vola-hero d-flex align-items-center justify-content-center">
            <div class="text-center vola-hero-content">

                <div class="vola-brand-icon">
                    <i class="bi bi-wallet2"></i>
                </div>

                <h1 class="display-4">Vola</h1>

                <p class="lead">
                    Votre argent,
                    partout,
                    en toute confiance.
                </p>

                <div class="vola-hero-stats">
                    <div class="text-center">
                        <div class="stat-value">+50k</div>
                        <div class="stat-label">Utilisateurs</div>
                    </div>
                    <div class="text-center">
                        <div class="stat-value">24/7</div>
                        <div class="stat-label">Disponible</div>
                    </div>
                    <div class="text-center">
                        <div class="stat-value">100%</div>
                        <div class="stat-label">Sécurisé</div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Formulaire -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center">

            <div class="card vola-login-card border-0">
                <div class="card-body">

                    <div class="text-center mb-4">
                        <h2 class="vola-title">Connexion</h2>
                        <p class="vola-subtitle">Entrez votre numéro pour accéder à votre compte.</p>
                    </div>

                    <form id="loginForm">

                        <div class="mb-4">

                            <label class="form-label">Numéro de téléphone</label>

                            <div class="input-group vola-input-group" id="telGroup">
                                <span class="input-group-text">
                                    <i class="bi bi-phone"></i>
                                </span>

                                <input
                                        type="tel"
                                        id="telephone"
                                        name="telephone"
                                        class="form-control"
                                        placeholder="<?= esc($placeholder) ?>"
                                        data-prefixes="<?= esc($prefixesStr) ?>"
                                        autocomplete="tel"
                                        required
                                >
                            </div>

                            <div class="form-text mt-2">
                                <i class="bi bi-shield-check text-success"></i>
                                Préfixes acceptés :
                                <?php foreach ($prefixes as $p): ?>
                                    <span class="vola-pill"><?= esc($p) ?></span>
                                <?php endforeach; ?>
                            </div>

                        </div>

                        <div id="errorMessage" class="alert alert-vola-danger d-none"></div>

                        <button type="submit" id="submitBtn" class="btn btn-vola-primary w-100">
                            <span id="submitLabel">
                                <i class="bi bi-box-arrow-in-right"></i>
                                Se connecter
                            </span>
                        </button>

                    </form>

                </div>
            </div>

        </div>

    </div>
</div>


<script>

    const telephoneInput = document.getElementById('telephone');
    const telGroup = document.getElementById('telGroup');
    const prefixesAutorises = telephoneInput.dataset.prefixes.split(',').filter(Boolean);
    const errorBox = document.getElementById('errorMessage');
    const submitBtn = document.getElementById('submitBtn');
    const submitLabel = document.getElementById('submitLabel');

    telephoneInput.addEventListener('focus', () => telGroup.classList.add('focused'));
    telephoneInput.addEventListener('blur', () => telGroup.classList.remove('focused'));

    function showError(message) {
        errorBox.textContent = message;
        errorBox.classList.toggle('d-none', !message);
    }

    function setLoading(isLoading) {
        submitBtn.disabled = isLoading;
        submitLabel.innerHTML = isLoading
            ? '<span class="vola-spinner"></span> Connexion...'
            : '<i class="bi bi-box-arrow-in-right"></i> Se connecter';
    }

    document.getElementById('loginForm').addEventListener('submit', async function (event) {

        event.preventDefault();

        const telephone = telephoneInput.value.trim();

        const prefixeValide = prefixesAutorises.some(p => telephone.startsWith(p));

        if (prefixesAutorises.length && !prefixeValide) {
            showError('Le numéro doit commencer par : ' + prefixesAutorises.join(', '));
            return;
        }

        showError('');
        setLoading(true);

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
                window.location.href = Number(data.type_user_id) === 1
                    ? '/operateur/dashboard'
                    : '/client/dashboard';
            } else {
                showError(data.error);
                setLoading(false);
            }

        } catch (error) {
            showError('Une erreur est survenue. Veuillez réessayer.');
            setLoading(false);
        }

    });

</script>


<script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>


</body>

</html>