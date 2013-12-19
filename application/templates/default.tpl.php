<?php include TEMPLATE_PATH.'/header.tpl.php'; ?>

    <h1><?php echo $this->title; ?></h1>
    <?php if ( property_exists( $this, 'subTitle' ) ): ?>
    <h2><?php echo $this->subTitle; ?></h2>
    <?php endif; ?>
    <div class="content">
        <?php echo $this->content; ?>
    </div>

<?php include TEMPLATE_PATH.'/footer.tpl.php'; ?>
