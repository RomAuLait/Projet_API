CREATE TABLE utilisateurs (
    id serial PRIMARY KEY,
    nom varchar(255),
    password varchar(255), 
    role varchar(255)
);

CREATE TABLE appartement (
    id serial PRIMARY KEY,
    superficie int,
    personnes int,
    adresse varchar(255),
    disponibilite boolean,
    prix int,
    proprietaireId int REFERENCES utilisateurs(id) 
);

CREATE TABLE reservation (
    id serial PRIMARY KEY,
    appartementId int REFERENCES appartement(id),
    dateDebut date,
    dateFin date,
    clientId int REFERENCES utilisateurs(id),
    prix int
);
