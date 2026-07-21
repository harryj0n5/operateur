<?php

$active = $active ?? '';

$menuItems = [
        ['key' => 'dashboard', 'url' => 'operateur/dashboard', 'icon' => 'bi-speedometer2', 'label' => 'Tableau de bord'],
        ['key' => 'users', 'url' => 'users', 'icon' => 'bi-people', 'label' => 'Utilisateurs'],
        ['key' => 'operateur', 'url' => 'operateur', 'icon' => 'bi-bank', 'label' => 'Operateurs'],
        ['key' => 'configurations', 'url' => 'configurations', 'icon' => 'bi-sliders', 'label' => 'Configurations'],
        ['key' => 'type-operations', 'url' => 'type-operations', 'icon' => 'bi-grid', 'label' => "Types d'operation"],
        ['key' => 'frais-operations', 'url' => 'frais-operations', 'icon' => 'bi-receipt', 'label' => "Frais d'operation"],
        ['key' => 'situation-gain', 'url' => 'operateur/situation-gain', 'icon' => 'bi-bar-chart', 'label' => 'Situation des gains'],
        ['key' => 'situation-gain-client', 'url' => 'operateur/situation-gain-client', 'icon' => 'bi-search', 'label' => 'Situation client'],
        ['key' => 'promotions', 'url' => 'promotions', 'icon' => 'bi-speedometer2', 'label' => 'Promotions'],
];
?>

<aside class="admin-sidebar">

    <div class="brand"><span class="brand-mark">V</span> Vola</div>

    <nav class="admin-nav">

        <?php foreach ($menuItems as $item): ?>
            <a href="<?= site_url($item['url']) ?>"
               class="<?= $active === $item['key'] ? 'active' : '' ?>">
                <i class="bi <?= esc($item['icon']) ?>"></i>
                <?= esc($item['label']) ?>
            </a>
        <?php endforeach; ?>

        <a href="<?= site_url('user/logout') ?>" class="logout-link">
            <i class="bi bi-box-arrow-right"></i>
            Deconnexion
        </a>

    </nav>

</aside>