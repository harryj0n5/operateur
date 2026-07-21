<?php

$active = $active ?? '';

$navItems = [
    ['key' => 'dashboard', 'url' => 'client/dashboard', 'icon' => 'bi-house', 'label' => 'Accueil'],
    ['key' => 'historique', 'url' => 'operations/historique', 'icon' => 'bi-receipt', 'label' => 'Historique'],
];
?>

<header class="client-topbar">
    <div class="brand"><span class="brand-mark">V</span> Vola</div>

    <nav class="client-nav">
        <?php foreach ($navItems as $item): ?>
            <a href="<?= site_url($item['url']) ?>"
               class="<?= $active === $item['key'] ? 'active' : '' ?>">
                <i class="bi <?= esc($item['icon']) ?>"></i>
                <?= esc($item['label']) ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <a href="<?= site_url('user/logout') ?>" class="logout-btn">
        <i class="bi bi-box-arrow-right"></i> Deconnexion
    </a>
</header>