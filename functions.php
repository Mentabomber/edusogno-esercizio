<?php
function isAdmin()
{
        if ($_SESSION['tipo_user']['tipo_user'] == "admin") {
                return true;
        }else{
                return false;
        }
}
?>