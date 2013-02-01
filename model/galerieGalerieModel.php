<?php
/**
 * galerieGalerieModel : module de galerie d'images
 *
 * @package
 * @version $id$
 * @copyright
 * @author Simon <simon@quai13.com>
 * @license
 */
class galerieGalerieModel extends galerieGalerieModel_Parent
{

    public $table_gallery                      = 'clementine_gallery';
    public $table_gallery_image                = 'clementine_gallery_image';

    /**
     * getAllGalleries : Retourne toutes les galeries
     *
     * @access public
     * @return array $t_return ou FALSE s'il n'y a pas d'enregistrements
     */
    public function getAllGalleries()
    {
        $t_return = array();
        $db = $this->getModel('db');
        $sql = "SELECT *
            FROM `" . $db->escape_string($this->table_gallery) . "`";
        $stmt_sql = $db->query($sql);
        while($row = $db->fetch_assoc($stmt_sql))
        {
            foreach ($row as $key => $val) {
                $row[$key] = stripslashes($val);
            }
            $t_return[] = $row;
        }
        return $t_return;
    }

    /**
     * getGalleryById : retourne une galerie selon son ID
     *
     * @param integer $id_gallery
     * @access public
     * @return array ou FALSE s'il n'y a pas d'enregistrements
     */
    public function getGalleryById($id_gallery)
    {
        $db = $this->getModel('db');
        $sql = "SELECT *
            FROM `" . $db->escape_string($this->table_gallery) . "`
            WHERE id = '" . (int) $id_gallery . "'";
        $gallery = $db->fetch_assoc($db->query($sql));
        // stripslashes car les contenus sont echappes avant d'etre enregistres en BD
        if ($gallery) {
            foreach ($gallery as $key => $val) {
                $gallery[$key] = stripslashes($val);
            }
        }
        return $gallery;
    }

    /**
     * getImagesGallery : retourne les images d'une galerie selon son ID
     *
     * @param integer $id_gallery
     * @access public
     * @return array ou FALSE s'il n'y a pas d'enregistrements
     */
    public function getImagesGallery($id_gallery)
    {
        $db = $this->getModel('db');
        $t_return = array();
        $sql = "SELECT *
            FROM `" . $db->escape_string($this->table_gallery_image) . "`
            WHERE id_gallery = '" . (int) $id_gallery . "'";
        $stmt_sql = $db->query($sql);
        while($row = $db->fetch_assoc($stmt_sql))
        {
            foreach ($row as $key => $val) {
                $row[$key] = stripslashes($val);
            }
            $t_return[] = $row;
        }
        return $t_return;
    }

    /**
     * addGallery : ajoute ou modifie une galerie
     *
     * @param integer $id_gallery
     * @access public
     * @return TRUE ou FALSE
     */
    public function addGallery($donnees)
    {
        $db = $this->getModel('db');
        if ($donnees['id'] == 0) {
            $sql = "INSERT INTO `" . $db->escape_string($this->table_gallery) . "` (title, description, date_creation)
                VALUES (
                    '" . $db->escape_string($donnees['title']) . "',
                    '" . $db->escape_string($donnees['description']) . "',
                    '" . $db->escape_string($donnees['date_creation']) . "'
                )";
            $db->query($sql);
            $sql = 'SELECT LAST_INSERT_ID()';
            $id = $db->fetch_assoc($db->query($sql));
            $id = $id['LAST_INSERT_ID()'];
        } else {
            $sql = "UPDATE  `" . $db->escape_string($this->table_gallery) . "` SET
                        title       = '" . $db->escape_string($donnees['title']) . "',
                        description = '" . $db->escape_string($donnees['description']) . "'
                     WHERE id = '" . $db->escape_string($donnees['id']) . "'";
            $db->query($sql);
            $id = $donnees['id'];
        }
        return $id;
    }

    /**
     * addImage : ajoute ou modifie une galerie
     *
     * @param integer $id_gallery
     * @access public
     * @return TRUE ou FALSE
     */
    public function addImage($id_gallery, $url, $date_creation)
    {
        $db = $this->getModel('db');
        $sql = "INSERT INTO `" . $db->escape_string($this->table_gallery_image) . "` (id_gallery, url, date_creation)
            VALUES (
                '" . $db->escape_string($id_gallery) . "',
                '" . $db->escape_string($url) . "',
                '" . $db->escape_string($date_creation) . "')";
        return $db->query($sql);
    }

    /**
     * deleteGallery : supprime une galerie
     *
     * @param integer $id_gallery
     * @access public
     * @return TRUE ou FALSE
     */
    public function deleteGallery ($id_gallery)
    {
        $db = $this->getModel('db');
        $id_gallery = (int) $id_gallery;
        $sql = "DELETE FROM `" . $db->escape_string($this->table_gallery) . "` WHERE id = '" . (int) $id_gallery . "' LIMIT 1 ";
        return $db->query($sql);
    }

    /**
     * deleteImage : supprime une image
     *
     * @param integer $id
     * @access public
     * @return TRUE ou FALSE
     */
    public function deleteImage ($id)
    {
        $id = (int) $id;
        $db = $this->getModel('db');
        $sql = "SELECT * FROM `" . $db->escape_string($this->table_gallery_image) . "` WHERE id = '" . (int) $id . "'";
        $stmt = $db->query($sql);
        $image = $db->fetch_array($stmt);
        if (file_exists(__FILES_ROOT__ . $image['url'])) {
            unlink(__FILES_ROOT__ . $image['url']);
        }
        $sql = "DELETE FROM `" . $db->escape_string($this->table_gallery_image) . "` WHERE id = '" . (int) $id . "' LIMIT 1 ";
        return $db->query($sql);
    }

    /**
     * publishGallery : publie ou dépublie une galerie
     *
     * @param integer $id_gallery
     * @access public
     * @return TRUE ou FALSE
     */
    public function publishGallery ($active, $id_gallery)
    {
        $db = $this->getModel('db');
        $sql = "UPDATE `" . $db->escape_string($this->table_gallery) . "` SET
                    active = '" . (int) $active . "'
                 WHERE id = '" . (int) $id_gallery . "' ";
        return $db->query($sql);
    }
}
?>
