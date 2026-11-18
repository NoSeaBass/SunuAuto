INSERT INTO utilisateur (id, passe, role) VALUES
('searchmethere@gmail.com', 'jirafe123', 'client'),  
('kfaye0838@gmail.com', 'khady 123', 'client'),       
('20230BNZB', 'jirafe123', 'gerant'),                  
('20230C2RP', 'khady 123', 'gerant');                  

INSERT INTO `client` (`num_client`, `email`, `telephone`, `nom`, `prenom`, `sexe`, `profession`, `status`) VALUES
(1, 'searchmethere@gmail.com', 775944007, 'Diop', 'Papa Ibrahima', 'M', 'Tarnished', 'actif'),
(2, 'kfaye0838@gmail.com', 785828716, 'Faye', 'Khady', 'F', 'mangeuse', 'actif');            

INSERT INTO gerant (id, email, nom, prenom) VALUES
('20230BNZB', 'searchmethere@gmail.com', 'Diop', 'Papa Ibrahima'), 
('20230C2RP', 'kfaye0838@gmail.com', 'Faye', 'Khady');               

INSERT INTO voiture (code, marque, modele, description, tarif_reserv, qt_initial, qt_disponible) VALUES
('V1', 'Peugeot', '208', 'Compacte et économique', 12000, 5, 5),
('V2', 'Toyota', 'Corolla', 'Fiable et pratique', 10000, 10, 10),
('V3', 'Toyota', 'RAV4', 'C\'est une RAV4', 20000, 15, 15),
('V4', 'Dacia', 'Sandero', 'Économique et robuste', 10000, 6, 6),
('V5', 'Kia', 'Rio', 'Moderne et élégante', 18000, 6, 6),
('V6', 'Ford', 'Fiesta', 'Agile et dynamique', 16000, 9, 9),
('V7', 'Hyundai', 'i20', 'Confort et technologie', 17000, 3, 3),
('V8', 'Mazda', '2', 'Sportive et fun', 20000, 2, 2);

INSERT INTO `photo` (`id_photo`, `code`, `url`) VALUES
(1, 'V1', './media/voitures/peugeotExt.jpg'),
(2, 'V1', './media/voitures/peugeotInt.jpg'),
(3, 'V2', './media/voitures/toyotaExt.jpg'),
(4, 'V2', './media/voitures/toyotaInt.jpg'),
(5, 'V3', './media/voitures/2014-toyota-rav4.webp'),
(6, 'V3', './media/voitures/RAV4.webp'),
(7, 'V4', './media/voitures/Sandaro.webp'),
(8, 'V4', './media/voitures/Dacia.jpg'),
(9, 'V5', './media/voitures/Kia.jpg'),
(10, 'V5', './media/voitures/Rio.jpeg'),
(11, 'V6', './media/voitures/Ford.jpg'),
(12, 'V6', './media/voitures/Fiesta.jpg'),
(13, 'V7', './media/voitures/hyundai.webp'),
(14, 'V7', './media/voitures/i20.webp'),
(15, 'V8', './media/voitures/mazda.jpg'),
(16, 'V8', './media/voitures/2.webp');
