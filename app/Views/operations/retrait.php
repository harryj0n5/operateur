<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vola - Retrait</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/2.44.0/iconfont/tabler-icons.min.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>

<div class="app" style="padding-bottom:24px;">

    <div class="topbar">
        <a href="<?= site_url('client/dashboard') ?>" class="back-btn" aria-label="Retour"><i class="ti ti-arrow-left"></i></a>
        <h1>Retrait d'argent</h1>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert success"><i class="ti ti-circle-check"></i> <?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert error"><i class="ti ti-alert-circle"></i> <?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="form-page">
        <form id="retraitForm" action="<?= site_url('operations/retrait') ?>" method="post">
            <?= csrf_field() ?>

            <div class="field">
                <label for="montant">Montant à retirer</label>
                <div class="amount-input-wrap">
                    <input type="number" id="montant" name="montant" min="1" step="1" placeholder="0" required>
                    <span class="suffix">Ar</span>
                </div>
                <div class="hint" id="feePreview">Les frais dépendent du montant retiré.</div>
            </div>

            <button type="submit" class="btn btn-primary" id="submitBtn">
                <i class="ti ti-arrow-up-right"></i> Retirer
            </button>
        </form>
    </div>

</div>

<div class="sheet-backdrop" id="confirmBackdrop">
    <div class="sheet">
        <div class="sheet-handle"></div>
        <h3>Confirmer le retrait</h3>
        <div class="fee-box">
            <div class="fee-row"><span>Montant du retrait</span><span class="val" id="sMontant">0 Ar</span></div>
            <div class="fee-row"><span>Frais appliqués</span><span class="val" id="sFrais">0 Ar</span></div>
            <div class="fee-row total"><span>Débité de votre solde</span><span class="val" id="sTotal">0 Ar</span></div>
        </div>
        <div class="sheet-actions">
            <button type="button" class="btn btn-outline" id="cancelConfirm">Annuler</button>
            <button type="button" class="btn btn-primary" id="validateConfirm">Confirmer</button>
        </div>
    </div>
</div>

<script>
    const bareme = <?= json_encode($bareme) ?>;
    const form = document.getElementById('retraitForm');
    const backdrop = document.getElementById('confirmBackdrop');
    const montantInput = document.getElementById('montant');
    const feePreview = document.getElementById('feePreview');

    function calculerFrais(montant) {
        const tranche = bareme.find(t =>
            montant >= Number(t.montant_min) && montant <= Number(t.montant_max)
        );
        return tranche ? Number(tranche.frais) : 0;
    }

    function formatAr(n) {
        return Number(n).toLocaleString('fr-FR').replace(/,/g, ' ') + ' Ar';
    }

    montantInput.addEventListener('input', function () {
        const montant = Number(montantInput.value);
        if (montant > 0) {
            feePreview.textContent = 'Frais estimés : ' + formatAr(calculerFrais(montant));
        } else {
            feePreview.textContent = 'Les frais dépendent du montant retiré.';
        }
    });

    form.addEventListener('submit', function (event) {
        const montant = Number(montantInput.value);

        if (!montant || montant <= 0) {
            return;
        }

        event.preventDefault();

        const frais = calculerFrais(montant);
        const total = montant + frais;

        document.getElementById('sMontant').textContent = formatAr(montant);
        document.getElementById('sFrais').textContent = formatAr(frais);
        document.getElementById('sTotal').textContent = formatAr(total);

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
