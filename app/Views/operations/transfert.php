<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mobile Money - Transfert</title>
</head>
<body>

<h1>Transfert d'argent</h1>

<?php if (session()->getFlashdata('success')): ?>
    <div style="color: green;">
        <?= esc(session()->getFlashdata('success')) ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div style="color: red;">
        <?= esc(session()->getFlashdata('error')) ?>
    </div>
<?php endif; ?>

<form id="transfertForm" action="<?= site_url('operations/transfert') ?>" method="post">
    <?= csrf_field() ?>

    <label for="telephone_destinataire">Numéro du destinataire :</label>
    <input type="text" id="telephone_destinataire" name="telephone_destinataire" required>

    <br>

    <label for="montant">Montant :</label>
    <input type="number" id="montant" name="montant" min="1" step="1" required>

    <button type="submit">Transférer</button>
</form>

<script>
    const bareme = <?= json_encode($bareme) ?>;

    function calculerFrais(montant) {
        const tranche = bareme.find(t =>
            montant >= Number(t.montant_min) && montant <= Number(t.montant_max)
        );
        return tranche ? Number(tranche.frais) : 0;
    }

    document.getElementById('transfertForm').addEventListener('submit', function (event) {
        const telephone = document.getElementById('telephone_destinataire').value.trim();
        const montant = Number(document.getElementById('montant').value);

        if (!montant || montant <= 0 || !telephone) {
            return;
        }

        const frais = calculerFrais(montant);
        const total = montant + frais;

        const message =
            'Confirmer le transfert de ' + montant + ' Ar vers ' + telephone + ' ?\n' +
            'Frais appliqués : ' + frais + ' Ar\n' +
            'Total débité de votre solde : ' + total + ' Ar';

        if (!confirm(message)) {
            event.preventDefault();
        }
    });
</script>

</body>
</html>