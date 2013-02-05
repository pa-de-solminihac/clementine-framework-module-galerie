            <div class="galerie_content">
<?php
if (isset($data['galleries']) && is_array($data['galleries']) && count($data['galleries'])) {
    foreach ($data['galleries'] as $gallery) {
        if (isset($gallery['images']) && is_array($gallery['images']) && count($gallery['images'])) {
?>
                <div class="galerie">
<?php
            if (Clementine::$config['module_galerie']['show_title']) {
?>
                    <h2><?php echo $gallery['title']; ?></h2>
<?php
            }
?>

                    <div class="detailgalerie_images">
<?php
            if (!Clementine::$config['module_galerie']['colorbox']) {
?>
                        <div class="detailgalerie_big_pic">
                            <img src="<?php echo(__WWW_ROOT__ . $gallery['images'][0]["url"]); ?>" alt="<?php echo($gallery['images'][0]["title"]); ?>" />
                            <div class="spacer"></div>
                            <br />
                        </div>
<?php
            }
?>
                        <div class="detailgalerie_gallerie">
                        <?php
                            $num_image = 1;
                            foreach ($gallery['images'] as $image) {
                        ?>
<?php
            if (Clementine::$config['module_galerie']['colorbox']) {
?>
                            <a class="colorbox" href="<?php echo(__WWW_ROOT__ . $image["url"]); ?>" rel="<?php echo $gallery['id']; ?>">
<?php
}
?>
                            <img src="<?php echo(__WWW_ROOT__ . $image["url"]); ?>" alt="<?php echo($image["title"]); ?>" />
<?php
            if (Clementine::$config['module_galerie']['colorbox']) {
?>
                            </a>
<?php
}
?>
                            <span class="detailgalerie_hspacer">&nbsp;</span>
                        <?php
                                $num_image ++;
                            }
                        ?>
                        </div>
                        <div class="spacer">&nbsp;</div>
<?php
            if (Clementine::$config['module_galerie']['show_description']) {
                echo $gallery['description'];
            }
?>
                    </div>
                </div>
                <div class="spacer"></div>
<?php
        }
    }
}
?>
            </div>

<script type="text/javascript">
<?php
if (Clementine::$config['module_galerie']['colorbox']) {
?>
$('.detailgalerie_gallerie a.colorbox').colorbox();
<?php
} else {
?>
$('.detailgalerie_gallerie img').click(function(){
    var pic_src = $(this).attr('src');
<?php
    if (Clementine::$config['module_galerie']['transition'] == 'fade') {
?>
    $(this).parent().parent().find('.detailgalerie_big_pic img').fadeOut('500', function() {
        $(this).parent().parent().find('.detailgalerie_big_pic img').attr('src', pic_src);
        $(this).parent().parent().find('.detailgalerie_big_pic img').fadeIn('500');
    });
<?php
    } elseif (Clementine::$config['module_galerie']['transition'] == 'show') {
?>
    $(this).parent().parent().find('.detailgalerie_big_pic img').hide('500', function() {
        $(this).parent().parent().find('.detailgalerie_big_pic img').attr('src', pic_src);
        $(this).parent().parent().find('.detailgalerie_big_pic img').show('500');
    });
<?php
    } else {
?>
    $(this).parent().parent().find('.detailgalerie_big_pic img').attr('src', pic_src);
<?php
    }
?>
});
<?php
}
?>
</script>
