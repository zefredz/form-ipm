<?php include TEMPLATE_PATH.'/header.tpl.php'; ?>
    
    <div id="container">
        <!-- the tabs -->
        <ul class="tabs">
        
            <?php foreach ( $this->tabs as $id => $label ): ?>
            
            <li><a href="#<?php echo $id; ?>_tab" id="<?php echo $id; ?>Tab"><?php echo $label ?></a></li>
            
            <?php endforeach; ?>
            
        </ul>
        <!-- tab "panes" -->
        <div class="panes">
        
            <?php foreach ( $this->panes as $id => $pane ): ?>
            
            <div id="<?php echo $id; ?>Pane">
                <div id="<?php echo $id; ?>Container">
                <?php echo $pane->render(); ?>
                </div>
            </div>
            
            <?php endforeach; ?>
            
        </div>
    
    </div><!-- container -->
    
<?php include TEMPLATE_PATH.'/footer.tpl.php'; ?>
