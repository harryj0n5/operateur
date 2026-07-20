<?php
$fraisDepot = 0;
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mobile Money - Depot</title>
</head>
<body>

<h1>Dépôt d'argent</h1>

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

<form id="depotForm" action="<?= site_url('operations/depot') ?>" method="post">
    <?= csrf_field() ?>

    <label for="montant">Montant :</label>
    <input type="number" id="montant" name="montant" min="1" step="1" required>

    <button type="submit">Déposer</button>
</form>

<script>
    const fraisDepot = <?= json_encode($fraisDepot) ?>;

    document.getElementById('depotForm').addEventListener('submit', function (event) {
        const montant = document.getElementById('montant').value;

        if (!montant || Number(montant) <= 0) {
            return; // laisse le "required"/validation navigateur gérer
        }

        const message =
            'Confirmer le dépôt de ' + montant + ' Ar ?\n' +
            'Frais appliqués : ' + fraisDepot + ' Ar\n' +
            'Total débité de votre point de dépôt : ' + (Number(montant) + fraisDepot) + ' Ar';

        if (!confirm(message)) {
            event.preventDefault();
        }
    });
</script>

</body>
</html>