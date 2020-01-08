<script src="js/notifIt.js"></script>
<link href="css/notifIt.css" rel="stylesheet">
<script>    
    $(document).ready(function() {
    <?php
        if(!empty($_SESSION['notif_mensagem'])){
            echo "notif({
                    msg: \"".$_SESSION['notif_mensagem']."\",
                    type: \"".$_SESSION['notif_tipo']."\"
                });";
            unset($_SESSION['notif_mensagem']);
            unset($_SESSION['notif_tipo']);
        }
    ?>
});
</script>