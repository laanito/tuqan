--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = FALSE;
SET client_min_messages = WARNING;

--
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: postgres
--

COMMENT ON SCHEMA public IS 'Standard public schema';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = FALSE;

--
-- Name: idiomas; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE idiomas (
  id     SERIAL NOT NULL,
  nombre CHARACTER VARYING(64)
);


ALTER TABLE public.idiomas
  OWNER TO qnova;

--
-- Name: idiomas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('idiomas', 'id'), 2, TRUE);

--
-- Name: qnova_acl; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE qnova_acl (
  id         SERIAL NOT NULL,
  login_name CHARACTER VARYING(64),
  login_pass CHARACTER VARYING(32)
);


ALTER TABLE public.qnova_acl
  OWNER TO qnova;

--
-- Name: qnova_acl_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('qnova_acl', 'id'), 7, TRUE);

--
-- Name: qnova_bbdd; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE qnova_bbdd (
  id          SERIAL NOT NULL,
  nombre_bbdd CHARACTER VARYING(64),
  login_bbdd  CHARACTER VARYING(64),
  pass_bbdd   CHARACTER VARYING(64),
  empresa     CHARACTER VARYING(128)
);


ALTER TABLE public.qnova_bbdd
  OWNER TO qnova;

--
-- Name: qnova_bbdd_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('qnova_bbdd', 'id'), 7, TRUE);

--
-- Data for Name: idiomas; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO idiomas VALUES (1, 'castellano');
INSERT INTO idiomas VALUES (2, 'catalan');

--
-- Data for Name: qnova_acl; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO qnova_acl VALUES (1, 'ics', '669dbcecfbb91195b183fceab6920a7a');
INSERT INTO qnova_acl VALUES (2, 'islanda', 'b63c34932939e7cbb8811b0a5fda2bf8');
INSERT INTO qnova_acl VALUES (4, 'Clinico', 'b3406902a985bba6452bd1233048cc36');
INSERT INTO qnova_acl VALUES (5, 'El materno', 'eeafbf4d9b3957b139da7b7f2e7f2d4a');
INSERT INTO qnova_acl VALUES (6, 'icsnovasoft', '669dbcecfbb91195b183fceab6920a7a');
INSERT INTO qnova_acl VALUES (7, 'prueba', 'c893bad68927b457dbed39460e6afd62');

--
-- Data for Name: qnova_bbdd; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO qnova_bbdd VALUES (2, 'qnovaislanda', 'qnovamaster', 'N2RlNTA0Y2IxOY4l4BZH', 'ISLANDA');
INSERT INTO qnova_bbdd VALUES (4, 'qnova_clinico', 'qnovamaster', 'N2RlNTA0Y2IxOY4l4BZH', 'Clinico');
INSERT INTO qnova_bbdd VALUES (5, 'qnova_el_materno', 'qnovamaster', 'N2RlNTA0Y2IxOY4l4BZH', 'El materno');
INSERT INTO qnova_bbdd VALUES (1, 'qnovadesarrollo', 'qnovamaster', 'N2RlNTA0Y2IxOY4l4BZH', 'ICS');
INSERT INTO qnova_bbdd VALUES (6, 'qnova', 'qnovamaster', 'N2RlNTA0Y2IxOY4l4BZH', 'NOVASOFT');
INSERT INTO qnova_bbdd VALUES (7, 'qnovapepe', 'qnovamaster', 'N2RlNTA0Y2IxOY4l4BZH', 'Prueba');

--
-- Name: idiomas_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY idiomas
  ADD CONSTRAINT idiomas_pkey PRIMARY KEY (id);

--
-- Name: qnova_acl_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY qnova_acl
  ADD CONSTRAINT qnova_acl_pkey PRIMARY KEY (id);

--
-- Name: qnova_bbdd_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY qnova_bbdd
  ADD CONSTRAINT qnova_bbdd_pkey PRIMARY KEY (id);

--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;

--
-- PostgreSQL database dump complete
--

