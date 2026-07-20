<?php
$solde = $solde ?? 0;
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mobile Money - Dashboard</title>
</head>
<body>

<h1>Bienvenue sur votre tableau de bord</h1>
<p>Votre solde actuel est : <?= esc($solde) ?> Ariary</p>

<nav>
    <ul>
        <li><a href="<?= site_url('client/depot') ?>">Faire un dépôt</a></li>
        <li><a href="<?= site_url('client/retrait') ?>">Faire un retrait</a></li>
        <li><a href="<?= site_url('client/transfert') ?>">Faire un transfert</a></li>
        <li><a href="<?= site_url('client/historique') ?>">Voir l'historique des transactions</a></li>
        <li><a href="<?= site_url('user/logout') ?>">Se déconnecter</a></li>
    </ul>
</nav>
</body>
</html>
