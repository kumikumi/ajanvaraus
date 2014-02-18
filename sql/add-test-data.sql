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
('tiina', 'lamp', 'Tiina Tomera');

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

insert into henkilokunta
select id from kayttajat where kayttajatunnus like 'tiina';

insert into johto
select id from kayttajat where kayttajatunnus like 'jorma';

insert into asiakas
(asiakas_id)
select id from kayttajat where kayttajatunnus like 'matti';

insert into hlokpalvelut
(hlo_id, palvelu_id)
select * from
(select id as hlo_id from kayttajat where kayttajatunnus like 'essi') t1,
(select palvelu_id from palvelu where nimi like 'Miesten hiustenleikkaus') t2;

insert into hlokpalvelut
(hlo_id, palvelu_id)
select * from
(select id as hlo_id from kayttajat where kayttajatunnus like 'tiina') t1,
(select palvelu_id from palvelu where nimi like 'Miesten hiustenleikkaus') t2;

insert into hlokpalvelut
(hlo_id, palvelu_id)
select * from
(select id as hlo_id from kayttajat where kayttajatunnus like 'tiina') t1,
(select palvelu_id from palvelu where nimi like 'Kaljunkiillotus') t2;

insert into tyovuoro
(hlo_id, paiva, aikaviipale)
select id, 'MA'::viikonpv as paiva, 1 as aikaviipale from kayttajat where kayttajatunnus like 'essi' UNION
select id, 'MA'::viikonpv as paiva, 2 as aikaviipale from kayttajat where kayttajatunnus like 'essi' UNION
select id, 'MA'::viikonpv as paiva, 3 as aikaviipale from kayttajat where kayttajatunnus like 'essi' UNION
select id, 'TI'::viikonpv as paiva, 4 as aikaviipale from kayttajat where kayttajatunnus like 'essi' UNION
select id, 'TI'::viikonpv as paiva, 5 as aikaviipale from kayttajat where kayttajatunnus like 'essi' UNION
select id, 'TI'::viikonpv as paiva, 7 as aikaviipale from kayttajat where kayttajatunnus like 'tiina' UNION
select id, 'TI'::viikonpv as paiva, 8 as aikaviipale from kayttajat where kayttajatunnus like 'tiina' UNION
select id, 'TI'::viikonpv as paiva, 9 as aikaviipale from kayttajat where kayttajatunnus like 'tiina' UNION
select id, 'TI'::viikonpv as paiva, 10 as aikaviipale from kayttajat where kayttajatunnus like 'tiina' UNION
select id, 'TI'::viikonpv as paiva, 11 as aikaviipale from kayttajat where kayttajatunnus like 'tiina' UNION
select id, 'TI'::viikonpv as paiva, 12 as aikaviipale from kayttajat where kayttajatunnus like 'tiina' UNION
select id, 'TI'::viikonpv as paiva, 13 as aikaviipale from kayttajat where kayttajatunnus like 'tiina' UNION
select id, 'TI'::viikonpv as paiva, 14 as aikaviipale from kayttajat where kayttajatunnus like 'tiina' UNION
select id, 'TI'::viikonpv as paiva, 15 as aikaviipale from kayttajat where kayttajatunnus like 'tiina' UNION
select id, 'KE'::viikonpv as paiva, 4 as aikaviipale from kayttajat where kayttajatunnus like 'tiina' UNION
select id, 'KE'::viikonpv as paiva, 5 as aikaviipale from kayttajat where kayttajatunnus like 'tiina';

insert into varaus
(pvm, aikaviipale, toimihlo, palvelu, asiakas)
select * from
(select '2014-02-17'::date as pvm, 2 as aikaviipale) t1,
(select id as toimihlo from kayttajat where kayttajatunnus like 'essi') t2,
(select palvelu_id as palvelu from palvelu where nimi like 'Miesten hiustenleikkaus') t3,
(select id as asiakas from kayttajat where kayttajatunnus like 'matti') t4;

