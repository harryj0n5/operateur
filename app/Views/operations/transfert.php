<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vola - Transfert</title>
    <link rel="stylesheet" href="/assets/css/tabler-icons-fallback.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>

<div class="app" style="padding-bottom:24px;">

    <div class="topbar">
        <a href="<?= site_url('client/dashboard') ?>" class="back-btn" aria-label="Retour"><i class="ti ti-arrow-left"></i></a>
        <h1>Transfert d'argent</h1>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert success"><i class="ti ti-circle-check"></i> <?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert error"><i class="ti ti-alert-circle"></i> <?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="form-page">
        <form id="transfertForm" action="<?= site_url('operations/transfert') ?>" method="post">
            <?= csrf_field() ?>

            <div class="field">
                <label for="telephone_destinataire">Numero du destinataire</label>
                <input type="text" id="telephone_destinataire" name="telephone_destinataire" placeholder="Ex: 034XXXXXXX" required>
            </div>

            <div class="field">
                <label for="montant">Montant a transferer</label>
                <div class="amount-input-wrap">
                    <input type="number" id="montant" name="montant" min="1" step="1" placeholder="0" required>
                    <span class="suffix">Ar</span>
                </div>
                <div class="hint" id="feePreview">Les frais dependent du montant transfere.</div>
            </div>

            <button type="submit" class="btn btn-primary" id="submitBtn">
                <i class="ti ti-send"></i> Transferer
            </button>
        </form>
    </div>

</div>

<div class="sheet-backdrop" id="confirmBackdrop">
    <div class="sheet">
        <div class="sheet-handle"></div>
        <h3>Confirmer le transfert</h3>
        <div class="fee-box">
            <div class="fee-row"><span>Destinataire</span><span class="val" id="sDest">-</span></div>
            <div class="fee-row"><span>Montant transfere</span><span class="val" id="sMontant">0 Ar</span></div>
            <div class="fee-row"><span>Frais appliques</span><span class="val" id="sFrais">0 Ar</span></div>
            <div class="fee-row total"><span>Debite de votre solde</span><span class="val" id="sTotal">0 Ar</span></div>
        </div>
        <div class="sheet-actions">
            <button type="button" class="btn btn-outline" id="cancelConfirm">Annuler</button>
            <button type="button" class="btn btn-primary" id="validateConfirm">Confirmer</button>
        </div>
    </div>
</div>

<script>
    const bareme = <?= json_encode($bareme) ?>;
    const form = document.getElementById('transfertForm');
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
            feePreview.textContent = 'Frais estimes : ' + formatAr(calculerFrais(montant));
        } else {
            feePreview.textContent = 'Les frais dependent du montant transfere.';
        }
    });

    form.addEventListener('submit', function (event) {
        const telephone = document.getElementById('telephone_destinataire').value.trim();
        const montant = Number(montantInput.value);

        if (!montant || montant <= 0 || !telephone) {
            return;
        }

        event.preventDefault();

        const frais = calculerFrais(montant);
        const total = montant + frais;

        document.getElementById('sDest').textContent = telephone;
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


