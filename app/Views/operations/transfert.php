<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vola - Transfert multiple</title>
    <link rel="stylesheet" href="/assets/css/tabler-icons-fallback.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>

<body>
    <div class="app" style="padding-bottom:24px;">

        <div class="topbar">
            <a href="<?= site_url('client/dashboard') ?>" class="back-btn">
                <i class="ti ti-arrow-left"></i>
            </a>
            <h1>Transfert multiple</h1>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert success">
                <i class="ti ti-circle-check"></i>
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert error">
                <i class="ti ti-alert-circle"></i>
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <div class="form-page">

            <form id="transfertForm" action="<?= site_url('operations/transfert') ?>" method="post">

                <?= csrf_field() ?>

                <div class="field">

                    <label>Numéros des destinataires</label>

                    <div id="destinataires">

                        <div class="dest-item">

                            <input type="text" name="destinataires[0][telephone]" placeholder="Ex: 034XXXXXXX" required>

                            <label>
                                <input type="checkbox" name="destinataires[0][inclure_frais]" value="1">

                                Inclure frais retrait
                            </label>

                        </div>

                    </div>


                    <button type="button" class="btn btn-outline" id="addDest">
                        <i class="ti ti-plus"></i>
                        Ajouter un numéro
                    </button>

                </div>


                <div class="field">

                    <label for="montant">
                        Montant total à transférer
                    </label>

                    <div class="amount-input-wrap">

                        <input type="number" id="montant" name="montant" min="1" step="1" placeholder="0" required>

                        <span class="suffix">
                            Ar
                        </span>

                    </div>


                    <div class="hint" id="feePreview">
                        Les frais dépendent du montant transféré.
                    </div>

                </div>


                <label>

                    <input type="checkbox" id="inclureTous">

                    Inclure les frais de retrait pour tous

                </label>


                <br><br>


                <button type="submit" class="btn btn-primary">

                    <i class="ti ti-send"></i>

                    Transférer

                </button>


            </form>

        </div>

    </div>


    <div class="sheet-backdrop" id="confirmBackdrop">

        <div class="sheet">

            <div class="sheet-handle"></div>

            <h3>Confirmer le transfert</h3>


            <div class="fee-box">


                <div class="fee-row">
                    <span>Nombre de destinataires</span>
                    <span class="val" id="sNombre">0</span>
                </div>


                <div class="fee-row">
                    <span>Montant total</span>
                    <span class="val" id="sMontant">0 Ar</span>
                </div>


                <div class="fee-row">
                    <span>Montant par personne</span>
                    <span class="val" id="sPart">0 Ar</span>
                </div>


                <div class="fee-row">
                    <span>Frais par personne</span>
                    <span class="val" id="sFraisPart">0 Ar</span>
                </div>


                <div class="fee-row">
                    <span>Frais appliqués</span>
                    <span class="val" id="sFrais">0 Ar</span>
                </div>


                <div class="fee-row total">

                    <span>Débité de votre solde</span>

                    <span class="val" id="sTotal">
                        0 Ar
                    </span>

                </div>


            </div>


            <div class="sheet-actions">

                <button type="button" class="btn btn-outline" id="cancelConfirm">

                    Annuler

                </button>


                <button type="button" class="btn btn-primary" id="validateConfirm">

                    Confirmer

                </button>


            </div>


        </div>

    </div>



    <script>

        const baremeTransfert = <?= json_encode($baremeTransfert) ?>;
        const baremeRetrait = <?= json_encode($baremeRetrait) ?>;


        const form = document.getElementById('transfertForm');
        const container = document.getElementById('destinataires');
        const montantInput = document.getElementById('montant');
        const feePreview = document.getElementById('feePreview');
        const backdrop = document.getElementById('confirmBackdrop');
        const inclureTous = document.getElementById('inclureTous');


        let indexDest = 1;



        function calculerFrais(montant, type) {

            let bareme = type === 2
                ? baremeRetrait
                : baremeTransfert;


            let tranche = bareme.find(t =>

                montant >= Number(t.montant_min)

                &&

                montant <= Number(t.montant_max)

            );


            return tranche ? Number(tranche.frais) : 0;

        }



        function formatAr(n) {

            return Number(n)
                .toLocaleString('fr-FR')
                .replace(/,/g, ' ')
                + ' Ar';

        }



        document.getElementById('addDest')
            .addEventListener('click', function () {


                let div = document.createElement('div');


                div.className = "dest-item";


                div.innerHTML = `

<input type="text"
name="destinataires[${indexDest}][telephone]"
placeholder="Ex: 034XXXXXXX"
required>


<label>

<input type="checkbox"
name="destinataires[${indexDest}][inclure_frais]"
value="1">

Inclure frais retrait

</label>


<button type="button" class="remove">
✕
</button>

`;


                container.appendChild(div);


                if (inclureTous.checked) {
                    div.querySelector('input[type="checkbox"]').checked = true;
                }


                indexDest++;

            });



        container.addEventListener('click', function (e) {

            if (e.target.classList.contains('remove')) {

                if (container.children.length > 1) {

                    e.target.parentElement.remove();

                }

            }

        });



        inclureTous.addEventListener('change', function () {

            document.querySelectorAll('input[name*="[inclure_frais]"]')
                .forEach(c => {

                    c.checked = inclureTous.checked;

                });

        });



        montantInput.addEventListener('input', function () {

            let montant = Number(montantInput.value);


            if (montant > 0) {

                feePreview.textContent =
                    "Frais transfert estimés : "
                    +
                    formatAr(calculerFrais(montant, 3));

            }

            else {

                feePreview.textContent =
                    "Les frais dépendent du montant transféré.";

            }

        });



        form.addEventListener('submit', function (e) {


            let montant = Number(montantInput.value);


            let destinataires =
                document.querySelectorAll('input[name*="[telephone]"]');



            if (!montant || montant <= 0) {
                return;
            }



            e.preventDefault();



            let nombre = destinataires.length;


            let part = montant / nombre;



            let fraisTransfert =
                calculerFrais(part, 3);



            let fraisTotal = 0;



            fraisTotal += fraisTransfert * nombre;



            document.querySelectorAll('input[name*="[inclure_frais]"]')
                .forEach(c => {


                    if (c.checked) {

                        fraisTotal += calculerFrais(part, 2);

                    }

                });



            let fraisParPersonne =
                fraisTotal / nombre;



            let total =
                montant + fraisTotal;



            document.getElementById('sNombre').textContent =
                nombre;


            document.getElementById('sMontant').textContent =
                formatAr(montant);



            document.getElementById('sPart').textContent =
                formatAr(part);



            document.getElementById('sFraisPart').textContent =
                formatAr(fraisParPersonne);



            document.getElementById('sFrais').textContent =
                formatAr(fraisTotal);



            document.getElementById('sTotal').textContent =
                formatAr(total);



            backdrop.classList.add('open');


        });



        document.getElementById('cancelConfirm')
            .addEventListener('click', function () {

                backdrop.classList.remove('open');

            });



        document.getElementById('validateConfirm')
            .addEventListener('click', function () {

                backdrop.classList.remove('open');

                form.submit();

            });


    </script>

</body>

</html>