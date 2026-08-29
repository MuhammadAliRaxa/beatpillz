DELETE FROM `settings` WHERE `key`="watermark";
INSERT INTO `settings` (`key`, `value`) VALUES
('watermark', '{\"image\":\"images\\/watermark\\/watermark.png\",\"status\":0,\"position\":\"fill\",\"width\":\"200\",\"height\":\"30\",\"rotate\":\"35\",\"opacity\":\"10\"}');
