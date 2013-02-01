<?php
class galerieGalerieController extends galerieGalerieController_Parent
{

    /**
     * indexAction : affiche la liste des galeries
     *
     * @access public
     * @return void
     */
    public function indexAction()
    {
        if (Clementine::$config['module_galerie']['colorbox']) {
            if (Clementine::$config['module_jstools']['use_google_cdn']) {
                $this->getModel('cssjs')->register_js('jquery', array('src' => 'https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js'));
            } else {
                $this->getModel('cssjs')->register_js('jquery', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/jquery/jquery.min.js'));
            }
            $this->getModel('cssjs')->register_css('jquery.colorbox', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/colorbox/colorbox.css', 'media' => 'screen'));
            $this->getModel('cssjs')->register_js('jquery.colorbox', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/colorbox/jquery.colorbox-min.js'));
        }
        $this->data["body"] = "galerie";
        $galleries = $this->getModel('galerie')->getAllGalleries();
        foreach ($galleries as $gallery) {
            $this->data['galleries'][$gallery['id']] = $gallery;
            $this->data['galleries'][$gallery['id']]['images'] = $this->getModel('galerie')->getImagesGallery($gallery['id']);
        }
    }

    /**
     * listegalerieAction : affiche une liste des galeries
     *
     * @access public
     * @return void
     */
    public function listegalerieAction()
    {
        if (!$this->getModel('users')->needPrivilege(Clementine::$config['module_galerie']['droit_administration'])) {
            if (__DEBUGABLE__ && Clementine::$config['clementine_debug']['display_errors']) {
                echo 'Vous n\'avez pas les privilèges pour afficher cette page';
            }
            die();
        }
        $this->data['galleries'] = $this->getModel('galerie')->getAllGalleries();
        $delete = $this->getModel('fonctions')->ifGet("int", "delete");
        if ($delete) {
            if ($this->getModel('galerie')->deleteGallery($delete)) {
                $this->getModel('fonctions')->redirect(__WWW__ . '/galerie/listegalerie');
            }
        }
        $publish = $this->getModel('fonctions')->ifGet("int", "active");
        if ($publish == -1 || $publish == 1) {
            $id = $this->getModel('fonctions')->ifGet("int", "id");
            if ($this->getModel('galerie')->publishGallery($publish, $id)) {
                $this->getModel('fonctions')->redirect(__WWW__ . '/galerie/listegalerie');
            }
        }
    }

    /**
     * editgalerieAction : édition d'une galerie
     *
     * @access public
     * @return void
     */
    public function editgalerieAction()
    {
        if (!$this->getModel('users')->needPrivilege(Clementine::$config['module_galerie']['droit_administration'])) {
            if (__DEBUGABLE__ && Clementine::$config['clementine_debug']['display_errors']) {
                echo 'Vous n\'avez pas les privilèges pour afficher cette page';
            }
            die();
        }
        if (Clementine::$config['module_jstools']['use_google_cdn']) {
            $this->getModel('cssjs')->register_js('jquery', array('src' => 'https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js'));
        } else {
            $this->getModel('cssjs')->register_js('jquery', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/jquery/jquery.min.js'));
        }
        $this->getModel('cssjs')->register_js('plupload', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/plupload/plupload.js'));
        $this->getModel('cssjs')->register_js('plupload.flash', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/plupload/plupload.flash.js'));
        $this->getModel('cssjs')->register_js('plupload.html4', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/plupload/plupload.html4.js'));
        $this->getModel('cssjs')->register_js('plupload.html5', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/plupload/plupload.html5.js'));
        $this->getModel('cssjs')->register_js('jquery.plupload.queue', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/plupload/jquery.plupload.queue/jquery.plupload.queue.js'));
        $this->getModel('cssjs')->register_css('plupload.queue', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/plupload/jquery.plupload.queue/css/jquery.plupload.queue.css', 'media' => 'screen'));
        $ns = $this->getModel('fonctions');
        if ($_POST) {
            $id            = $ns->ifPost("int", "id");
            $title         = $ns->ifPost("string", "title");
            $description   = $ns->ifPost("string", "description");
            $date_creation = date('Y-m-d H:i:s');
            $donnees['id']            = $id;
            $donnees['title']         = $title;
            $donnees['description']   = $description;
            $donnees['date_creation'] = $date_creation;
            // verification des donnees requises
            $erreurs = array();
            if (!strlen($id)) {
                $erreurs['id'][] = 'Le champ id est obligatoire';
            }
            if (!strlen($title)) {
                $erreurs['title'][] = 'Le champ titre est obligatoire';
            }
            if (count($erreurs)) {
                $this->data['erreurs'] = $erreurs;
            } else {
                if ($new_id = $this->getModel('galerie')->addGallery($donnees)) {
                    if ($id == 0) {
                        $ns->redirect(__WWW__ . '/galerie/editgalerie?id=' . $new_id);
                    } else {
                        $ns->redirect(__WWW__ . '/galerie/listegalerie');
                    }
                } else {
                    $this->data['erreurs']['sauvegarde'] = 'Erreur lors de la sauvegarde';
                }
            }
        }
        $ns = $this->getModel('fonctions');
        $id_gallery = $ns->ifGet("int", "id");
        if ($id_gallery) {
            $this->data['gallery'] = $this->getModel('galerie')->getGalleryById($id_gallery);
            $this->data['gallery']['images'] = $this->getModel('galerie')->getImagesGallery($id_gallery);
        }
    }

    /**
     * listimagesAction : affiche la liste des images
     *
     * @access public
     * @return void
     */
    public function listimagesAction()
    {
        $ns = $this->getModel('fonctions');
        $id_gallery = $ns->ifGet("int", "id");
        if ($id_gallery) {
            $this->data['gallery'] = $this->getModel('galerie')->getGalleryById($id_gallery);
            $this->data['gallery']['images'] = $this->getModel('galerie')->getImagesGallery($id_gallery);
        }
    }

    /**
     * uploadAction : upload des images via plupload
     *
     * @access public
     * @return void
     */
    public function uploadAction()
    {
        $ns = $this->getModel('fonctions');
        $id = $ns->ifGet("int", "id");
        $name = $ns->ifPost("string", "name");
        $name = preg_replace('@\.\./@', '', $name);
        if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name']) && strlen($name)) {
            if ($_FILES['file']['size'] <= 1000000) {
                $infosfichier = pathinfo($_FILES['file']['name']);
                $extension_upload = $infosfichier['extension'];
                $extensions_autorisees = array('jpg', 'jpeg', 'JPG', 'JPEG');
                if (in_array($extension_upload, $extensions_autorisees)) {
                    move_uploaded_file($_FILES['file']['tmp_name'], __FILES_ROOT__ . '/files/media/images/galeries/' . $name);
                    $args['filename'] = __FILES_ROOT__ . '/files/media/images/galeries/' . $name;
                    $args['canevaswidth'] = Clementine::$config['module_galerie']['width_image'];
                    $args['canevasheight'] = Clementine::$config['module_galerie']['height_image'];
                    $args['cropwidth'] = Clementine::$config['module_galerie']['width_image'];
                    $args['cropheight'] = Clementine::$config['module_galerie']['height_image'];
                    $args['color'] = Clementine::$config['module_galerie']['bgcolor_image'];
                    $args['alpha'] = Clementine::$config['module_galerie']['transparence_image'];
                    $args['interieur'] = Clementine::$config['module_galerie']['crop_image'];
                    $args['save_filename'] = __FILES_ROOT__ . '/files/media/images/galeries/' . $name;
                    $ns->img_resize($args);
                    $this->getModel('galerie')->addImage($id, '/files/media/images/galeries/' . $name, date('Y-m-d H:i:s'));
                } else {
                    die('L\'extension du fichier n\'est pas supportée');
                }
            } else {
                die('Le fichier est trop volumineux');
            }
        }
        $this->data['id'] = (int) $id;
        $this->data['name'] = $name;
    }

    /**
     * deleteimageAction : supprime une image
     *
     * @access public
     * @return void
     */
    public function deleteimageAction()
    {
        $ns = $this->getModel('fonctions');
        $id = $ns->ifGet("int", "id");
        if ($id) {
            $this->getModel('galerie')->deleteImage($id);
        }
    }

}
?>
