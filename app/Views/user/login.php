<?php
$prefixes = $prefixes ?? [];
$prefixesStr = implode(',', $prefixes);

$placeholder = !empty($prefixes)
        ? 'Ex: ' . $prefixes[0] . 'XXXXXXX'
        : 'Numéro de téléphone';
?>

<!doctype html>
<html lang="fr">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vola - Connexion</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/bootstrap/icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/app.css">

    <style>

        :root {
            --vola-primary: #0d6efd;
            --vola-primary-dark: #0b5ed7;
            --vola-accent: #4c8df8;
            --vola-bg: #f4f7fb;
        }

        * {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        body {
            min-height: 100vh;
            background: var(--vola-bg);
        }

        .auth-wrapper {
            min-height: 100vh;
        }

        .hero {
            background: linear-gradient(135deg, var(--vola-primary), var(--vola-accent));
            color: white;
            position: relative;
            overflow: hidden;
            padding: 2rem;
        }

        .hero::before {
            content: "";
            position: absolute;
            width: 350px;
            height: 350px;
            background: rgba(255, 255, 255, .15);
            border-radius: 50%;
            top: -100px;
            left: -100px;
        }

        .hero::after {
            content: "";
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, .1);
            border-radius: 50%;
            bottom: -120px;
            right: -100px;
        }

        .brand-icon {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .18);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 45px;
            margin: auto;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, .25);
        }

        .hero h1 {
            letter-spacing: -1px;
            text-shadow: 0 4px 20px rgba(0, 0, 0, .15);
        }

        .hero .lead {
            opacity: .92;
            max-width: 320px;
            margin: 0 auto;
        }

        .login-card {
            max-width: 430px;
            width: 100%;
            border-radius: 24px !important;
            box-shadow: 0 20px 60px rgba(13, 110, 253, .12) !important;
        }

        .login-card .card-body {
            padding: 3rem !important;
        }

        .form-label {
            font-size: .9rem;
            color: #344054;
            margin-bottom: .5rem;
        }

        .form-control {
            height: 52px;
            border-radius: 0 12px 12px 0;
            border: 1px solid #dfe4ea;
            font-size: .95rem;
        }

        .form-control:focus {
            border-color: var(--vola-primary);
            box-shadow: 0 0 0 .2rem rgba(13, 110, 253, .12);
        }

        .input-group-text {
            border-radius: 12px 0 0 12px;
            background: white;
            border: 1px solid #dfe4ea;
            border-right: none;
            color: #6b7280;
        }

        .form-text {
            font-size: .82rem;
            color: #6b7280;
        }

        .btn-login {
            height: 52px;
            border-radius: 12px;
            font-weight: 600;
            font-size: .95rem;
            background: var(--vola-primary);
            border: none;
            transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
        }

        .btn-login:hover {
            background: var(--vola-primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(13, 110, 253, .35);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        #errorMessage {
            border-radius: 12px;
            font-size: .9rem;
        }

        @media (max-width: 768px) {

            .hero {
                display: none;
            }

            .login-card .card-body {
                padding: 2rem !important;
            }

        }

    </style>

</head>


<body>


<div class="container-fluid">
    <div class="row auth-wrapper">
        <div class="col-lg-6 hero d-flex align-items-center justify-content-center">
            <div class="text-center position-relative">
                <div class="brand-icon mb-4">

                    <i class="bi bi-wallet2"></i>

                </div>


                <h1 class="display-3 fw-bold">

                    Vola

                </h1>


                <p class="lead">

                    Votre argent,
                    partout,
                    en toute confiance.

                </p>


            </div>


        </div>


        <!-- Formulaire -->

        <div class="col-lg-6 d-flex align-items-center justify-content-center">


            <div class="card login-card border-0 shadow-lg">


                <div class="card-body p-5">


                    <div class="text-center mb-4">


                        <h2 class="fw-bold">

                            Connexion

                        </h2>


                        <p class="text-muted">

                            Entrez votre numéro pour accéder à votre compte.

                        </p>


                    </div>


                    <form id="loginForm">


                        <div class="mb-4">


                            <label class="form-label fw-semibold">

                                Numéro de téléphone

                            </label>


                            <div class="input-group">


                            <span class="input-group-text">

                                <i class="bi bi-phone"></i>

                            </span>


                                <input

                                        type="tel"

                                        id="telephone"

                                        name="telephone"

                                        class="form-control"

                                        placeholder="<?= esc($placeholder) ?>"

                                        data-prefixes="<?= esc($prefixesStr) ?>"

                                        required

                                >


                            </div>


                            <div class="form-text mt-2">


                                <i class="bi bi-shield-check text-success"></i>

                                Préfixes acceptés :

                                <?= esc(implode(', ', $prefixes)) ?>


                            </div>


                        </div>


                        <div

                                id="errorMessage"

                                class="alert alert-danger d-none">

                        </div>


                        <button

                                type="submit"

                                class="btn btn-primary btn-login w-100">


                            <i class="bi bi-box-arrow-in-right"></i>

                            Se connecter


                        </button>


                    </form>


                </div>


            </div>


        </div>


    </div>


</div>


<script>


    const telephoneInput =
        document.getElementById('telephone');


    const prefixesAutorises =
        telephoneInput.dataset.prefixes
            .split(',');


    const errorBox =
        document.getElementById('errorMessage');


    function showError(message) {

        errorBox.textContent = message;

        errorBox.classList.toggle(
            'd-none',
            !message
        );

    }


    document
        .getElementById('loginForm')
        .addEventListener(
            'submit',
            async function (event) {


                event.preventDefault();


                const telephone =
                    telephoneInput.value.trim();


                const prefixeValide =
                    prefixesAutorises.some(
                        p => telephone.startsWith(p)
                    );


                if (prefixesAutorises.length && !prefixeValide) {

                    showError(
                        'Le numéro doit commencer par : '
                        +
                        prefixesAutorises.join(', ')
                    );

                    return;

                }


                try {


                    const response =
                        await fetch(
                            '/user/login',
                            {

                                method: 'POST',

                                headers: {

                                    'Content-Type':
                                        'application/json'

                                },

                                body:
                                    JSON.stringify(
                                        {
                                            telephone
                                        }
                                    )

                            }
                        );


                    const data =
                        await response.json();


                    if (response.ok) {


                        window.location.href =
                            Number(data.type_user_id) === 1

                                ? '/operateur/dashboard'

                                : '/client/dashboard';


                    } else {


                        showError(data.error);


                    }


                } catch (error) {


                    showError(
                        'Une erreur est survenue. Veuillez réessayer.'
                    );


                }


            });


</script>


<script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>


</body>

</html>