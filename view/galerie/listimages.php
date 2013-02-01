<?php 
if (isset($data['gallery']) && $data['gallery']) {
    $gallery = $data['gallery']; 
}
if (isset($gallery['images']) && $gallery['images']) {
    foreach ($gallery['images'] as $image) {
?>
        <li id="li_image_<?php echo $image['id']; ?>" style="float: left; width: 150px;">
            <img src="<?php echo __WWW_ROOT__; ?><?php echo $image['url']; ?>" class="apercu_image" width="100" />
            <a href="" class="delete_image" id="<?php echo $image['id']; ?>">
                <img src="<?php echo __WWW_ROOT_GALERIE__; ?>/skin/images/icons/delete.png" alt="delete" />
            </a>
        </li>
<?php 
    }
}
?>
<script type="text/javascript">
$(".delete_image").click(function(){
    if ((confirm('Etes-vous sûr de vouloir supprimer cette image ?'))) {
        $.ajax({
            url: "<?php echo __WWW__; ?>/galerie/deleteimage?id=" + $(this).attr("id"),
            success: function(data) {
                $("#li_image_" + data).remove();
            }
        });
    }
    return false;
});
</script>
