<?php
class ModelExtensionModuleAdvancedReviews extends Model {
    public function install(){        
        $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "advanced_reviews`(                  
            `review_id` int(11) NOT NULL,
            `verified` tinyint(4) NOT NULL DEFAULT 0,
            `image` varchar(255),
            `email` varchar(96),
            `coupon_sent` tinyint(4) NOT NULL DEFAULT 0,
            PRIMARY KEY(`review_id`)
        );";

        $this->db->query($sql); 

        $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX .  "advanced_reviews_mailtext` (
            `language_id` int(11) NOT NULL,
            `subject` TEXT NOT NULL,
            `mailtext` TEXT NOT NULL,
            PRIMARY KEY(`language_id`)
        );";

        $this->db->query($sql);        
    }

    public function uninstall(){
        $sql = "DROP TABLE IF EXISTS `" . DB_PREFIX . "advanced_reviews`";
        $this->db->query($sql);

        $sql = "DROP TABLE IF EXISTS `" . DB_PREFIX . "advanced_reviews_mailtext`";
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


    public function saveMailtexts($mailtexts) {
        foreach ($mailtexts as $key => $mailtext) {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "advanced_reviews_mailtext` SET `language_id` = '" . (int)$key . "', `mailtext` = '" . $this->db->escape($mailtext["mailtext"]) . "', `subject` = '" . $this->db->escape($mailtext["subject"]) . "' ON DUPLICATE KEY UPDATE `mailtext` = '" . $this->db->escape($mailtext["mailtext"]) . "', `subject` = '" . $this->db->escape($mailtext["subject"]) . "'");
        }
    }

    public function getMailtexts() {
        $mailtexts = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "advanced_reviews_mailtext");

		foreach ($query->rows as $result) {
			$mailtexts[$result['language_id']] = array(
				'mailtext'      => $result['mailtext'],
                'subject'       => $result['subject']
			);
		}

		return $mailtexts;
    }

    public function getMailtext($language_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "advanced_reviews_mailtext WHERE `language_id` = '" . (int)$language_id . "'");
		return $query->row;
    }

    public function setCouponSent($review_id) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "advanced_reviews SET `review_id` = '" . (int)$review_id . "', `verified` = 0, `image` = '', `email` = '' ON DUPLICATE KEY UPDATE `coupon_sent` = '1'");
    }

    public function totalCouponsReceived($email) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "advanced_reviews` WHERE `email` = '" . $this->db->escape($email) . "' AND `coupon_sent` = 1");
		return $query->row['total'];
    }

    public function addNewCoupon($data) {
        $this->load->model('marketing/coupon');
        $this->model_marketing_coupon->addCoupon($data);
    }

    public function generateCoupon($length = 10) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
};