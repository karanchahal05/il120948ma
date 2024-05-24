<?php 
    include_once __DIR__.'/header-backened-nonfriend.php';

    if(isset($_POST['view'])) {
        if(isset($_POST['templatetitle'])) {
            $encemail = encryptSingleDataGivenIv([$_SESSION['user']->email], $key, $_SESSION['user']->iv); // make this encemail accessible across pages
            $templatetitle = mysqli_real_escape_string($conn, $_POST['templatetitle']);
            $enctemplatetitle = encryptSingleDataGivenIv([$templatetitle], $key, $_SESSION['user']->iv);

            $enctemplateresp = getDatafromSQLResponse(["email", "title", "textt", "datentimeinteger", "iv"], executeSQL($conn, "SELECT * FROM templates WHERE email='$encemail' AND title='$enctemplatetitle';", "nothing", "nothing", "select", "nothing"));
            if(count($enctemplateresp) != 1) {
                exit("Error in viewing the template!");
            }
            $dectemplateresp = decryptFullData($enctemplateresp, $key, 4)[0];

            $templateobject = new stdClass();
            $_SESSION["template"] = createTemplateObject($templateobject, $dectemplateresp[0], $dectemplateresp[1], $dectemplateresp[2], $dectemplateresp[3], $dectemplateresp[4]);

            echo "true";
        }
        else {
            exit("Error! Not template title!");
        }
    }
    else {
        exit("Error! Commanded to do nothing!");
    }
?>
