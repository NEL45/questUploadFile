<?php

$errors=[];

// Je vérifie que le formulaire est soumis, comme pour tout traitement de formulaire.
if($_SERVER["REQUEST_METHOD"] === "POST" )
{
    $formData = array_map('trim', $_POST);
    // chemin vers un dossier sur le serveur qui va recevoir les fichiers transférés (attention ce dossier doit être accessible en écriture)
    $uploadDir = 'uploads/';
    
    // le nom de fichier sur le serveur est celui du nom d'origine du fichier sur le poste du client (mais d'autre stratégies de nommage sont possibles)
    $uploadFile = $uploadDir . uniqid() . '_' . basename($_FILES['avatar']['name']);

    // Je récupère l'extension du fichier
    $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
    // Les extensions autorisées
    $extensions_ok = ['jpg','jpeg','png'];
    // Le poids max géré par PHP par défaut est de 2M
    $maxFileSize = 1000000;

    // Je sécurise et effectue mes tests

    if (empty($formData["user_name"])) {
        $errors[] = "Name is required";
      }
    if (empty($formData["user_name"])) {
        $errors[] = "Firstname is required";
    }
    if (empty($formData["user_age"])) {
        $errors[] = "Age is required";
        }

    /****** Si l'extension est autorisée *************/

    if( (!in_array($extension, $extensions_ok ))){
        $errors[] = 'Veuillez sélectionner une image de type Jpg ou Jpeg ou Png !';
    }

    /****** On vérifie si l'image existe et si le poids est autorisé en octets *************/
    if( file_exists($_FILES['avatar']['tmp_name']) && filesize($_FILES['avatar']['tmp_name']) > $maxFileSize)
    {
    $errors[] = "Votre fichier doit faire moins de 2M !";
    }

    /****** Si je n'ai pas d"erreur alors j'upload *************/
    if(empty($errors))
    {
    // on déplace le fichier temporaire vers le nouvel emplacement sur le serveur. Ca y est, le fichier est uploadé
    move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadFile);

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laisse pas traîner ton File</title>

</head>
<body>
<?php if (!empty($errors)): ?>
    <ul>
        <?php foreach ($errors as $error): ?>
            <li>
                <?= $error ?>
            </li>
        <?php endforeach ?>
    </ul>
<?php endif ?>
<form action="form.php" method="post" enctype="multipart/form-data">
    <div>
        <label for="nom">Name :</label>
        <input type="text" required id="name" name="user_name">
    </div>
    <div>
        <label for="firstname">Firstname :</label>
        <input type="text" required id="firstname" name="user_firstname">
    </div>
    <div>
        <label for="age">Age :</label>
        <input type="text" required id="age" name="user_age">
    </div>
    <div>
        <label for="imageUpload">Upload an profile image</label>
        <input type="file" name="avatar" id="imageUpload" />
    </div>
    <button name="send">Send</button>
</form>

<?php if (isset($uploadFile)): ?>
    <img src="<?= $uploadFile ?>">
<?php endif ?>
</body>
</html>
