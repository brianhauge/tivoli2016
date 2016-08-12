<?php
/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 08/08/16
 * Time: 20:24
 */

setlocale(LC_ALL, "da_DK");

spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});

$score = new ScoreController();

if(isset($_GET['body'])) {
    print_r($_GET);
    $incomingSmsScoreModel = new IncomingSmsScoreModel();
    $incomingSmsScoreModel->setSmscontent($_GET['body'],$_GET['sender']);
    $score->handleReceivedPoints($incomingSmsScoreModel);
}

else {
?>
<!DOCTYPE html>
<html lang="da">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Brian Hauge Hansen">
        <meta name="description" content="FDF og spejderne indtager Tivoli">
        <title>FDF og spejderne indtager Tivoli - Score</title>
        <link rel="canonical" href="http://haugemedia.net/tivoli2016/">
        <link rel="stylesheet" href="dist/css/bootstrap.min.css" type="text/css">
        <link rel="stylesheet" href="dist/css/bootstrap-theme.min.css" type="text/css">
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="page-header">
                        <h1>Løbsplacering <small>Kl. <?php echo date("H:i"); ?></small></h1>
                    </div>
                    <h3 class="text-muted">Gruppe 1</h3>
                    <?php print($score->getScoreTableByGroup(1)); ?>
                    <h3 class="text-muted">Gruppe 2</h3>
                    <?php print($score->getScoreTableByGroup(2)); ?>
                </div>
            </div>
        </div>
    </body>
</html>
<?php
}