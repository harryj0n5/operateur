<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vola - Transfert multiple</title>
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
        <h1>Transfert multiple</h1>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert success">
            <i class="bi bi-check-circle"></i>
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert error">
            <i class="bi bi-exclamation-circle"></i>
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <div class="form-page">
        <form id="transfertForm" action="<?= site_url('operations/transfert') ?>" method="post">
            <?= csrf_field() ?>

            <div class="field">
                <label>Numéros des destinataires</label>
                <div id="destinataires">
                    <div class="dest-item">
                        <input type="text" name="telephones[]" placeholder="Ex: 034XXXXXXX" required>
                    </div>
                </div>

                <button type="button" class="btn-vola-outline" id="addDest">
                    <i class="bi bi-plus-lg"></i>
                    Ajouter un numéro
                </button>
            </div>

            <div class="field">
                <label for="montant">Montant total à transférer</label>
                <div class="amount-input-wrap">
                    <input type="number" id="montant" name="montant" min="1" step="1" placeholder="0" required>
                    <span class="suffix">Ar</span>
                </div>
                <div class="hint" id="feePreview">
                    Les frais dépendent du montant transféré.
                </div>
                <div class="hint-secondary d-none" id="feePreviewRetrait"></div>
                <div class="hint-secondary d-none" id="feePreviewCommission"></div>
            </div>

            <label class="checkbox-field">
                <input type="checkbox" name="inclure_frais_retrait" id="inclureRetrait" value="1">
                Inclure les frais de retrait
            </label>

            <button type="submit" class="btn-vola-primary w-100">
                <i class="bi bi-send"></i>
                Transférer
            </button>
        </form>
    </div>
</div>

<div class="modal-backdrop" id="confirmBackdrop">
    <div class="modal-card">
        <h3>Confirmer le transfert</h3>

        <div class="fee-box">
            <div class="fee-row">
                <span>Nombre de destinataires</span>
                <span class="val" id="sNombre">0</span>
            </div>

            <div class="fee-row">
                <span>Montant total</span>
                <span class="val" id="sMontant">0 Ar</span>
            </div>

            <div class="fee-row">
                <span>Montant par personne</span>
                <span class="val" id="sPart">0 Ar</span>
            </div>

            <div class="fee-row">
                <span>Frais de transfert</span>
                <span class="val" id="sFrais">0 Ar</span>
            </div>

            <div class="fee-row hidden" id="sCommissionRow">
                <span id="sCommissionLabel">Commission</span>
                <span class="val" id="sCommission">0 Ar</span>
            </div>

            <div class="fee-row hidden" id="sFraisRetraitRow">
                <span>Frais de retrait</span>
                <span class="val" id="sFraisRetrait">0 Ar</span>
            </div>

            <div class="fee-row total">
                <span>Débité de votre solde</span>
                <span class="val" id="sTotal">0 Ar</span>
            </div>
        </div>

        <div class="modal-actions">
            <button type="button" class="btn-vola-outline" id="cancelConfirm">
                Annuler
            </button>
            <button type="button" class="btn-vola-primary" id="validateConfirm">
                Confirmer
            </button>
        </div>
    </div>
</div>

<script>
    const bareme_transfert = <?= json_encode($bareme_transfert) ?>;
    const bareme_retrait = <?= json_encode($bareme_retrait) ?>;
    const operateurs = <?= json_encode($operateurs) ?>;
    const form = document.getElementById('transfertForm');
    const container = document.getElementById('destinataires');
    const montantInput = document.getElementById('montant');
    const feePreview = document.getElementById('feePreview');
    const feePreviewRetrait = document.getElementById('feePreviewRetrait');
    const feePreviewCommission = document.getElementById('feePreviewCommission');
    const inclureRetraitCheckbox = document.getElementById('inclureRetrait');
    const backdrop = document.getElementById('confirmBackdrop');

    function getOperateurParTelephone(telephone) {
        telephone = (telephone || '').trim();
        for (const op of operateurs) {
            if (telephone.startsWith(op.prefix)) {
                return op;
            }
        }
        return null;
    }

    function calculerFraisTransfert(montant) {
        const tranche = bareme_transfert.find(t =>
            montant >= Number(t.montant_min) &&
            montant <= Number(t.montant_max)
        );
        return tranche ? Number(tranche.frais) : 0;
    }

    function calculerFraisRetrait(montant) {
        const tranche = bareme_retrait.find(t =>
            montant >= Number(t.montant_min) &&
            montant <= Number(t.montant_max)
        );
        return tranche ? Number(tranche.frais) : 0;
    }

    function calculerFraisOperateur2(fraisTransfert, operateur) {
        if (!operateur || Number(operateur.principale) === 1) {
            return 0;
        }
        return fraisTransfert * (Number(operateur.pourcentage_frais) / 100);
    }

    function formatAr(n) {
        return Number(n).toLocaleString('fr-FR').replace(/,/g, ' ') + ' Ar';
    }

    function getTelephones() {
        return Array.from(document.querySelectorAll('input[name="telephones[]"]'))
            .map(i => i.value.trim())
            .filter(v => v !== '');
    }

    // Détermine l'opérateur du groupe. Retourne null si mélange d'opérateurs
    // (le serveur refusera dans ce cas — voir verifierMemeOperateur()).
    function getOperateurGroupe(telephones) {
        let operateur = null;
        for (const tel of telephones) {
            const op = getOperateurParTelephone(tel);
            if (!op) return {operateur: null, melange: false};
            if (operateur === null) {
                operateur = op;
            } else if (Number(operateur.operateur_id) !== Number(op.operateur_id)) {
                return {operateur: null, melange: true};
            }
        }
        return {operateur, melange: false};
    }

    function updateInclureRetraitDisponibilite(operateur) {
        const estNonPrincipalConnu = operateur && Number(operateur.principale) !== 1;

        if (estNonPrincipalConnu) {
            inclureRetraitCheckbox.checked = false;
            inclureRetraitCheckbox.disabled = true;
        } else {
            inclureRetraitCheckbox.disabled = false;
        }
    }

    function updateFeePreview() {
        const montant = Number(montantInput.value);
        const telephones = getTelephones();
        const nombre = telephones.length;

        const {operateur, melange} = getOperateurGroupe(telephones);
        updateInclureRetraitDisponibilite(operateur);

        if (melange) {
            feePreview.textContent = "Tous les destinataires doivent appartenir au même opérateur.";
            feePreviewRetrait.classList.add('d-none');
            feePreviewCommission.classList.add('d-none');
            return;
        }

        if (montant > 0 && nombre > 0) {
            const part = montant / nombre;
            const fraisTransfert = calculerFraisTransfert(part) * nombre;
            const commission = calculerFraisOperateur2(fraisTransfert, operateur);

            feePreview.textContent = "Frais de transfert estimés : " + formatAr(fraisTransfert);

            if (commission > 0) {
                feePreviewCommission.textContent =
                    "Commission " + operateur.libelle + " estimée : " + formatAr(commission);
                feePreviewCommission.classList.remove('d-none');
            } else {
                feePreviewCommission.classList.add('d-none');
            }

            if (inclureRetraitCheckbox.checked) {
                const fraisRetrait = calculerFraisRetrait(part) * nombre;
                feePreviewRetrait.textContent = "Frais de retrait estimés : " + formatAr(fraisRetrait);
                feePreviewRetrait.classList.remove('d-none');
            } else {
                feePreviewRetrait.classList.add('d-none');
            }
        } else {
            feePreview.textContent = "Les frais dépendent du montant transféré.";
            feePreviewRetrait.classList.add('d-none');
            feePreviewCommission.classList.add('d-none');
        }
    }

    document.getElementById('addDest').addEventListener('click', function () {
        const div = document.createElement('div');
        div.className = "dest-item";
        div.innerHTML = `
        <input type="text" name="telephones[]" placeholder="Ex: 034XXXXXXX" required>
        <button type="button" class="remove">✕</button>
    `;
        container.appendChild(div);
        updateFeePreview();
    });

    container.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove')) {
            if (container.children.length > 1) {
                e.target.parentElement.remove();
                updateFeePreview();
            }
        }
    });

    container.addEventListener('input', updateFeePreview);
    montantInput.addEventListener('input', updateFeePreview);
    inclureRetraitCheckbox.addEventListener('change', updateFeePreview);

    form.addEventListener('submit', function (e) {
        const montant = Number(montantInput.value);
        const telephones = getTelephones();
        const nombre = telephones.length;

        if (!montant || montant <= 0) {
            return;
        }

        e.preventDefault();

        const {operateur, melange} = getOperateurGroupe(telephones);

        if (melange) {
            feePreview.textContent = "Tous les destinataires doivent appartenir au même opérateur.";
            return;
        }

        const part = montant / nombre;
        const inclureRetrait = inclureRetraitCheckbox.checked;

        const fraisTransfert = calculerFraisTransfert(part) * nombre;
        const commission = calculerFraisOperateur2(fraisTransfert, operateur);
        const fraisRetrait = inclureRetrait ? calculerFraisRetrait(part) * nombre : 0;
        const frais = fraisTransfert + commission + fraisRetrait;
        const total = montant + frais;

        document.getElementById('sNombre').textContent = nombre;
        document.getElementById('sMontant').textContent = formatAr(montant);
        document.getElementById('sPart').textContent = formatAr(part);
        document.getElementById('sFrais').textContent = formatAr(fraisTransfert);

        const sCommissionRow = document.getElementById('sCommissionRow');
        if (commission > 0) {
            document.getElementById('sCommissionLabel').textContent = "Commission " + operateur.libelle;
            document.getElementById('sCommission').textContent = formatAr(commission);
            sCommissionRow.classList.remove('hidden');
        } else {
            sCommissionRow.classList.add('hidden');
        }

        const sFraisRetraitRow = document.getElementById('sFraisRetraitRow');
        if (inclureRetrait) {
            document.getElementById('sFraisRetrait').textContent = formatAr(fraisRetrait);
            sFraisRetraitRow.classList.remove('hidden');
        } else {
            sFraisRetraitRow.classList.add('hidden');
        }

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