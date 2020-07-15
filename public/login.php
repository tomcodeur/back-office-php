<?php

// activation du système d'autoloading de Composer
require __DIR__.'/../vendor/autoload.php';

// instanciation du chargeur de templates
$loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/../templates');

// instanciation du moteur de template
$twig = new \Twig\Environment($loader, [
    // activation du mode debug
    'debug' => true,
    // activation du mode de variables strictes
    'strict_variables' => true,
]);

// chargement de l'extension Twig_Extension_Debug
$twig->addExtension(new \Twig\Extension\DebugExtension());

$formData = [
    'email' => '',
    'subject'  => '',
    'message' => '',
];

if ($_POST) {
    dump($_POST);

    $errors = [];
    $messages = [];

    // Remplacement des valeur par défaut par celles de l'utilisateur
    if (isset($_POST['email'])) {
        $formData['email'] = $_POST['email'];
    }

    if (isset($_POST['subject'])) {
        $formData['subject'] = $_POST['subject'];
    }

    // if (isset($_POST['message'])) {
    //     $formData['message'] = $_POST['message'];
    // }

    // Validation des données envoyées par l'utiilisateur
    if (!isset($_POST['email']) || empty($_POST['email'])) {
        $errors['email'] = true;
        $messages['email'] = "Merci de renseigner votre pseudo";
    } elseif (strlen($_POST['email']) < 4) {
        $errors['email'] = true;
        $messages['email'] = "Votre login doit faire 4 caractères minimum";
    } elseif (strlen($_POST['email']) > 15) {
        $errors['email'] = true;
        $messages['email'] = "Votre login doit faire 100 caractères maximum";
    } elseif ($_POST['email'] !== "toto") {
        $errors['email'] = true;
        $messages['email'] = "Le pseudo ou le mot de passe est incorrect !";
    }


    // Le mot de passe fait plus de 4 caractères et moins de 100 caractères
    if (!isset($_POST['subject']) || empty($_POST['subject'])) {
        $errors['subject'] = true;
        $messages['subject'] = "Merci de renseigner votre mot de passe";
    } elseif (strlen($_POST['subject']) < 4) {
        $errors['subject'] = true;
        $messages['subject'] = "Le mot de passe doit faire 4 caractères minimum";
    } elseif (strlen($_POST['subject']) > 100) {
        $errors['subject'] = true;
        $messages['subject'] = "Le mot de passe doit faire 100 caractères maximum";
    } elseif ($_POST['subject'] !== "12345678") {
        $errors['subject'] = true;
        $messages['subject'] = "Le pseudo ou le mot de passe est incorrect ! ";
    }

    if (!$errors) {
        send_id_user($_POST['email'], $_POST['subject']);
    }
}

// affichage du rendu d'un template
echo $twig->render('login.html.twig', [
    // transmission de données au template
    'errors' => $errors,
    'messages' => $messages,
    'formData' => $formData,
]);

