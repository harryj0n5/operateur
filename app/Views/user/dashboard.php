<?php
$solde = $solde ?? 0;
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vola - Tableau de bord</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/2.44.0/iconfont/tabler-icons.min.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>

<div class="app">

    <div class="app-header">
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
        <div class="row">
            <div class="brand"><span class="brand-mark">V</span> Vola</div>
            <a href="<?= site_url('user/logout') ?>" class="icon-btn" aria-label="Se déconnecter">
                <i class="ti ti-logout"></i>
            </a>
        </div>
        <div class="greeting">Bienvenue sur votre compte</div>
    </div>

    <div class="balance-card">
        <div class="label">
            <i class="ti ti-wallet"></i> Solde disponible
            <button type="button" class="toggle-eye" id="toggleBalance" aria-label="Afficher/masquer le solde">
                <i class="ti ti-eye" id="eyeIcon"></i>
            </button>
        </div>
        <div class="amount">
            <span id="balanceValue" data-amount="<?= esc($solde) ?>"><?= esc(number_format((float) $solde, 0, ',', ' ')) ?></span>
            <span class="unit">Ar</span>
        </div>
    </div>

    <div class="quick-actions">
        <a href="<?= site_url('operations/depot') ?>" class="qa-item accent">
            <span class="qa-icon"><i class="ti ti-plus"></i></span>
            <span class="qa-label">Dépôt</span>
        </a>
        <a href="<?= site_url('operations/retrait') ?>" class="qa-item">
            <span class="qa-icon"><i class="ti ti-arrow-up-right"></i></span>
            <span class="qa-label">Retrait</span>
        </a>
        <a href="<?= site_url('operations/transfert') ?>" class="qa-item">
            <span class="qa-icon"><i class="ti ti-send"></i></span>
            <span class="qa-label">Transfert</span>
        </a>
        <a href="<?= site_url('operations/historique') ?>" class="qa-item">
            <span class="qa-icon"><i class="ti ti-receipt"></i></span>
            <span class="qa-label">Historique</span>
        </a>
    </div>

    <div class="section">
        <div class="section-title">
            <h2>Besoin d'aide ?</h2>
        </div>
        <div class="card" style="display:flex;align-items:center;gap:12px;">
            <span class="qa-icon" style="flex-shrink:0;"><i class="ti ti-headset"></i></span>
            <div>
                <div style="font-weight:600;font-size:13.5px;">Une question sur une opération ?</div>
                <div style="font-size:12.5px;color:var(--ink-500);margin-top:2px;">Consultez l'historique de vos transactions pour vérifier un montant ou des frais.</div>
            </div>
        </div>
    </div>

    <nav class="bottom-nav">
        <a href="<?= site_url('client/dashboard') ?>" class="bn-item active">
            <i class="ti ti-home"></i> Accueil
        </a>
        <a href="<?= site_url('operations/historique') ?>" class="bn-item">
            <i class="ti ti-receipt"></i> Historique
        </a>
        <a href="<?= site_url('user/logout') ?>" class="bn-item">
            <i class="ti ti-logout"></i> Quitter
        </a>
    </nav>

</div>

<script>
    const eyeIcon = document.getElementById('eyeIcon');
    const balanceValue = document.getElementById('balanceValue');
    const rawAmount = Number(balanceValue.dataset.amount || 0);
    const formatted = rawAmount.toLocaleString('fr-FR').replace(/,/g, ' ');
    let hidden = false;

    document.getElementById('toggleBalance').addEventListener('click', function () {
        hidden = !hidden;
        balanceValue.textContent = hidden ? '••••••' : formatted;
        eyeIcon.className = hidden ? 'ti ti-eye-off' : 'ti ti-eye';
    });
</script>

</body>
</html>
