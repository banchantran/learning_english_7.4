ALTER TABLE `learning_english`.`items`
    ADD COLUMN `is_crawl` tinyint(4) DEFAULT 0 AFTER `audio_name`;

ALTER TABLE `learning_english`.`items`
    ADD COLUMN `text_note` varchar(255) AFTER `text_destination`;
