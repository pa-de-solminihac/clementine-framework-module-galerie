<div class="galleries_index">
    <table class="galleries_index_list">
<?php 
if (isset($data['galleries']) && is_array($data['galleries']) && count($data['galleries'])) {
?>
        <thead>
            <tr>
                <th class="col1"> Titre </th>
                <th class="col3"> Actions </th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach ($data['galleries'] as $galleries) {
?>
            <tr>
                <td class="col1">
                    <a title="modifier" href="<?php echo __WWW__; ?>/galerie/editgalerie?id=<?php echo $galleries['id']; ?>" >
<?php 
        if (isset($galleries['title'])) {
            echo $galleries['title']; 
        }
?>
                    </a>
                </td>
                <td class="col3">
                    <a class="modifier" href="<?php echo __WWW__; ?>/galerie/editgalerie?id=<?php echo $galleries['id']; ?>" >
                        <img src="<?php echo __WWW_ROOT_GALERIE__; ?>/skin/images/icons/write.png" alt="modifier" />
                    </a>
<?php 
        if ($galleries['active'] == 1) {
?>
                    <a class="voir" href="<?php echo __WWW__; ?>/galerie/listegalerie?active=-1&id=<?php echo $galleries['id']; ?>" >
                        <img src="<?php echo __WWW_ROOT_GALERIE__; ?>/skin/images/icons/tick_circle.png" alt="voir" />
                    </a>
<?php 
        } else {
?>
                    <a class="voir" href="<?php echo __WWW__; ?>/galerie/listegalerie?active=1&id=<?php echo $galleries['id']; ?>" >
                        <img src="<?php echo __WWW_ROOT_GALERIE__; ?>/skin/images/icons/cross_circle.png" alt="voir" />
                    </a>
<?php 
        }
?>
                    <a class="supprimer" href="#" onclick="deleteGallery(<?php echo $galleries['id']; ?>); return false;" >
                        <img src="<?php echo __WWW_ROOT_GALERIE__; ?>/skin/images/icons/delete.png" alt="voir" />
                    </a>
                </td>
            </tr>
<?php 
    }
?>
        </tbody>
    </table>
<?php
} else {
?>
        Il n'y a aucune galerie.
<?php 
}
?>
</div>
<script type="text/javascript">   
    function deleteGallery(idGallerie) {
        if ((confirm('Etes-vous sûr de vouloir supprimer cette galerie ?'))) {
            document.location.href='<?php echo __WWW__; ?>/galerie/listegalerie?delete=' + idGallerie;
        }
    }
</script>

