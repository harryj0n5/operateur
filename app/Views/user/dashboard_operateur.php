<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mobile Money - Dashboard Opérateur</title>
</head>
<body>

<h1>Tableau de bord opérateur</h1>

<p>Nombre d'utilisateurs : <?= esc($nbUsers) ?></p>
<p>Gains du jour (<?= esc($date) ?>) : <?= esc($gain['total_gain']) ?> Ariary sur <?= esc($gain['nombre_transaction']) ?> transaction(s)</p>

<nav>
    <ul>
        <li><a href="<?= site_url('users') ?>">Gérer les utilisateurs</a></li>
        <li><a href="<?= site_url('configurations') ?>">Gérer les configurations (préfixes)</a></li>
        <li><a href="<?= site_url('type-operations') ?>">Gérer les types d'opération</a></li>
        <li><a href="<?= site_url('frais-operations') ?>">Gérer les frais d'opération</a></li>
        <li><a href="<?= site_url('operateur/situation-gain') ?>">Voir la situation des gains</a></li>
        <li><a href="<?= site_url('operateur/situation-gain-client') ?>">Voir la situation d'un client</a></li>
        <li><a href="<?= site_url('user/logout') ?>">Se déconnecter</a></li>
    </ul>
</nav>
</body>
</html>
