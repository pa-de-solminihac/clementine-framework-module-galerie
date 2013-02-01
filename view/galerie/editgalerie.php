<div class="gallery_edit">
<?php
if (isset($data['gallery']) && $data['gallery']) {
    $gallery = $data['gallery'];
}
?>
    <form name="edit_gallery" method="post" action="<?php echo __WWW__; ?>/galerie/editgalerie?id=<?php
    if (isset($gallery['id'])) {
        echo $gallery['id'];
    } else {
        echo '0';
    } ?>" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php
    if (isset($gallery['id'])) {
        echo $this->getModel('fonctions')->htmlentities($gallery['id']);
    }
?>" />
        <p class="form_gallery_edit_title">
            <label>Titre</label>
            <input type="text" name="title" value="<?php
    if (isset($gallery['title'])) {
        echo $gallery['title'];
    }
?>" />
        </p>
        <p class="form_gallery_edit_description">
            <label>Description</label>
            <textarea name="description"><?php
    if (isset($gallery['description'])) {
        echo $gallery['description'];
    }
?></textarea>
        </p>
        <p id="form_gallery_edit_submit"><input type="submit" name="valider" value="Valider" /></p>
<?php
if (isset($data['erreurs'])) {
    print_r($data['erreurs']);
}
?>
    </form>
<?php
if (isset($gallery['id'])) {
?>
    <div>
        <div id="filelist"></div>
        <br />
        <a id="pickfiles" href="#">Sélectionnez les images</a> |
        <a id="uploadfiles" href="#">Uploadez les images</a>
    </div><br />
    <ul class="list-images">
<?php
$this->getBlock('galerie/listimages', $data);
?>
    </ul>
    <div class="spacer"></div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    var uploader = new plupload.Uploader({
        runtimes : 'html5,flash,html4',
        browse_button : 'pickfiles',
        max_file_size : '10mb',
        unique_names: true,
        url : '<?php echo __WWW__; ?>/galerie/upload?id=<?php echo (int) $gallery['id']; ?>',
        resize : {width : <?php echo Clementine::$config['module_galerie']['width_image']; ?>, height : <?php echo Clementine::$config['module_galerie']['height_image']; ?>, quality : 100},
        flash_swf_url : '<?php echo __WWW_ROOT_JSTOOLS__; ?>/skin/plupload/plupload.flash.swf',
        filters : [
            {title : "Image files", extensions : "jpg,gif,png"},
        ]
    });


    uploader.bind('FilesAdded', function(up, files) {
        $.each(files, function(i, file) {
            $('#filelist').append(
                '<div id="' + file.id + '">' +
                file.name + ' (' + plupload.formatSize(file.size) + ') <b><\/b>' +
            '</\div>');
        });
    });

    uploader.bind('UploadProgress', function(up, file) {
        $('#' + file.id + " b").html(file.percent + "%");
        if (file.percent == 100) {
            $.ajax({
                async: true,
                type: "get",
                url: "<?php echo __WWW__; ?>/galerie/listimages",
                data: "id=<?php echo $gallery['id']; ?>",
                success: function(data) {
                    jQuery(".list-images").css({display:"none"});
                    jQuery(".list-images").html(data);
                    jQuery(".list-images").fadeIn(500);
                }
            });
        }
    });

    $('#uploadfiles').click(function(e) {
        uploader.start();
        e.preventDefault();
    });

    uploader.init();
});
</script>
<?php
}
?>
