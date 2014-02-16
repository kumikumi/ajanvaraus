CREATE TABLE kayttajat (
	id serial PRIMARY KEY,
	kayttajatunnus varchar(16) not null,
	salasana varchar(16) not null,
	kokonimi varchar(30)
);

CREATE TABLE henkilokunta (
	hlo_id integer PRIMARY KEY references kayttajat
);

CREATE TABLE johto (
	joh_id integer PRIMARY KEY references kayttajat
);

CREATE TABLE asiakas (
	asiakas_id integer PRIMARY KEY references kayttajat,
	asiakastili decimal (5,2) default 0.00
);

CREATE TABLE palvelu (
	palvelu_id serial PRIMARY KEY,
	kesto integer,
	nimi varchar(50),
	kuvaus varchar(1000),
	hinta decimal (5,2)
);

CREATE TABLE hlokpalvelut(
	hlo_id integer references henkilokunta,
	palvelu_id integer references palvelu,
	primary key(hlo_id, palvelu_id)
);

CREATE TYPE viikonpv as ENUM ('MA', 'TI', 'KE', 'TO', 'PE', 'LA', 'SU');

CREATE TABLE tyovuoro(
	hlo_id integer references henkilokunta,
	paiva viikonpv,
	aikaviipale integer,
	primary key(hlo_id, paiva, aikaviipale)
);

CREATE TABLE varaus(
	pvm date,
	aikaviipale integer,
	toimihlo integer references henkilokunta,
	palvelu integer references palvelu,
	asiakas integer references asiakas,
	primary key (pvm, aikaviipale, toimihlo)
);
