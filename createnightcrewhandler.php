<?php
/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 16/08/16
 * Time: 11:38
 */

session_start();
setlocale(LC_ALL, "da_DK");
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});

$data = $_POST;
if(!empty($data)) {
    if($data['captcha'] == $_SESSION['captcha']['code']) {
        $nightcrewmodel = new CreateNightCrewModel($data);
        $nightcrewcontroller = new CreateNightCrewController();
        $nightcrew = $nightcrewcontroller->insertNightCrew($nightcrewmodel);
        print(json_encode($nightcrew));
    }
    else {
        $tmp['message'] = "Forkert kode";
        $tmp['status'] = false;
        print(json_encode($tmp));
    }
}