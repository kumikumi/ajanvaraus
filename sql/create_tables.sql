CREATE TABLE kayttajat (
	id serial PRIMARY KEY,
	kayttajatunnus varchar(16) not null,
	salasana varchar(16) not null,
	kokonimi varchar(30)
);

CREATE TABLE henkilokunta (
	hlo_id integer PRIMARY KEY references kayttajat on delete cascade on update cascade
);

CREATE TABLE johto (
	joh_id integer PRIMARY KEY references kayttajat on delete cascade on update cascade
);

CREATE TABLE asiakas (
	asiakas_id integer PRIMARY KEY references kayttajat on delete cascade on update cascade,
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
	hlo_id integer references henkilokunta on delete cascade on update cascade,
	palvelu_id integer references palvelu on delete cascade on update cascade,
	primary key(hlo_id, palvelu_id)
);

CREATE TYPE viikonpv as ENUM ('MA', 'TI', 'KE', 'TO', 'PE', 'LA', 'SU');

CREATE TABLE tyovuoro(
	hlo_id integer references henkilokunta on delete cascade on update cascade,
	paiva viikonpv,
	aikaviipale integer,
	primary key(hlo_id, paiva, aikaviipale)
);

CREATE TABLE varaus(
	pvm date,
	aikaviipale integer,
	toimihlo integer references henkilokunta on delete cascade on update cascade,
	palvelu integer references palvelu on delete cascade on update cascade,
	asiakas integer references asiakas on delete cascade on update cascade,
	primary key (pvm, aikaviipale, toimihlo)
);
