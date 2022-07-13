<?php
class ModelExtensionModuleAdvancedReviews extends Model {
    public function install(){        
        $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "advanced_reviews`(                  
            `review_id` int(11) NOT NULL,
            `verified` tinyint(4) NOT NULL DEFAULT 0,
            `image` varchar(255),
            `email` varchar(96)
            PRIMARY KEY(`review_id`)
        );";

        $this->db->query($sql);        
    }

    public function uninstall(){
        $sql = "DROP TABLE IF EXISTS `" . DB_PREFIX . "advanced_reviews`";
        $this->db->query($sql);
    }

    public function getAdvancedReview($review_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "advanced_reviews WHERE review_id = '" . (int)$review_id . "'");
		return $query->row;
    }

    public function createOrUpdateAdvancedReview($review_id, $data) {
        
        
        if(!isset($data['image'])):
            $data['image'] = "";
        endif;

        $this->db->query("INSERT INTO " . DB_PREFIX . "advanced_reviews SET `review_id` = '" . (int)$review_id . "', `verified` = " . (int)$data['verified'] . ", `image` = '" . $this->db->escape($data['image']) . "', `email` = '" . $this->db->escape($data['email']) . "'
        ON DUPLICATE KEY UPDATE `verified` = " . (int)$data['verified'] . ", `image` = '" . $this->db->escape($data['image']) . "', `email` = '" . $this->db->escape($data['email']) . "'");
    }

    public function deleteAdvancedReview($review_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "advanced_reviews WHERE `review_id` = '" . (int)$review_id . "'");
    }
};