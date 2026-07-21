<?php
$fraisDepot = 0;
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vola - Depot</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/bootstrap/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>

<div class="client-shell">

    <?= view('partials/header', ['active' => 'dashboard']) ?>

    <div class="page-topline">
        <a href="<?= site_url('client/dashboard') ?>" class="back-btn" aria-label="Retour">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1>Depot d'argent</h1>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert success"><i class="bi bi-check-circle"></i> <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert error"><i class="bi bi-exclamation-circle"></i> <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <div class="form-page">
        <form id="depotForm" action="<?= site_url('operations/depot') ?>" method="post">
            <?= csrf_field() ?>

            <div class="field">
                <label for="montant">Montant a deposer</label>
                <div class="amount-input-wrap">
                    <input type="number" id="montant" name="montant" min="1" step="1" placeholder="0" required>
                    <span class="suffix">Ar</span>
                </div>
                <div class="hint">Ce montant sera credite sur votre compte.</div>
            </div>

            <button type="submit" class="btn-vola-primary w-100" id="submitBtn">
                <i class="bi bi-plus-lg"></i> Deposer
            </button>
        </form>
    </div>

</div>

<div class="modal-backdrop" id="confirmBackdrop">
    <div class="modal-card">
        <h3>Confirmer le depot</h3>
        <div class="fee-box">
            <div class="fee-row"><span>Montant du depot</span><span class="val" id="sMontant">0 Ar</span></div>
            <div class="fee-row"><span>Frais appliques</span><span class="val" id="sFrais">0 Ar</span></div>
            <div class="fee-row total"><span>Debite du point de depot</span><span class="val" id="sTotal">0 Ar</span>
            </div>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn-vola-outline" id="cancelConfirm">Annuler</button>
            <button type="button" class="btn-vola-primary" id="validateConfirm">Confirmer</button>
        </div>
    </div>
</div>

<script>
    const fraisDepot = <?= json_encode($fraisDepot) ?>;
    const form = document.getElementById('depotForm');
    const backdrop = document.getElementById('confirmBackdrop');

    function formatAr(n) {
        return Number(n).toLocaleString('fr-FR').replace(/,/g, ' ') + ' Ar';
    }

    form.addEventListener('submit', function (event) {
        const montant = document.getElementById('montant').value;

        if (!montant || Number(montant) <= 0) {
            return; // laisse le "required"/validation navigateur gerer
        }

        event.preventDefault();

        document.getElementById('sMontant').textContent = formatAr(montant);
        document.getElementById('sFrais').textContent = formatAr(fraisDepot);
        document.getElementById('sTotal').textContent = formatAr(Number(montant) + fraisDepot);

        backdrop.classList.add('open');
    });

    document.getElementById('cancelConfirm').addEventListener('click', function () {
        backdrop.classList.remove('open');
    });

    document.getElementById('validateConfirm').addEventListener('click', function () {
        backdrop.classList.remove('open');
        form.submit();
    });
</script>

</body>
</html>