INSERT INTO "Compte" ("email", "mdp", "nomCli", "preCli", "adresse", "estAdmin", "news") VALUES
('admin@example.com', 'hashedpassword1', 'Admin', 'User', ARRAY['123 Admin St', 'Admin City', '10001'], TRUE, TRUE),
('user1@example.com', 'hashedpassword2', 'Doe', 'John', ARRAY['456 User Lane', 'User City', '20002'], FALSE, FALSE),
('user2@example.com', 'hashedpassword3', 'Smith', 'Jane', ARRAY['789 User Blvd', 'User Town', '30003'], FALSE, TRUE);

INSERT INTO "Categorie" ("libCateg") VALUES
('Colliers'),
('Bracelets');

INSERT INTO "Materiau" ("libMateriau") VALUES
('Argent'),
('Or'),
('Cuir'),
('Bois');

INSERT INTO "Taille" ("libTaille") VALUES
('S'),
('M'),
('L'),
('XL');

INSERT INTO "Pendentif" ("libPendentif") VALUES
('Tortue'),
('Lion'),
('Dauphin'),
('Girafe');

INSERT INTO "Fil" ("libCouleur") VALUES
('Bleu'),
('Rouge'),
('Vert'),
('Noir'),
('Blanc'),
('Beige'),
('Marron'),
('Kaki'),
('Gris'),
('Violet'),
('Rose');

INSERT INTO "Pierre" ("libPierre", "descriptionPierre") VALUES
('Jaspe impérial', 'Cette pierre est symbole de force, de stabilité et de protection. Elle est souvent utilisée pour favoriser la confiance en soi, l''équilibre et la sécurité intérieure. Le jaspe impérial est aussi associé à la prospérité et au leadership.'),
('Howlite', 'La howlite est une pierre de calme et de relaxation. Elle est réputée pour apaiser les pensées agitées et aider à réduire l’anxiété, favorisant la patience et la tranquillité d''esprit.'),
('Labradorite', 'Cette pierre est considérée comme une pierre de protection et d''intuition. Elle renforce les capacités psychiques, aide à se reconnecter à son moi intérieur et protège des énergies négatives. Elle est aussi associée à la transformation et à la créativité.'),
('Magnésite', 'Cette pierre est une pierre de relaxation et de paix intérieure. Elle favorise l’harmonie, aide à libérer les blocages émotionnels et encourage l’amour de soi. La magnésite est également associée à la purification énergétique.'), 
('Quartz rose', 'Pierre de l''amour inconditionnel, le quartz rose symbolise la douceur, la compassion et la guérison émotionnelle. Il est souvent utilisé pour favoriser l''harmonie dans les relations et pour développer l''amour de soi.'),
('Œil de tigre', 'Symbole de protection et de force, l''œil de tigre est une pierre qui aide à renforcer la confiance en soi et à prendre des décisions avec discernement. Elle est également associée à l''équilibre entre le corps et l''esprit.'),
('Aventurine', 'Connue comme la pierre de la chance et de la prospérité, l’aventurine symbolise l’abondance, la croissance et le bien-être. Elle est souvent utilisée pour attirer des opportunités et améliorer la santé physique et mentale.'),
('Onyx noir', 'Pierre de protection, l''onyx noir est censé repousser les énergies négatives, apporter stabilité et renforcer la force intérieure. Elle aide aussi à surmonter les épreuves et à maintenir une grande concentration.'),
('Agathe jaune', 'L''agathe jaune symbolise la joie, l''optimisme et l''équilibre émotionnel. Elle est réputée pour apporter énergie et vitalité, tout en favorisant la concentration et la clarté mentale.'),
('Jade', 'Pierre de la chance et de la prospérité, le jade est souvent associé à la guérison, à l’harmonie et à l’équilibre. Il favorise l’épanouissement personnel et protège contre les énergies négatives.'),
('Améthyste', 'Symbolisant la sérénité, la sagesse et la paix intérieure, l''améthyste est une pierre de purification et de protection. Elle est utilisée pour favoriser l’intuition et pour équilibrer les émotions.'),
('Agate', 'L''agate est une pierre de stabilité, de protection et de guérison. Elle est utilisée pour harmoniser les énergies, apporter une sensation de calme et renforcer l’équilibre émotionnel. Elle aide également à développer la confiance en soi et la concentration.'),
('Perle', 'Les perles symbolisent la pureté, la sagesse et l’élégance. Elles sont souvent associées à l’intuition, à la féminité et à l’harmonie intérieure. '),
('Coquillages', 'Ils symbolisent la protection, l’abondance, la fertilité et la connexion à la nature. Ils sont associés à la chance, au renouveau, à la féminité et à la paix intérieure, tout en évoquant la beauté et la sagesse de la mer. ') ;

INSERT INTO "Produit" ("libProd", "descriptionProd", "prix", "estGravable", "tabPhoto", "tempsRea", "idCateg") VALUES
('Kelyan', '', 15.00, FALSE, ARRAY['CollierKelyan1.webp'], 2, 1),
('Yvan', 'Tous les éléments sont personnalisables : couleur des pierres, du métal, la taille, et le pendentif', 23.00, FALSE, ARRAY['CollierYvan1.webp'], 4, 1),
('Sandrine', 'Tous les éléments sont personnalisables : couleur des pierres, du métal, et la taille', 8.00, FALSE, ARRAY['BraceletSandrine1.webp'], 3, 2),
('Claudia', 'Tous les éléments sont personnalisables : couleur des pierres, du métal, et la taille', 8.00, FALSE, ARRAY['BraceletClaudia1.webp'], 3, 2),
('Anne', 'Tous les éléments sont personnalisables : couleur des pierres, du métal, et la taille', 8.00, FALSE, ARRAY['BraceletAnne1.webp'], 3, 2),
('Charlotte', 'Tous les éléments sont personnalisables : couleur des pierres, du métal, et la taille', 8.00, FALSE, ARRAY['BraceletCharlotte1.webp'], 3, 2),
('Sarah', 'Tous les éléments sont personnalisables : couleur des pierres, du métal, et la taille', 8.00, FALSE, ARRAY['BraceletSarah1.webp'], 3, 2),
('Maeva', 'Tous les éléments sont personnalisables : couleur des pierres, du métal, et la taille', 8.00, FALSE, ARRAY['BraceletMaeva1.webp'], 3, 2),
('Emma', 'Tous les éléments sont personnalisables : couleur des pierres, du métal, et la taille', 8.00, FALSE, ARRAY['BraceletEmma1.webp'], 3, 2),
('Romy', 'Cordons disponibles en : noir, blanc, beige, marron, kaki, gris, bleu, violet et rose', 10.00, FALSE, ARRAY['BraceletRomy1.webp'], 3, 2),
('Juanna', 'Tous les éléments sont modifiables : couleur du métal et la taille', 10.00, FALSE, ARRAY['BraceletRomy1.webp'], 3, 2);


INSERT INTO "TaiProd" ("idProd", "libTaille") VALUES
(1, 'M'),
(1, 'L'),
(1, 'XL'),
(2, 'L');

INSERT INTO "PenProd" ("idProd", "libPendentif") VALUES
(1, 'Girafe'),
(1, 'Dauphin'),
(1, 'Lion'),
(2, 'Girafe');

INSERT INTO "PieProd" ("idProd", "libPierre") VALUES
(1, 'Quartz rose'),
(2, 'Améthyste');

INSERT INTO "MatProd" ("idProd", "libMateriau") VALUES
(1, 'Argent'),
(2, 'Or'),
(3, 'Bois');

INSERT INTO "FilProd" ("idProd", "libCouleur") VALUES
(1, 'Noir'),
(1, 'Blanc'),
(1, 'Beige'),
(1, 'Marron'),
(1, 'Kaki'),
(1, 'Gris'),
(1, 'Bleu'),
(1, 'Violet'),
(1, 'Rose'),
(2, 'Noir'),
(3, 'Vert');

INSERT INTO "Panier" ("idProd", "idCli", "gravure", "variante", "qa") VALUES
(1, 2, 'Love', 'Taille M', 1),
(2, 3, 'Happy Birthday', 'Taille L', 2);

INSERT INTO "Commande" ("idCli", "comm", "estCadeau", "carte", "dateLivraison", "adresse", "etat") VALUES
(1, 'Urgent', TRUE, 'Joyeux anniversaire!', '2024-12-10', '123 Commande St, Paris, 75000', 'en cours'),
(2, 'Livraison standard', FALSE, NULL, '2024-12-15', '456 Livraison Rd, Lyon, 69000', 'en cours');

INSERT INTO "CommandeProduit" ("idProd", "idCommande", "gravure", "variante", "qa") VALUES
(1, 1, 'Love', 'Taille M', 1),
(2, 1, NULL, 'Taille S', 2);

INSERT INTO "CodePromo" ("code", "reduc", "type") VALUES
('PROMO10', 10.00, 'P'),
('BLACKFRIDAY', 25.00, 'P'),
('WELCOME', 15.00, 'E');

INSERT INTO "PromoProduit" ("code", "idProd") VALUES
('PROMO10', 1),
('BLACKFRIDAY', 2);

INSERT INTO "UtilisationCode" ("code", "idCommande") VALUES
('PROMO10', 1),
('BLACKFRIDAY', 2);


INSERT INTO "FAQ" ("contenu", "reponse", "idCli") VALUES
('Comment fonctionne la gravure ?', 'La gravure est incluse dans le prix pour certains produits.', 2),
('Quel est le délai de livraison ?', 'Le délai dépend du produit et de l''adresse de livraison.', 3);

INSERT INTO "Article" ("titreArticle", "contenu") VALUES
('Nouveautés de décembre', 'Découvrez nos nouveaux produits pour les fêtes.'),
('Comment choisir un cadeau personnalisé ?', 'Guide pour sélectionner le cadeau parfait.');

INSERT INTO "Avis" ("contenu", "note", "idCli") VALUES
('Produit magnifique, livraison rapide.', 5, 2),
('La gravure était parfaite, merci !', 4, 3);
