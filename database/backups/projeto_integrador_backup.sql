-- Destyn Trips — backup full (schema + data)
CREATE DATABASE IF NOT EXISTS projeto_integrador CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE projeto_integrador;

-- MySQL dump 10.13  Distrib 8.0.46, for Linux (x86_64)
--
-- Host: localhost    Database: projeto_integrador
-- ------------------------------------------------------
-- Server version	8.0.46

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `destinations`
--

DROP TABLE IF EXISTS `destinations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `destinations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_destinations_is_active` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destinations`
--

LOCK TABLES `destinations` WRITE;
/*!40000 ALTER TABLE `destinations` DISABLE KEYS */;
INSERT INTO `destinations` VALUES (1,'Rio de Janeiro','Cinco dias entre mirantes, praias e cultura carioca — o Rio essencial, com tempo para sentir a cidade.','/frontend/assets/images/rio.jpg',1,'2026-11-27 19:45:02'),(2,'Foz do Iguaçu','Quatro dias de natureza grandiosa: cataratas, trilhas e a energia da Tríplice Fronteira em família.','/frontend/assets/images/foz.jpg',1,'2026-11-27 19:45:02'),(3,'Dubai','Um encontro entre futurismo e tradição: arranha-céus, deserto dourado e hospitalidade árabe em cinco dias inesquecíveis.','/frontend/assets/images/dest-dubai.jpg',1,'2026-11-27 21:24:19'),(4,'Bali','Seis dias de imersão entre templos sagrados, terraços verdes de arroz e o ritmo sereno da costa sul balinesa.','/frontend/assets/images/dest-bali.jpg',1,'2026-11-27 21:24:19'),(5,'Sydney','Harbour cintilante, arquitetura icônica e praias urbanas: o essencial de Sydney em cinco dias bem ritmados.','/frontend/assets/images/dest-sydney.jpg',1,'2026-11-27 21:24:19'),(6,'Phuket','Quatro dias de águas claras, ilhas próximas e a gastronomia vibrante do sul da Tailândia.','/frontend/assets/images/dest-phuket.jpg',1,'2026-11-27 21:24:19'),(7,'Shanghai','Cinco dias entre o Bund clássico, a skyline de Pudong e os sabores que fazem de Xangai uma metrópole irresistível.','/frontend/assets/images/dest-shanghai.jpg',1,'2026-11-27 21:24:19'),(8,'London','História viva às margens do Tâmisa: museus, pontes icônicas e o charme clássico de Londres em cinco dias.','/frontend/assets/images/dest-london.jpg',1,'2026-11-27 21:24:19'),(9,'Paris','Arte, cafés e luz dourada: cinco dias para viver Paris no ritmo certo — entre ícones e passeios sem pressa.','/frontend/assets/images/dest-paris.jpg',1,'2026-11-27 21:24:19'),(10,'Maldives','Quatro dias de águas turquesa, silêncio e horizonte infinito — o escape tropical definitivo.','/frontend/assets/images/dest-maldives.jpg',1,'2026-11-27 21:24:19');
/*!40000 ALTER TABLE `destinations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `packages`
--

DROP TABLE IF EXISTS `packages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `packages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `destination_id` int unsigned NOT NULL,
  `title` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `summary` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration_days` smallint unsigned NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_packages_destination_id` (`destination_id`),
  KEY `idx_packages_is_active` (`is_active`),
  CONSTRAINT `fk_packages_destination` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `chk_packages_duration_days` CHECK ((`duration_days` > 0)),
  CONSTRAINT `chk_packages_price` CHECK ((`price` >= 0))
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `packages`
--

LOCK TABLES `packages` WRITE;
/*!40000 ALTER TABLE `packages` DISABLE KEYS */;
INSERT INTO `packages` VALUES (1,1,'Rio Essencial','Cinco dias entre mirantes, praias e cultura carioca — o Rio essencial, com tempo para sentir a cidade.','Dia 1 — Chegada e orla\nCheck-in e first walk por Copacabana ou Ipanema ao entardecer. Jantar com vista para o mar e clima de boas-vindas.\n\nDia 2 — Cristo Redentor\nSubida ao Corcovado e tempo no mirante mais famoso do Brasil. Tarde livre no Jardim Botânico ou em cafés de botafogo.\n\nDia 3 — Pão de Açúcar\nBondinho até o mirante do Pão de Açúcar, com vistas de baía e cidade. À noite, opção de samba ou jantar no Centro.\n\nDia 4 — Cultura e bairros\nManhã em museus ou centros culturais, tarde em Santa Teresa ou Lapa. Um dia para conhecer o Rio além da praia.\n\nDia 5 — Despedida carioca\nManhã livre na orla e traslado. Ideal para primeira visita: clássicos bem escolhidos, ritmo leve e muita paisagem.',5,1890.00,1,'2026-11-27 19:45:02','2026-11-27 21:40:28'),(2,2,'Cataratas em Família','Quatro dias de natureza grandiosa: cataratas, trilhas e a energia da Tríplice Fronteira em família.','Dia 1 — Chegada à fronteira\nCheck-in e ambientação. Ao entardecer, mirante panorâmico e jantar regional para abrir o roteiro.\n\nDia 2 — Cataratas pelo lado brasileiro\nDia completo no Parque Nacional: passarelas, mirantes e a impressionante Garganta do Diabo. Tempo para fotos e observação da fauna.\n\nDia 3 — Aventura e natureza\nOpção de passeio de barco próximo às quedas (conforme condições) ou trilhas mais longas. Tarde livre para parque das aves ou descanso.\n\nDia 4 — Encerramento\nManhã livre para mais um olhar às quedas ou compras na região antes do traslado. Uma experiência marcante para todas as idades.',4,2190.00,1,'2026-11-27 19:45:02','2026-11-27 21:40:28'),(3,3,'Dubai Skyline','Um encontro entre futurismo e tradição: arranha-céus, deserto dourado e hospitalidade árabe em cinco dias inesquecíveis.','Dia 1 — Chegada e primeira impressão\nApós o check-in, você conhece a Dubai Marina ao entardecer e embarca em um passeio de barco com vista para a skyline iluminada. O jantar opcional à beira-mar completa a abertura do roteiro.\n\nDia 2 — Ícones da cidade moderna\nVisita à Burj Khalifa com acesso ao mirante, tempo livre no Dubai Mall e tarde no bairro histórico de Al Fahidi, onde museus e cafés revelam o contraste entre o Dubai antigo e o contemporâneo.\n\nDia 3 — Cultura, souks e sabores\nManhã nos mercados tradicionais de especiarias e ouro, com pausa para almoço típico. À tarde, tempo livre para praia ou spa, no ritmo de quem quer absorver a cidade sem pressa.\n\nDia 4 — Deserto e pôr do sol\nSafari no deserto com dunas, chá de boas-vindas e o espetáculo do sol se pondo sobre a areia. À noite, jantar beduíno sob as estrelas — o momento mais fotogênico da viagem.\n\nDia 5 — Encerramento à sua escolha\nManhã livre para compras, café com vista ou um último passeio pela Corniche antes do traslado. Um roteiro completo para quem busca luxo, contraste e memórias marcantes.',5,4590.00,1,'2026-11-27 21:24:19','2026-11-27 21:40:28'),(4,4,'Bali Cultural','Seis dias de imersão entre templos sagrados, terraços verdes de arroz e o ritmo sereno da costa sul balinesa.','Dia 1 — Bem-vindo a Ubud\nChegada e ambientação na região cultural da ilha. Caminhada leve pelos arredores, jantar com culinária local e primeira noite cercada pela natureza.\n\nDia 2 — Terraços e espiritualidade\nVisita aos terraços de arroz e ao templo Tirta Empul, com tempo para observar rituais e a paisagem que inspirou gerações de artistas. Tarde livre em cafés e ateliês de Ubud.\n\nDia 3 — Natureza e bem-estar\nTrilha suave, banho em cascata (conforme condições) e espaço para massagem tradicional balinesa. Um dia pensado para desacelerar de verdade.\n\nDia 4 — Costa sul e Uluwatu\nTransfer para o litoral. Cliffs dramáticos, praia e o pôr do sol no templo Uluwatu, seguido de apresentação de dança Kecak ao ar livre.\n\nDias 5 e 6 — Mar, vilas e despedida\nTempo livre para praias do sul, mercados e gastronomia. No último dia, manhã livre e traslado com a sensação de ter vivido Bali além do cartão-postal.',6,3890.00,1,'2026-11-27 21:24:19','2026-11-27 21:40:28'),(5,5,'Sydney Harbour','Harbour cintilante, arquitetura icônica e praias urbanas: o essencial de Sydney em cinco dias bem ritmados.','Dia 1 — Harbour e primeiras luzes\nChegada e walk pela Circular Quay. Vista da Opera House e da Harbour Bridge ao entardecer, com jantar livre em um dos cais mais fotogênicos do mundo.\n\nDia 2 — Ícones de perto\nTour pela Opera House e passagem pela Harbour Bridge. Tarde no Rocks, bairro histórico com galerias, pubs e mirantes sobre o porto.\n\nDia 3 — Bondi e costa leste\nManhã em Bondi Beach, com opção de caminhada costeira até praias vizinhas. Tarde livre para café, surf culture e compras leves.\n\nDia 4 — Ferry para Manly\nTravessia de ferry pelo harbour até Manly: praia, calçadão e clima de cidade praiana a poucos minutos do centro. Retorno ao fim do dia.\n\nDia 5 — Encerramento urbano\nManhã livre para museus, Darling Harbour ou um último brunch com vista antes do traslado. Sydney no equilíbrio perfeito entre cidade e mar.',5,5290.00,1,'2026-11-27 21:24:19','2026-11-27 21:40:28'),(6,6,'Phuket Islands','Quatro dias de águas claras, ilhas próximas e a gastronomia vibrante do sul da Tailândia.','Dia 1 — Chegada e ritmo tropical\nCheck-in e tempo livre na praia. Ao entardecer, passeio por vilas costeiras e jantar com frutos do mar frescos.\n\nDia 2 — Day trip pelas ilhas\nPasseio de barco por enseadas de águas cristalinas, paradas para banho e snorkel. Almoço a bordo ou em praia isolada, conforme a programação do dia.\n\nDia 3 — Cultura local e sabores\nManhã em mercados e templos da região. Tarde livre para spa, praia ou aula rápida de culinária tailandesa.\n\nDia 4 — Último mergulho no azul\nManhã livre para mais uma dose de mar antes do traslado. Um roteiro curto, ensolarado e perfeito para recarregar.',4,3290.00,1,'2026-11-27 21:24:19','2026-11-27 21:40:28'),(7,7,'Shanghai Lights','Cinco dias entre o Bund clássico, a skyline de Pudong e os sabores que fazem de Xangai uma metrópole irresistível.','Dia 1 — O Bund ao entardecer\nCaminhada pelo Bund com a skyline de Pudong se acendendo. Introdução à cidade e jantar com vista para o Huangpu.\n\nDia 2 — Tradição e bairros antigos\nManhã no centro histórico, templos e ruelas comerciais. Tarde em casas de chá e lojas de artesanato.\n\nDia 3 — Pudong e futuro\nVisita ao distrito financeiro, mirante e museus de arquitetura contemporânea. Noite livre para a vida noturna ou um show.\n\nDia 4 — Sabores e detalhes\nTour gastronômico leve (dumplings, macarrão e mercados). Tarde livre para shopping ou galerias.\n\nDia 5 — Despedida urbana\nManhã livre e traslado. Um roteiro intenso, fotogênico e cheio de camadas culturais.',5,4190.00,1,'2026-11-27 21:24:19','2026-11-27 21:40:28'),(8,8,'London Classics','História viva às margens do Tâmisa: museus, pontes icônicas e o charme clássico de Londres em cinco dias.','Dia 1 — Westminster e o Tâmisa\nBig Ben, Houses of Parliament e primeira caminhada pela South Bank. Introdução suave ao ritmo londrino.\n\nDia 2 — Pontes e mirantes\nTower Bridge, Torre de Londres (por fora ou visita opcional) e London Eye. Tarde livre em Covent Garden.\n\nDia 3 — Museus sem pressa\nBritish Museum ou Natural History Museum, com tempo para cafés e bookshops. Londres para quem ama aprender andando.\n\nDia 4 — Bairros com personalidade\nPasseio por Notting Hill, South Kensington ou Shoreditch (à escolha do grupo). Noite livre para teatro ou pub tradicional.\n\nDia 5 — Encerramento\nManhã livre para compras em Oxford Street ou um último brunch antes do traslado. O clássico londrino, bem contado.',5,4890.00,1,'2026-11-27 21:24:19','2026-11-27 21:40:28'),(9,9,'Paris Romance','Arte, cafés e luz dourada: cinco dias para viver Paris no ritmo certo — entre ícones e passeios sem pressa.','Dia 1 — Luzes da Torre Eiffel\nChegada, walk pelo Champ de Mars e primeira noite com a Torre Eiffel iluminada. Jantar em bistrô próximo.\n\nDia 2 — Arte no Louvre\nManhã no Louvre com foco nas obras essenciais. Tarde livre no Jardim das Tulherias e nas galerias do Marais.\n\nDia 3 — Montmartre e artistas\nSubida a Montmartre, Sacré-Cœur e praças de artistas. Tarde para cafés, crepes e vistas sobre a cidade.\n\nDia 4 — Sena e margens\nPasseio de barco pelo Sena e tempo livre em Saint-Germain ou Île de la Cité. Paris pelo lado mais poético.\n\nDia 5 — Au revoir\nManhã livre para boulangerie favorita e compras leves antes do traslado. Um roteiro romântico, cultural e memorável.',5,4990.00,1,'2026-11-27 21:24:19','2026-11-27 21:40:28'),(10,10,'Maldives Escape','Quatro dias de águas turquesa, silêncio e horizonte infinito — o escape tropical definitivo.','Dia 1 — Chegada ao paraíso\nTransfer para o atol e check-in. Tarde livre na praia privada e primeiro pôr do sol sobre o Índico.\n\nDia 2 — Azul em todas as direções\nSnorkel em recifes próximos, tempo livre em overwater ou praia, e jantar com frutos do mar.\n\nDia 3 — Ritmo slow\nDia dedicado ao descanso: spa, caiaque, leitura à beira-mar ou mais um mergulho leve. Sem agenda apertada.\n\nDia 4 — Última manhã no Índico\nCafé com vista para a lagoa e traslado de volta. Um roteiro premium para casais, lua de mel ou quem busca silêncio e beleza absoluta.',4,6790.00,1,'2026-11-27 21:24:19','2026-11-27 21:40:28');
/*!40000 ALTER TABLE `packages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchases`
--

DROP TABLE IF EXISTS `purchases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchases` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `package_id` int unsigned NOT NULL,
  `status` enum('confirmed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'confirmed',
  `purchased_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_purchases_user_id` (`user_id`),
  KEY `idx_purchases_package_id` (`package_id`),
  CONSTRAINT `fk_purchases_package` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_purchases_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchases`
--

LOCK TABLES `purchases` WRITE;
/*!40000 ALTER TABLE `purchases` DISABLE KEYS */;
INSERT INTO `purchases` VALUES (1,2,3,'confirmed','2026-11-27 21:24:39'),(2,2,2,'confirmed','2026-11-27 21:25:33'),(3,2,1,'confirmed','2026-11-10 01:54:59'),(4,2,4,'confirmed','2026-07-01 09:03:15'),(5,13,2,'confirmed','2026-10-31 09:41:54'),(6,13,5,'confirmed','2026-11-15 13:56:22'),(7,14,3,'confirmed','2026-07-18 05:00:15'),(8,14,6,'confirmed','2026-10-12 18:59:47'),(9,15,4,'confirmed','2026-08-26 16:49:23'),(10,15,7,'confirmed','2026-09-19 02:30:27'),(11,16,5,'confirmed','2026-10-07 13:11:33'),(12,16,8,'confirmed','2026-11-20 19:31:24'),(13,17,6,'confirmed','2026-09-13 18:33:48'),(14,17,9,'confirmed','2026-10-18 20:26:33'),(15,18,7,'confirmed','2026-10-08 20:23:38'),(16,18,10,'confirmed','2026-11-09 04:58:37'),(17,19,8,'confirmed','2026-06-17 01:13:29'),(18,19,1,'confirmed','2026-08-29 04:06:08'),(19,20,9,'confirmed','2026-11-23 21:37:20'),(20,20,2,'confirmed','2026-10-11 15:46:53'),(21,21,10,'confirmed','2026-11-14 08:46:45'),(22,21,3,'confirmed','2026-11-20 10:10:52'),(23,22,1,'confirmed','2026-07-26 21:36:20'),(24,22,4,'confirmed','2026-09-05 14:04:43'),(25,23,2,'confirmed','2026-10-12 20:36:33'),(26,23,5,'confirmed','2026-11-09 18:45:24'),(27,24,3,'confirmed','2026-11-21 05:36:10'),(28,24,6,'confirmed','2026-11-12 08:04:02'),(29,25,4,'confirmed','2026-08-18 04:34:02'),(30,25,7,'confirmed','2026-08-09 07:48:06'),(31,26,5,'confirmed','2026-09-18 19:18:55'),(32,26,8,'confirmed','2026-08-25 03:03:45'),(33,27,6,'confirmed','2026-06-23 22:35:36'),(34,27,9,'confirmed','2026-11-23 16:17:24'),(35,28,7,'confirmed','2026-11-11 15:18:43'),(36,28,10,'confirmed','2026-10-25 14:43:20'),(37,29,8,'confirmed','2026-11-17 17:17:20'),(38,29,1,'confirmed','2026-11-13 13:31:20'),(39,30,9,'confirmed','2026-06-25 04:27:07'),(40,30,2,'confirmed','2026-07-29 21:27:28'),(41,31,10,'confirmed','2026-09-26 13:22:27'),(42,31,3,'confirmed','2026-11-18 23:26:08');
/*!40000 ALTER TABLE `purchases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','customer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (2,'Marina Costa','marina.costa@destyntrips.com','$2y$10$l77F6Ojn2bbpFyOeh8/SG.mzEVKGTRYTKbm9EgLuW2y1ySmTsPJM2','customer','2026-06-27 18:20:58','2026-11-27 21:40:28'),(9,'Thiago Barbosa','thiago.barbosa@destyntrips.com','$2y$10$m9gCRdrV.tKCrcWZESpHGeIdFSo3eHXQujuc1NQxa82jSeps7EyeG','admin','2026-06-18 13:26:17','2026-11-27 21:40:28'),(10,'Arthur Inacio','arthur.inacio@destyntrips.com','$2y$10$m9gCRdrV.tKCrcWZESpHGeIdFSo3eHXQujuc1NQxa82jSeps7EyeG','admin','2026-04-29 23:12:08','2026-11-27 21:40:28'),(11,'Pedro Gomes','pedro.gomes@destyntrips.com','$2y$10$m9gCRdrV.tKCrcWZESpHGeIdFSo3eHXQujuc1NQxa82jSeps7EyeG','admin','2026-05-02 05:22:13','2026-11-27 21:40:28'),(13,'Lucas Almeida','lucas.almeida@email.com','$2y$10$l77F6Ojn2bbpFyOeh8/SG.mzEVKGTRYTKbm9EgLuW2y1ySmTsPJM2','customer','2026-08-31 15:10:06','2026-11-27 21:40:28'),(14,'Fernanda Ribeiro','fernanda.ribeiro@email.com','$2y$10$l77F6Ojn2bbpFyOeh8/SG.mzEVKGTRYTKbm9EgLuW2y1ySmTsPJM2','customer','2026-06-27 18:39:59','2026-11-27 21:40:28'),(15,'Bruno Oliveira','bruno.oliveira@email.com','$2y$10$l77F6Ojn2bbpFyOeh8/SG.mzEVKGTRYTKbm9EgLuW2y1ySmTsPJM2','customer','2026-08-24 00:23:25','2026-11-27 21:40:28'),(16,'Camila Santos','camila.santos@email.com','$2y$10$l77F6Ojn2bbpFyOeh8/SG.mzEVKGTRYTKbm9EgLuW2y1ySmTsPJM2','customer','2026-09-25 03:06:33','2026-11-27 21:40:28'),(17,'Rafael Mendes','rafael.mendes@email.com','$2y$10$l77F6Ojn2bbpFyOeh8/SG.mzEVKGTRYTKbm9EgLuW2y1ySmTsPJM2','customer','2026-08-03 17:53:20','2026-11-27 21:40:28'),(18,'Juliana Ferreira','juliana.ferreira@email.com','$2y$10$l77F6Ojn2bbpFyOeh8/SG.mzEVKGTRYTKbm9EgLuW2y1ySmTsPJM2','customer','2026-09-21 15:16:11','2026-11-27 21:40:28'),(19,'Gabriel Souza','gabriel.souza@email.com','$2y$10$l77F6Ojn2bbpFyOeh8/SG.mzEVKGTRYTKbm9EgLuW2y1ySmTsPJM2','customer','2026-06-12 05:14:38','2026-11-27 21:40:28'),(20,'Beatriz Lima','beatriz.lima@email.com','$2y$10$l77F6Ojn2bbpFyOeh8/SG.mzEVKGTRYTKbm9EgLuW2y1ySmTsPJM2','customer','2026-09-25 22:56:46','2026-11-27 21:40:28'),(21,'André Carvalho','andre.carvalho@email.com','$2y$10$l77F6Ojn2bbpFyOeh8/SG.mzEVKGTRYTKbm9EgLuW2y1ySmTsPJM2','customer','2026-11-11 04:24:19','2026-11-27 21:40:28'),(22,'Patrícia Nunes','patricia.nunes@email.com','$2y$10$l77F6Ojn2bbpFyOeh8/SG.mzEVKGTRYTKbm9EgLuW2y1ySmTsPJM2','customer','2026-06-28 19:17:32','2026-11-27 21:40:28'),(23,'Diego Martins','diego.martins@email.com','$2y$10$l77F6Ojn2bbpFyOeh8/SG.mzEVKGTRYTKbm9EgLuW2y1ySmTsPJM2','customer','2026-08-16 02:32:56','2026-11-27 21:40:28'),(24,'Larissa Rocha','larissa.rocha@email.com','$2y$10$l77F6Ojn2bbpFyOeh8/SG.mzEVKGTRYTKbm9EgLuW2y1ySmTsPJM2','customer','2026-10-05 01:11:39','2026-11-27 21:40:28'),(25,'Felipe Araujo','felipe.araujo@email.com','$2y$10$l77F6Ojn2bbpFyOeh8/SG.mzEVKGTRYTKbm9EgLuW2y1ySmTsPJM2','customer','2026-06-30 13:56:21','2026-11-27 21:40:28'),(26,'Amanda Vieira','amanda.vieira@email.com','$2y$10$l77F6Ojn2bbpFyOeh8/SG.mzEVKGTRYTKbm9EgLuW2y1ySmTsPJM2','customer','2026-08-24 12:06:17','2026-11-27 21:40:28'),(27,'Rodrigo Barbosa','rodrigo.barbosa@email.com','$2y$10$l77F6Ojn2bbpFyOeh8/SG.mzEVKGTRYTKbm9EgLuW2y1ySmTsPJM2','customer','2026-06-21 20:16:53','2026-11-27 21:40:28'),(28,'Isabela Monteiro','isabela.monteiro@email.com','$2y$10$l77F6Ojn2bbpFyOeh8/SG.mzEVKGTRYTKbm9EgLuW2y1ySmTsPJM2','customer','2026-10-10 03:36:47','2026-11-27 21:40:28'),(29,'Thiago Freitas','thiago.freitas@email.com','$2y$10$l77F6Ojn2bbpFyOeh8/SG.mzEVKGTRYTKbm9EgLuW2y1ySmTsPJM2','customer','2026-11-05 22:16:38','2026-11-27 21:40:28'),(30,'Natália Campos','natalia.campos@email.com','$2y$10$l77F6Ojn2bbpFyOeh8/SG.mzEVKGTRYTKbm9EgLuW2y1ySmTsPJM2','customer','2026-06-22 14:18:56','2026-11-27 21:40:28'),(31,'Henrique Duarte','henrique.duarte@email.com','$2y$10$l77F6Ojn2bbpFyOeh8/SG.mzEVKGTRYTKbm9EgLuW2y1ySmTsPJM2','customer','2026-09-23 04:49:52','2026-11-27 21:40:28');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'projeto_integrador'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-27 21:41:55
