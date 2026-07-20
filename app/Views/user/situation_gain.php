<h1>Situation des gains</h1>

<p><a href="/operateur/dashboard">&larr; Retour au dashboard</a></p>

<?php if ($error): ?>
    <p style="color: red;"><?= esc($error) ?></p>
<?php endif; ?>

<form method="get" action="/operateur/situation-gain">

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
            <th>Gain total</th>
            <th>Nombre de transactions</th>
        </tr>

        <tr>
            <td><?= esc($situation['total_gain']) ?></td>
            <td><?= esc($situation['nombre_transaction']) ?></td>
        </tr>

    </table>

<?php endif; ?>
