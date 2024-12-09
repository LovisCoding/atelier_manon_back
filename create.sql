DROP TABLE IF EXISTS "Avis" CASCADE;
DROP TABLE IF EXISTS "Article" CASCADE;
DROP TABLE IF EXISTS "FAQ" CASCADE;
DROP TABLE IF EXISTS "UtilisationCode" CASCADE;
DROP TABLE IF EXISTS "PromoProduit" CASCADE;
DROP TABLE IF EXISTS "CodePromo" CASCADE;
DROP TABLE IF EXISTS "CommandeProduit" CASCADE;
DROP TABLE IF EXISTS "Commande" CASCADE;
DROP TABLE IF EXISTS "Panier" CASCADE;
DROP TABLE IF EXISTS "FilProd" CASCADE;
DROP TABLE IF EXISTS "MatProd" CASCADE;
DROP TABLE IF EXISTS "PieProd" CASCADE;
DROP TABLE IF EXISTS "Produit" CASCADE;
DROP TABLE IF EXISTS "Pierre" CASCADE;
DROP TABLE IF EXISTS "Fil" CASCADE;
DROP TABLE IF EXISTS "Materiau" CASCADE;
DROP TABLE IF EXISTS "Categorie" CASCADE;
DROP TABLE IF EXISTS "Compte" CASCADE;


CREATE TABLE "Compte" (
    "idCli" SERIAL PRIMARY KEY,
    email VARCHAR(50) NOT NULL UNIQUE,
    mdp VARCHAR(255) NOT NULL,
    "nomCli" VARCHAR(50) NOT NULL,
    "preCli" VARCHAR(50) NOT NULL,
    adresse VARCHAR[] NOT NULL,
    token VARCHAR(255),
    token_expiration TIMESTAMP,
    "estAdmin" BOOLEAN NOT NULL DEFAULT FALSE,
    news BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE "Categorie" (
    "idCateg" SERIAL PRIMARY KEY,
    "libCateg" VARCHAR(30) NOT NULL UNIQUE
);

CREATE TABLE "Materiau" (
    "libMateriau" VARCHAR(50) PRIMARY KEY
);

CREATE TABLE "Fil" (
    "libCouleur" VARCHAR(50) PRIMARY KEY
);

CREATE TABLE "Pierre" (
    "libPierre" VARCHAR(50) PRIMARY KEY,
    "descriptionPierre" TEXT
);

CREATE TABLE "Produit" (
    "idProd" SERIAL PRIMARY KEY,
    "libProd" VARCHAR(50) NOT NULL UNIQUE,
    "descriptionProd" TEXT,
    prix NUMERIC(12,2) NOT NULL CHECK (prix > 0),
    "estGravable" BOOLEAN NOT NULL DEFAULT FALSE,
    "tabPhoto" VARCHAR[] NOT NULL,
    "tempsRea" INT NOT NULL CHECK ("tempsRea" > 0),
    "idCateg" INT NOT NULL REFERENCES "Categorie"("idCateg") ON DELETE CASCADE
);

CREATE TABLE "PieProd" (
    "idProd" INT NOT NULL REFERENCES "Produit"("idProd") ON DELETE CASCADE,
    "libPierre" VARCHAR(50) NOT NULL REFERENCES "Pierre"("libPierre") ON DELETE CASCADE,
    PRIMARY KEY ("idProd", "libPierre")
);

CREATE TABLE "MatProd" (
    "idProd" INT NOT NULL REFERENCES "Produit"("idProd") ON DELETE CASCADE,
    "libMateriau" VARCHAR(50) NOT NULL REFERENCES "Materiau"("libMateriau") ON DELETE CASCADE,
    PRIMARY KEY ("idProd", "libMateriau")
);

CREATE TABLE "FilProd" (
    "idProd" INT NOT NULL REFERENCES "Produit"("idProd") ON DELETE CASCADE,
    "libCouleur" VARCHAR(50) NOT NULL REFERENCES "Fil"("libCouleur") ON DELETE CASCADE,
    PRIMARY KEY ("idProd", "libCouleur")
);


CREATE TABLE "Panier" (
    "idProd" INT NOT NULL REFERENCES "Produit"("idProd") ON DELETE CASCADE,
    "idCli" INT NOT NULL REFERENCES "Compte"("idCli") ON DELETE CASCADE,
    gravure VARCHAR(100),
    variante VARCHAR(255),
    qa INT NOT NULL CHECK (qa > 0),
    PRIMARY KEY ("idProd", "idCli", gravure, variante)
);

CREATE TABLE "Commande" (
    "idCommande" SERIAL PRIMARY KEY,
    "idCli" INT NOT NULL REFERENCES "Compte"("idCli") ON DELETE CASCADE,
    "dateCommande" DATE NOT NULL DEFAULT CURRENT_DATE,
    comm VARCHAR(150),
    "estCadeau" BOOLEAN NOT NULL DEFAULT FALSE,
    carte VARCHAR(150),
    "dateLivraison" DATE NOT NULL CHECK ("dateLivraison" > CURRENT_DATE),
    adresse VARCHAR(255) NOT NULL,
    etat VARCHAR(20) NOT NULL DEFAULT 'en cours'
);

CREATE TABLE "CommandeProduit" (
    "idProd" INT NOT NULL REFERENCES "Produit"("idProd") ON DELETE CASCADE,
    "idCommande" INT NOT NULL REFERENCES "Commande"("idCommande") ON DELETE CASCADE,
    gravure VARCHAR(100),
    variante VARCHAR(255),
    qa INT NOT NULL CHECK (qa > 0),
    PRIMARY KEY ("idProd", "idCommande")
);

CREATE TABLE "CodePromo" (
    code VARCHAR(20) PRIMARY KEY,
    reduc DECIMAL(5,2) NOT NULL CHECK (reduc > 0 AND reduc <= 100),
    type CHAR NOT NULL CHECK (type IN ('P', 'E'))
);

CREATE TABLE "PromoProduit" (
    code VARCHAR(20) NOT NULL REFERENCES "CodePromo"(code) ON DELETE CASCADE,
    "idProd" INT NOT NULL REFERENCES "Produit"("idProd") ON DELETE CASCADE,
    PRIMARY KEY (code, "idProd")
);


CREATE TABLE "UtilisationCode" (
    code VARCHAR(20) NOT NULL REFERENCES "CodePromo"(code) ON DELETE CASCADE,
    "idCommande" INT NOT NULL REFERENCES "Commande"("idCommande") ON DELETE CASCADE,
    PRIMARY KEY (code, "idCommande")
);

CREATE TABLE "FAQ" (
    "idQuestion" SERIAL PRIMARY KEY,
    contenu TEXT NOT NULL,
    "dateQuestion" DATE NOT NULL DEFAULT CURRENT_DATE,
    reponse TEXT,
    "idCli" INT NOT NULL REFERENCES "Compte"("idCli") ON DELETE CASCADE
);

CREATE TABLE "Article" (
    "idArticle" SERIAL PRIMARY KEY,
    "titreArticle" VARCHAR(80) NOT NULL,
    contenu TEXT NOT NULL,
    "dateArticle" DATE NOT NULL DEFAULT CURRENT_DATE
);

CREATE TABLE "Avis" (
    "idAvis" SERIAL PRIMARY KEY,
    contenu TEXT NOT NULL,
    "dateAvis" DATE NOT NULL DEFAULT CURRENT_DATE,
    note INT NOT NULL CHECK (note BETWEEN 1 AND 5),
    "idCli" INT NOT NULL REFERENCES "Compte"("idCli") ON DELETE CASCADE
);
