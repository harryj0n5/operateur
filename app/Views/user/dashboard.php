<?php
$solde = $solde ?? 0;
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vola - Tableau de bord</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/bootstrap/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>

<div class="client-shell">

    <?= view('partials/header', ['active' => 'dashboard']) ?>

    <section class="client-hero">
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

        <div class="client-hero-left">
            <div class="greeting">Bienvenue sur votre compte</div>
            <div class="label">
                <i class="bi bi-wallet2"></i> Solde disponible
                <button type="button" class="toggle-eye" id="toggleBalance" aria-label="Afficher/masquer le solde">
                    <i class="bi bi-eye" id="eyeIcon"></i>
                </button>
            </div>
            <div class="amount">
                <span id="balanceValue"
                      data-amount="<?= esc($solde) ?>"><?= esc(number_format((float)$solde, 0, ',', ' ')) ?></span>
                <span class="unit">Ar</span>
            </div>
        </div>

        <div class="client-hero-right">
            <div class="client-hero-icon">
                <i class="bi bi-wallet2"></i>
            </div>
        </div>
    </section>

    <div class="client-actions">
        <a href="<?= site_url('operations/depot') ?>" class="client-action-card accent">
            <span class="ca-icon"><i class="bi bi-plus-lg"></i></span>
            <span>
                <span class="ca-title">Depot</span>
                <span class="ca-sub">Ajouter des fonds</span>
            </span>
        </a>
        <a href="<?= site_url('operations/retrait') ?>" class="client-action-card">
            <span class="ca-icon"><i class="bi bi-arrow-up-right"></i></span>
            <span>
                <span class="ca-title">Retrait</span>
                <span class="ca-sub">Retirer des fonds</span>
            </span>
        </a>
        <a href="<?= site_url('operations/transfert') ?>" class="client-action-card">
            <span class="ca-icon"><i class="bi bi-send"></i></span>
            <span>
                <span class="ca-title">Transfert</span>
                <span class="ca-sub">Envoyer de l'argent</span>
            </span>
        </a>
        <a href="<?= site_url('operations/historique') ?>" class="client-action-card">
            <span class="ca-icon"><i class="bi bi-receipt"></i></span>
            <span>
                <span class="ca-title">Historique</span>
                <span class="ca-sub">Voir les transactions</span>
            </span>
        </a>
    </div>

    <div class="client-grid">

        <div class="client-panel">
            <h2>Activite recente</h2>
            <p style="color:var(--vola-text-muted);font-size:0.88rem;">
                Vos dernieres transactions apparaitront ici.
                <a href="<?= site_url('operations/historique') ?>">Voir tout l'historique</a>
            </p>
        </div>

        <div class="client-panel">
            <h2>Besoin d'aide ?</h2>
            <div class="help-card">
                <span class="ca-icon"><i class="bi bi-headset"></i></span>
                <div>
                    <div class="help-title">Une question sur une operation ?</div>
                    <div class="help-text">
                        Consultez l'historique de vos transactions pour verifier un montant ou des frais.
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<script>
    const eyeIcon = document.getElementById('eyeIcon');
    const balanceValue = document.getElementById('balanceValue');
    const rawAmount = Number(balanceValue.dataset.amount || 0);
    const formatted = rawAmount.toLocaleString('fr-FR').replace(/,/g, ' ');
    let hidden = false;

    document.getElementById('toggleBalance').addEventListener('click', function () {
        hidden = !hidden;
        balanceValue.textContent = hidden ? '------' : formatted;
        eyeIcon.className = hidden ? 'bi bi-eye-slash' : 'bi bi-eye';
    });
</script>

</body>
</html>