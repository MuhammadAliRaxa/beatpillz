DELETE FROM `settings` WHERE `key` = 'faker';
INSERT INTO `settings` (`key`, `value`) VALUES
('faker', '{\"api_provider\":\"gemini\",\"gemini_api_key\":null,\"openai_api_key\":null}');

