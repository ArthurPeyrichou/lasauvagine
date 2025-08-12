-- seed.sql : sample data
INSERT INTO zones (zone_name) VALUES ('Zone A'), ('Zone B');

INSERT INTO users (nickname, email, password, role, status) VALUES
('Admin', 'admin@example.com', '$2y$10$changemechangemechangemechangemechg.', 'admin', 'activated'),
('God', 'god@example.com', '$2y$10$changemechangemechangemechangemechg.', 'god', 'activated');

-- clients sample
INSERT INTO clients (firstname, lastname, title, phone, email, address, city, zone_id, indications) VALUES
('Jean','Dupont','Mr','0600000001','j.dupont@example.com','1 rue A','Paris',1,''),
('Marie','Durand','Mme','0600000002','m.durand@example.com','2 rue B','Paris',1,''),
('Paul','Martin','Mr & Mme','0600000003','p.martin@example.com','3 rue C','Lyon',2,'');

-- periods: current month and next
INSERT INTO periods (month, year) VALUES (MONTH(CURDATE()), YEAR(CURDATE())), (MONTH(CURDATE())+1, YEAR(CURDATE()));
