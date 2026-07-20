<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mobile Money - Retrait</title>
</head>
<body>

<h1>Retrait d'argent</h1>

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

<form id="retraitForm" action="<?= site_url('operations/retrait') ?>" method="post">
    <?= csrf_field() ?>

    <label for="montant">Montant :</label>
    <input type="number" id="montant" name="montant" min="1" step="1" required>

    <button type="submit">Retirer</button>
</form>

<script>
    const bareme = <?= json_encode($bareme) ?>;

    function calculerFrais(montant) {
        const tranche = bareme.find(t =>
            montant >= Number(t.montant_min) && montant <= Number(t.montant_max)
        );
        return tranche ? Number(tranche.frais) : 0;
    }

    document.getElementById('retraitForm').addEventListener('submit', function (event) {
        const montant = Number(document.getElementById('montant').value);

        if (!montant || montant <= 0) {
            return;
        }

        const frais = calculerFrais(montant);
        const total = montant + frais;

        const message =
            'Confirmer le retrait de ' + montant + ' Ar ?\n' +
            'Frais appliqués : ' + frais + ' Ar\n' +
            'Total débité de votre solde : ' + total + ' Ar';

        if (!confirm(message)) {
            event.preventDefault();
        }
    });
</script>

</body>
</html>