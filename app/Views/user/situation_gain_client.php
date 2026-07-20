<h1>Situation d'un client</h1>

<p><a href="/operateur/dashboard">&larr; Retour au dashboard</a></p>

<?php if ($error): ?>
    <p style="color: red;"><?= esc($error) ?></p>
<?php endif; ?>

<form method="get" action="/operateur/situation-gain-client">

    <label>Client</label>
    <select name="client_id">
        <option value="">-- Choisir un client --</option>
        <?php foreach ($clients as $client): ?>
            <option
                value="<?= esc($client['id']) ?>"
                <?= (string) $clientId === (string) $client['id'] ? 'selected' : '' ?>
            >
                <?= esc($client['telephone']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Jusqu'au</label>
    <input type="date" name="date" value="<?= esc($date) ?>">

    <button>
        Afficher
    </button>

</form>

<br>

<?php if ($situation): ?>

    <table border="1">

        <tr>
            <th>Téléphone</th>
            <th>Date de situation</th>
            <th>Solde actuel</th>
            <th>Nombre de transactions</th>
            <th>Total crédit</th>
            <th>Total débit</th>
            <th>Total frais</th>
        </tr>

        <tr>
            <td><?= esc($situation['telephone']) ?></td>
            <td><?= esc($situation['date_situation']) ?></td>
            <td><?= esc($situation['solde_actuel']) ?></td>
            <td><?= esc($situation['nombre_transaction']) ?></td>
            <td><?= esc($situation['total_credit']) ?></td>
            <td><?= esc($situation['total_debit']) ?></td>
            <td><?= esc($situation['total_frais']) ?></td>
        </tr>

    </table>

<?php endif; ?>
