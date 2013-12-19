<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd"
    >
<html lang="fr">
    <head>
        <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8" />
        <title>[Formations IPM] Modification de votre mot de passe</title>
    </head>
    <body>
        <p>
            Vous avez demand&eacute; la modification de votre mot de passe sur
            l'application de gestion des formations de l'IPM. Pour modifier votre
            mot de passe, cliquez sur le lien ci-dessous
        </p>
        <p>
            <a href="<?php echo ACCOUNT_URL; ?>?action=reset&amp;resetkey=<?php echo $this->resetkey; ?>">
                <?php echo ACCOUNT_URL; ?>?action=reset&amp;resetkey=<?php echo $this->resetkey; ?>
            </a>
            <em>Ce lien est valide jusqu'au <?php echo $this->date; ?>.</em>
        </p>
        <p>
            En cas de probl&egrave;me, vous pouvez contacter Nicole Marion
            &lt;<a href="mailto:nicole.marion@uclouvain.be">nicole.marion@uclouvain.be</a>&gt;
        </p>
    </body>
</html>