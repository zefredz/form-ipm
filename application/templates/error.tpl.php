<?php include TEMPLATE_PATH.'/header.tpl.php'; ?>

    <h1>Une erreur s'est produite</h1>
    <h2><?php echo $this->errorMessage; ?></h2>
    <p>
        Veuillez réessayer plus tard.<br />
        Si l'erreur persistait, vous pouvez contacter 
        &lt;<a href="mailto:<?php echo APPLICATION_MANAGER; ?>"><?php echo APPLICATION_MANAGER; ?></a>&gt;.
    </p>
    <?php if ( defined('DEBUG_MODE') && DEBUG_MODE ):?>
    <p>Merci de communiquer le message suivant dans votre email :</p>
    <pre>
        <?php echo $this->errorDump; ?>
    </pre>
    <?php endif; ?>

<?php include TEMPLATE_PATH.'/footer.tpl.php'; ?>
