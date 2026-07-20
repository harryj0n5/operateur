<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mobile Money - Historique</title>
</head>
<body>

<h1>Historique des transactions</h1>

<?php if (empty($historique)): ?>
    <p>Aucune transaction pour le moment.</p>
<?php else: ?>

    <table border="1">
        <tr>
            <th>Date</th>
            <th>Opération</th>
            <th>Sens</th>
            <th>Montant</th>
            <th>Frais</th>
            <th>Contrepartie</th>
            <th>Solde après</th>
        </tr>

        <?php foreach ($historique as $ligne): ?>
            <tr>
                <td><?= esc($ligne['date']) ?></td>
                <td><?= esc($ligne['type_operation_libelle']) ?></td>
                <td>
                    <?= $ligne['type_mouvement'] === 'credit'
                        ? '<span style="color: green;">+ Crédit</span>'
                        : '<span style="color: red;">- Débit</span>' ?>
                </td>
                <td><?= esc($ligne['montant']) ?> Ar</td>
                <td><?= esc($ligne['frais']) ?> Ar</td>
                <td><?= $ligne['contrepartie_telephone'] ? esc($ligne['contrepartie_telephone']) : '-' ?></td>
                <td><?= esc($ligne['solde_apres']) ?> Ar</td>
            </tr>
        <?php endforeach; ?>
    </table>

<?php endif; ?>

<a href="/client/dashboard">Retour au tableau de bord</a>

</body>
</html>