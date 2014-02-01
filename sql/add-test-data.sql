insert into kayttajat 
(kayttajatunnus, salasana, kokonimi)
VALUES 
('matti', 'beer', 'Matti Meikäläinen');

insert into kayttajat 
(kayttajatunnus, salasana, kokonimi)
VALUES 
('essi', 'onion', 'Essi Esimerkki');

insert into kayttajat 
(kayttajatunnus, salasana, kokonimi)
VALUES 
('jorma', 'button', 'Jorma Johtaja');

insert into palvelu
(kesto, nimi, kuvaus, hinta)
VALUES
(1, 'Miesten hiustenleikkaus', 'Jooh elikkäs otetaan sakset ja sitten otetaan hiuksia lyhyemmäksi hihihihihi', 10.00);

insert into palvelu
(kesto, nimi, kuvaus, hinta)
VALUES
(4, 'Kaljunkiillotus', 'Kahden tunnin muhkea sessio', 10.00);

insert into henkilokunta
select id from kayttajat where kayttajatunnus like 'essi';

insert into johto
select id from kayttajat where kayttajatunnus like 'jorma';

insert into asiakas
(asiakas_id)
select id from kayttajat where kayttajatunnus like 'matti';
