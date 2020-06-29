<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="Description" content="Enter your description here"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
<link rel="stylesheet" href="css/style.css">
<title>Date Simulator</title>
</head>
<body>

<div class="container-fluid bg">

<?php 
/* Connexion à la base de données et chargement des classes */
include('config/database.php');
include('config/autoload.php');

/* Démarrage de la session */
session_start();

/* Gestion de la deconnexion */
if (isset($_GET['logout']))
{
    session_destroy();
    header('Location: .');
    exit();
}

/* Crée une instance de l'objet GirlManager */
$girlManager = new GirlManager($database);

if (isset($_SESSION['girl'])) {
    $girl = $_SESSION['girl'];
}

/* Crée une instance de l'objet Girl, vérifie si déjà enregistrée et si oui la supprime */
if (isset($_POST['create']) && !empty($_POST['name']) && !empty($_POST['age'])) {
    $girl = new Girl(['name' => ucfirst($_POST['name']), 'age' => $_POST['age']]);

    if ($girlManager->girlExists($girl->getName())) {
        $message = 'Girl already dated.';
        unset($girl);
    } else {
        $girlManager->createGirl($girl);
        $girlManager->updateGirl($girl);
    }
}

if (isset($_POST['nicknameBtn']) && !empty($_POST['nickname'])) {
    $message = $girl->nickname($_POST['nickname']);
    $girlManager->updateGirl($girl);
}

if (isset($_POST['complimentBtn']) && !empty($_POST['compliment'])) {
    $message = $girl->compliment($_POST['compliment']);
    $girlManager->updateGirl($girl);
}

if (isset($_POST['giftBtn']) && !empty($_POST['gift'])) {
    $message = $girl->offerGift($_POST['gift']);
    $girlManager->updateGirl($girl);
}

if (isset($_POST['mentionAgeBtn'])) {
    $message = $girl->mentionHerAge();
    $girlManager->updateGirl($girl);
}

if (isset($_POST['kissBtn'])) {
    $message = $girl->kiss();
    $girlManager->updateGirl($girl);
}

if (isset($girl) && ($girl->getTempted() >= 10)) {
    $girl->setDressed(0);
    $message = ($girl->getName().' est totalement sous le charme... Ses vêtements se consument automatiquement. Victoire !');
}

?>

<div class="row"<?php if (!isset($message)){echo 'style="visibility:hidden;"';}?>>
    <div class="col-12 pt-3">
        <h6 class="text-white">

        <?php
        if (isset($message)) {
            echo ($message);      
        } else {
            echo ('Enjoy the fight !');
        }
        
echo ('
        </h6>
    </div>
</div>
');


if (isset($girl)) {
    ?>
    <div class="pageContent">
        <div class="container girl">
            <div class="row justify-content-around">
                <div class="col-10 col-md-6 card cardDark mt-5">
                    <div class="row card-body">
                        <div class="col-6 offset-3 col-md-4 offset-md-4 text-center">
                            <h5 class="text-center girlName"><?= ucfirst($girl->getName()) ?></h5>
                        </div>
                    </div>
                    <div class="row justify-content-around girlAge mt-2">
                        <div class="col-10 text-center">
                            <h6>Age <?= $girl->getAge() ?></h6>
                            <div class="progress mt-3">
                                <?php
                                if ($girl->getTempted() < 2) {
                                    echo ('
                                        <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="'.$girl->getTempted().'" aria-valuemin="0" aria-valuemax="10" style="width:'.($girl->getTempted() *10).'%">
                                        Tempted '.$girl->getTempted().'/10
                                        </div>
                                    ');
                                } else if ($girl->getTempted() > 2 && $girl->getTempted() < 4) {
                                    echo ('
                                        <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="'.$girl->getTempted().'" aria-valuemin="0" aria-valuemax="10" style="width:'.($girl->getTempted() *10).'%">
                                        Tempted '.$girl->getTempted().'/10
                                        </div>
                                    ');
                                } else {
                                    echo ('
                                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="'.$girl->getTempted().'" aria-valuemin="0" aria-valuemax="10" style="width:'.($girl->getTempted() *10).'%">
                                        Tempted '.$girl->getTempted().'/10
                                        </div>
                                    '); 
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-around mt-2 mb-3">
                        <div class="col-12 text-center">
                            <h5 class="mt-1 actionsNumber">Nombre d'actions effectuées : <?= $girl->getActionsNumber() ?></h5>
                        </div>
                    </div>

                    <div class="row justify-content-around mt-3">
                        <div class="col-12 text-center">
                        <?php
                        if (!$girl->getDressed()) {
                            echo ('<h6>Plus un vêtement en vue sur '.$girl->getName().' !</h6>');
                        } else {
                            echo ('<h6>'.$girl->getName().' est toujours (beaucoup trop) habillée.</h6>');
                        }
                        ?>
                        </div>
                        <div class="col-12 text-center mt-2">
                            <button class="btn btn-sm logoutBtn"><a href="?logout=1">Logout</a></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid actions mt-5">
                <div class="row justify-content-around">
                    <div class="col-12 mb-3">
                        <h5 class="text-center text-white">Choisis une action</h5>
                    </div>

                    <div class="col-10 col-md-2 card actionCard cardAnim mx-2 mb-3">
                        <div class="row justify-content-around card-body">
                            <h5 class="mb-3 text-center">Lui donner un petit nom</h5>
                            <div id="nicknameForm" class="row justify-content-around mb-1">
                                <div class="col-12 text-center mt-3">
                                    <form action="" method="POST">
                                        <label for="nickname">La surnommer :</label>
                                        <input type="text" name="nickname" class="col-12 mr-3 input-sm" minlength="2" maxlength="20" placeholder="Nunuche">
                                        <button id="nicknameBtn" type="submit" name="nicknameBtn" class="btn btn-sm mt-5">Valider</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-10 col-md-2 card actionCard cardAnim mx-2 mb-3">
                        <div class="row justify-content-around card-body">
                            <h5 class="mb-3 text-center">Lui faire un compliment</h5>
                            <div id="complimentForm" class="row justify-content-around mb-1">
                                <div class="col-12 text-center mt-3">
                                    <form action="" method="POST">
                                    <label for="compliment">Dire que vous la trouvez :</label>
                                        <input type="text" name="compliment" class="col-12 mr-3 input-sm" minlength="2" maxlength="20" placeholder="Ponctuelle">
                                        <button id="complimentBtn" type="submit" name="complimentBtn" class="btn btn-sm mt-5">Valider</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-10 col-md-2 card actionCard cardAnim mx-2 mb-3">
                        <div class="row justify-content-around card-body">
                            <h5 class="mb-3 text-center">Lui offrir un cadeau</h5>
                            <div id="giftForm" class="row justify-content-around mb-1">
                                <div class="col-12 text-center mt-3">
                                    <form action="" method="POST">
                                        <label for="gift">Lui offrir :</label>
                                        <input type="text" name="gift" class="col-12 mr-3 input-sm" minlength="2" maxlength="20" placeholder="Un poney">
                                        <button id="giftBtn" type="submit" name="giftBtn" class="btn btn-sm mt-5">Valider</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-10 col-md-2 card actionCard cardAnim mx-2 mb-3">
                        <div class="row justify-content-around card-body">
                            <h5 class="mb-3 text-center">Evoquer son âge</h5>
                            <div id="mentionAgeForm" class="row justify-content-around mb-1">
                                <div class="col-12 text-center mt-3">
                                    <form action="" method="POST">
                                        <div class="col-12 text-center mt-3">
                                            <h6>Risqué, mais peut rapporter beaucoup</h6>
                                            <button id="mentionAgeBtn" type="submit" name="mentionAgeBtn" class="btn btn-sm mt-5">Valider</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-10 col-md-2 card actionCard cardAnim mx-2 mb-3">
                        <div class="row justify-content-around card-body">
                            <h5 class="mb-3 text-center">L'embrasser</h5>
                            <div id="kissForm" class="row justify-content-around mb-1">
                                <div class="col-12 text-center mt-3">
                                    <form action="" method="POST">
                                        <div class="col-12 text-center mt-3">
                                            <h6>Risqué, mais peut rapporter beaucoup</h6>
                                            <button id="kissBtn" type="submit" name="kissBtn" class="btn btn-sm mt-5">Valider</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                            
<?php
} else {
?>
        <div class="col-12 text-center mt-5 mb-5">
            <h4 class="text-white letsDate">Let's date !</h4>
        </div>

        <div class="container">
            <div class="row justify-content-around text-center mt-5 mb-4">
                <div class="col-10 col-md-4 card cardDark">
                    <div class="col-12 mb-3 card-body">
                        <h5>Draguer une nouvelle fille</h5>
                    </div>

                    <form action="" method="POST">
                        <div class="row justify-content-around text-center">      
                            <div class="col-12 mb-2">
                                <input type="text" name="name" class="col-6 mr-3 input-sm" minlength="2" maxlength="20" placeholder="Prénom" required>
                            </div> 
                            <div class="col-12 mb-4">
                                <input type="text" name="age" class="col-6 mr-3 input-sm" minlength="1" maxlength="3" placeholder="Age" required>
                            </div> 
                        </div> 
                        <div class="row justify-content-center text-center">
                            <div class="col-8">
                                <input type="submit" class="btn btn-sm mx-3" name="create" value="Draguer"/>
                            </div> 
                        </div>
                    </form>
                </div>
            </div>
        </div>
<?php
}

if (isset($girl)) {
    $_SESSION['girl'] = $girl;
}

?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>