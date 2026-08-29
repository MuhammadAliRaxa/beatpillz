DELETE FROM `storage_providers` WHERE `alias`="s3"; 
DELETE FROM `storage_providers` WHERE `alias`="digitalocean"; 
DELETE FROM `storage_providers` WHERE `alias`="stroj"; 
DELETE FROM `storage_providers` WHERE `alias`="idrive"; 
DELETE FROM `storage_providers` WHERE `alias`="contabo"; 
DELETE FROM `storage_providers` WHERE `alias`="cloudflare"; 

INSERT INTO `storage_providers` (`name`, `alias`, `processor`, `credentials`, `created_at`, `updated_at`) VALUES
('Amazon S3', 's3', 'App\\Http\\Controllers\\Storage\\AmazonS3Controller', '{\"access_key_id\":null,\"secret_access_key\":null,\"default_region\":null,\"bucket\":null,\"url\":null,\"endpoint\":null}', '2024-03-06 00:23:02', '2024-03-06 08:12:36'),
('Digitalocean Spaces', 'digitalocean', 'App\\Http\\Controllers\\Storage\\DigitaloceanController', '{\"access_key_id\":null,\"secret_access_key\":null,\"default_region\":null,\"bucket\":null,\"url\":null,\"endpoint\":null}', '2024-03-06 00:23:02', '2024-03-06 08:12:36'),
('Storj', 'stroj', 'App\\Http\\Controllers\\Storage\\StorjController', '{\"access_key_id\":null,\"secret_access_key\":null,\"default_region\":null,\"bucket\":null,\"url\":null,\"endpoint\":null}', '2024-03-06 00:23:02', '2024-03-06 08:12:36'),
('Idrive E2', 'idrive', 'App\\Http\\Controllers\\Storage\\IdriveE2Controller', '{\"access_key_id\":null,\"secret_access_key\":null,\"default_region\":null,\"bucket\":null,\"url\":null,\"endpoint\":null}', '2024-03-06 00:23:02', '2024-05-17 19:45:33'),
('Contabo Object Storage', 'contabo', 'App\\Http\\Controllers\\Storage\\ContaboController', '{\"access_key_id\":null,\"secret_access_key\":null,\"default_region\":null,\"bucket\":null,\"url\":null,\"endpoint\":null}', '2024-03-06 00:23:02', '2024-03-06 08:12:36'),
('Cloudflare R2', 'cloudflare', 'App\\Http\\Controllers\\Storage\\CloudflareR2Controller', '{\"access_key_id\":null,\"secret_access_key\":null,\"default_region\":null,\"bucket\":null,\"url\":null,\"endpoint\":null}', '2024-03-06 00:23:02', '2024-03-06 08:12:36');
