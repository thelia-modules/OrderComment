
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- order_comment
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `order_comment`;

CREATE TABLE `order_comment`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `order_id` INTEGER NOT NULL,
    `comment` TEXT NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `FI_order_comment_order_id` (`order_id`),
    CONSTRAINT `fk_order_comment_order_id`
        FOREIGN KEY (`order_id`)
        REFERENCES `order` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
