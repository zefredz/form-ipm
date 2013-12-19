<?php echo $this->user['prenom'] . ' ' . $this->user['nom']; ?> s'est inscrit &agrave; la formation &agrave; la demande <?php echo $this->session->Formation->related_record->titre; ?><?php if ( ! empty($this->session->sous_titre) ) { echo ' - ' . $this->session->sous_titre; } ?>.

