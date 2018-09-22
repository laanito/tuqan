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
-- Name: acciones_mejora; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE acciones_mejora (
  id                   SERIAL NOT NULL,
  tipo                 INTEGER,
  cliente              INTEGER,
  fecha                DATE,
  usuario_detectado    INTEGER,
  descripcion          CHARACTER VARYING(1024),
  analisis             CHARACTER VARYING(1024),
  requiere_tratamiento BOOLEAN,
  tratamiento          CHARACTER VARYING(1024),
  accion_preventiva    CHARACTER VARYING(1024),
  fecha_implantacion   DATE,
  usuario_verifica     INTEGER,
  fecha_verifica       DATE,
  observaciones        CHARACTER VARYING(1024),
  coste                NUMERIC(10, 2),
  cerrada              BOOLEAN,
  usuario_cerrado      INTEGER,
  fecha_cierre         DATE,
  usuario_implantacion INTEGER,
  plazo                DATE,
  auditoria            INTEGER,
  area                 CHARACTER VARYING(128)
);


ALTER TABLE public.acciones_mejora
  OWNER TO qnova;

--
-- Name: acciones_mejora_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('acciones_mejora', 'id'), 4, TRUE);


SET default_with_oids = TRUE;

--
-- Name: alumnos; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE alumnos (
  id         SERIAL NOT NULL,
  usuario    INTEGER,
  curso      INTEGER,
  inscrito   BOOLEAN,
  verificado BOOLEAN
);


ALTER TABLE public.alumnos
  OWNER TO qnova;

--
-- Name: alumnos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('alumnos', 'id'), 6, TRUE);


SET default_with_oids = FALSE;

--
-- Name: areas; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE areas (
  id     SERIAL NOT NULL,
  nombre CHARACTER VARYING(64),
  activo BOOLEAN
);


ALTER TABLE public.areas
  OWNER TO qnova;

--
-- Name: areas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('areas', 'id'), 1, TRUE);


SET default_with_oids = TRUE;

--
-- Name: aspectos; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE aspectos (
  id            SERIAL NOT NULL,
  nombre        CHARACTER VARYING(256),
  magnitud      SMALLINT,
  gravedad      SMALLINT,
  frecuencia    SMALLINT,
  tipo_aspecto  INTEGER,
  activo        BOOLEAN,
  impacto       INTEGER,
  probabilidad  SMALLINT,
  severidad     SMALLINT,
  area          CHARACTER VARYING(128),
  observaciones TEXT
);


ALTER TABLE public.aspectos
  OWNER TO qnova;

--
-- Name: aspectos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('aspectos', 'id'), 18, TRUE);

--
-- Name: auditores; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE auditores (
  id              SERIAL NOT NULL,
  auditoria       INTEGER,
  usuario_interno INTEGER,
  nombre          CHARACTER VARYING(64),
  tipo            CHARACTER(4),
  activo          BOOLEAN,
  documento       INTEGER
);


ALTER TABLE public.auditores
  OWNER TO qnova;

--
-- Name: auditores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('auditores', 'id'), 13, TRUE);


SET default_with_oids = FALSE;

--
-- Name: auditorias; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE auditorias (
  id                      SERIAL NOT NULL,
  programa                INTEGER,
  estado                  INTEGER,
  descripcion             CHARACTER VARYING(255),
  observaciones           TEXT,
  activo                  BOOLEAN,
  requisitos              TEXT,
  alcance                 TEXT,
  interna                 BOOLEAN,
  fecha_realiza           DATE,
  lugar_informe           CHARACTER VARYING(255),
  fecha_informe           DATE,
  recomendaciones_informe TEXT,
  conclusiones_informe    TEXT,
  fecha                   DATE,
  areas                   TEXT,
  nombre                  CHARACTER VARYING(32),
  responsable_de_calidad  CHARACTER VARYING(32),
  idioma_auditoria        CHARACTER VARYING(32)
);


ALTER TABLE public.auditorias
  OWNER TO qnova;

--
-- Name: auditorias_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('auditorias', 'id'), 17, TRUE);


SET default_with_oids = TRUE;

--
-- Name: botones; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE botones (
  id           SERIAL NOT NULL,
  menu         INTEGER,
  texto        CHARACTER VARYING(32),
  accion       CHARACTER VARYING(128),
  permisos     BOOLEAN [],
  tipo_botones INTEGER
);


ALTER TABLE public.botones
  OWNER TO qnova;

--
-- Name: botones_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('botones', 'id'), 316, TRUE);


SET default_with_oids = FALSE;

--
-- Name: botones_idiomas; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE botones_idiomas (
  id        SERIAL NOT NULL,
  valor     CHARACTER VARYING(256),
  boton     INTEGER,
  idioma_id INTEGER
);


ALTER TABLE public.botones_idiomas
  OWNER TO qnova;

--
-- Name: botones_idiomas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('botones_idiomas', 'id'), 590, TRUE);

--
-- Name: clientes; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE clientes (
  id       SERIAL NOT NULL,
  nombre   CHARACTER VARYING(255),
  telefono CHARACTER VARYING(32),
  contacto CHARACTER VARYING(255),
  activo   BOOLEAN
);


ALTER TABLE public.clientes
  OWNER TO qnova;

--
-- Name: clientes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('clientes', 'id'), 2, TRUE);


SET default_with_oids = TRUE;

--
-- Name: contactos_proveedores; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE contactos_proveedores (
  id        SERIAL NOT NULL,
  proveedor INTEGER,
  nombre    CHARACTER VARYING(64),
  telefono1 CHARACTER VARYING(16),
  telefono2 CHARACTER VARYING(16),
  fax       CHARACTER VARYING(16),
  movil     CHARACTER VARYING(16),
  activo    BOOLEAN
);


ALTER TABLE public.contactos_proveedores
  OWNER TO qnova;

--
-- Name: contactos_proveedores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('contactos_proveedores', 'id'), 5, TRUE);


SET default_with_oids = FALSE;

--
-- Name: contenido_adjunto; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE contenido_adjunto (
  id           SERIAL NOT NULL,
  size         BIGINT,
  archivo_oid  OID,
  tipo_fichero INTEGER
);


ALTER TABLE public.contenido_adjunto
  OWNER TO qnova;

--
-- Name: contenido_adjunto_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('contenido_adjunto', 'id'), 16, TRUE);

--
-- Name: contenido_binario; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE contenido_binario (
  id           INTEGER NOT NULL,
  tipo_fichero INTEGER,
  size         BIGINT,
  contenido    BYTEA,
  archivo_oid  OID
);


ALTER TABLE public.contenido_binario
  OWNER TO qnova;

--
-- Name: contenido_procesos; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE contenido_procesos (
  proveedor              TEXT,
  entradas               TEXT,
  propietario            TEXT,
  indicadores            INTEGER [],
  salidas                TEXT,
  cliente                TEXT,
  doc_asociada           TEXT,
  registros              TEXT,
  indicaciones           TEXT,
  anejos                 INTEGER [],
  flujograma             INTEGER,
  instalaciones_ambiente TEXT,
  documento              INTEGER,
  proceso                INTEGER,
  id                     SERIAL NOT NULL
);


ALTER TABLE public.contenido_procesos
  OWNER TO qnova;

--
-- Name: contenido_procesos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('contenido_procesos', 'id'), 7, TRUE);

--
-- Name: contenido_texto; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE contenido_texto (
  id        INTEGER NOT NULL,
  contenido TEXT
);


ALTER TABLE public.contenido_texto
  OWNER TO qnova;

--
-- Name: criterios_homologacion; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE criterios_homologacion (
  id     SERIAL NOT NULL,
  nombre CHARACTER VARYING(255),
  valor  INTEGER,
  activo BOOLEAN
);


ALTER TABLE public.criterios_homologacion
  OWNER TO qnova;

--
-- Name: criterios_homologacion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('criterios_homologacion', 'id'), 1, TRUE);


SET default_with_oids = TRUE;

--
-- Name: cursos; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE cursos (
  id                    SERIAL NOT NULL,
  tipo                  INTEGER,
  objetivos             TEXT,
  contenido             TEXT,
  num_horas             INTEGER,
  material_necesario    TEXT,
  material_suministrado TEXT,
  activo                BOOLEAN,
  plan                  INTEGER,
  fecha_prevista        DATE,
  lugar                 CHARACTER VARYING(64),
  fecha_realizacion     DATE,
  estado                INTEGER,
  nombre                CHARACTER VARYING(128),
  responsable           INTEGER,
  observaciones         TEXT,
  calidad               BOOLEAN,
  medioambiente         BOOLEAN,
  hoja_firmas           INTEGER
);


ALTER TABLE public.cursos
  OWNER TO qnova;

--
-- Name: cursos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('cursos', 'id'), 4, TRUE);


SET default_with_oids = FALSE;

--
-- Name: division_ayuda; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE division_ayuda (
  id     SERIAL NOT NULL,
  idioma INTEGER,
  texto  TEXT,
  boton  INTEGER,
  menu   INTEGER
);


ALTER TABLE public.division_ayuda
  OWNER TO qnova;

--
-- Name: division_ayuda_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('division_ayuda', 'id'), 524, TRUE);

--
-- Name: documentos; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE documentos (
  id               SERIAL NOT NULL,
  nombre           CHARACTER VARYING(512),
  codigo           CHARACTER VARYING(50),
  estado           INTEGER,
  revisado_por     INTEGER,
  aprobado_por     INTEGER,
  revision         CHARACTER VARYING(16),
  activo           BOOLEAN,
  tipo_documento   INTEGER,
  area             INTEGER,
  calidad          BOOLEAN,
  medioambiente    BOOLEAN,
  perfil_ver       BOOLEAN [],
  perfil_nueva     BOOLEAN [],
  perfil_modificar BOOLEAN [],
  perfil_revisar   BOOLEAN [],
  perfil_aprobar   BOOLEAN [],
  perfil_historico BOOLEAN [],
  perfil_tareas    BOOLEAN [],
  fecha_revision   DATE,
  fecha_aprobacion DATE
);


ALTER TABLE public.documentos
  OWNER TO qnova;

--
-- Name: documentos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('documentos', 'id'), 181, TRUE);

--
-- Name: equipos; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE equipos (
  id                 SERIAL                 NOT NULL,
  numero             CHARACTER VARYING(10)  NOT NULL,
  descripcion        CHARACTER VARYING(255) NOT NULL,
  numero_serie       CHARACTER VARYING(20)  NOT NULL,
  modelo             CHARACTER VARYING(255) NOT NULL,
  fabricante         CHARACTER VARYING(255) NOT NULL,
  ubicacion          CHARACTER VARYING(255) NOT NULL,
  fuera_uso          BOOLEAN,
  causa              TEXT,
  fecha_fuera        DATE,
  ver_interna        BOOLEAN                NOT NULL,
  mantenimiento_cada SMALLINT               NOT NULL,
  dias               BOOLEAN                NOT NULL,
  activo             BOOLEAN
);


ALTER TABLE public.equipos
  OWNER TO qnova;

--
-- Name: equipos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('equipos', 'id'), 4, TRUE);

--
-- Name: estados_documento; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE estados_documento (
  id     SERIAL NOT NULL,
  nombre CHARACTER VARYING(64)
);


ALTER TABLE public.estados_documento
  OWNER TO qnova;

--
-- Name: estados_documento_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('estados_documento', 'id'), 1, FALSE);


SET default_with_oids = TRUE;

--
-- Name: ficha_personal; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE ficha_personal (
  id       SERIAL NOT NULL,
  nombre   CHARACTER VARYING(64),
  fecha    DATE,
  revision CHARACTER VARYING(16),
  codigo   CHARACTER VARYING(16),
  activo   BOOLEAN
);


ALTER TABLE public.ficha_personal
  OWNER TO qnova;

--
-- Name: ficha_personal_cambio_departamento; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE ficha_personal_cambio_departamento (
  id                        SERIAL NOT NULL,
  fecha_cambio_departamento DATE,
  ficha                     INTEGER
);


ALTER TABLE public.ficha_personal_cambio_departamento
  OWNER TO qnova;

--
-- Name: ficha_personal_cambio_departamento_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('ficha_personal_cambio_departamento', 'id'), 1, TRUE);

--
-- Name: ficha_personal_cambio_perfil; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE ficha_personal_cambio_perfil (
  id                  SERIAL NOT NULL,
  fecha_cambio_perfil DATE,
  ficha               INTEGER
);


ALTER TABLE public.ficha_personal_cambio_perfil
  OWNER TO qnova;

--
-- Name: ficha_personal_cambio_perfil_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('ficha_personal_cambio_perfil', 'id'), 2, TRUE);

--
-- Name: ficha_personal_datos_personales; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE ficha_personal_datos_personales (
  nombre               CHARACTER VARYING(32),
  apellidos            CHARACTER VARYING(64),
  fecha_nac            DATE,
  localidad            CHARACTER VARYING(32),
  provincia            CHARACTER VARYING(32),
  vehiculo_propio      BOOLEAN,
  observaciones        TEXT,
  id                   INTEGER,
  direccion            CHARACTER VARYING(128),
  telefono             CHARACTER VARYING(16),
  poblacion            CHARACTER VARYING(32),
  provincia_residencia CHARACTER VARYING(32)
);


ALTER TABLE public.ficha_personal_datos_personales
  OWNER TO qnova;

--
-- Name: ficha_personal_experiencia_laboral; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE ficha_personal_experiencia_laboral (
  id           SERIAL NOT NULL,
  ficha        INTEGER,
  empresa      CHARACTER VARYING(64),
  puesto       CHARACTER VARYING(64),
  fecha_inicio DATE,
  fecha_fin    DATE
);


ALTER TABLE public.ficha_personal_experiencia_laboral
  OWNER TO qnova;

--
-- Name: ficha_personal_experiencia_laboral_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('ficha_personal_experiencia_laboral', 'id'), 5, TRUE);

--
-- Name: ficha_personal_formacion_academica; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE ficha_personal_formacion_academica (
  titulos_oficiales CHARACTER VARYING(255),
  fecha_fin         CHARACTER VARYING(255),
  centro            CHARACTER VARYING(255),
  id                INTEGER
);


ALTER TABLE public.ficha_personal_formacion_academica
  OWNER TO qnova;

--
-- Name: ficha_personal_formacion_tecnica; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE ficha_personal_formacion_tecnica (
  id      SERIAL NOT NULL,
  ficha   INTEGER,
  lugar   CHARACTER VARYING(64),
  desde   DATE,
  hasta   DATE,
  periodo CHARACTER VARYING(32),
  nombre  CHARACTER VARYING(64)
);


ALTER TABLE public.ficha_personal_formacion_tecnica
  OWNER TO qnova;

--
-- Name: ficha_personal_formacion_tecnica_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('ficha_personal_formacion_tecnica', 'id'), 8, TRUE);

--
-- Name: ficha_personal_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('ficha_personal', 'id'), 5, TRUE);

--
-- Name: ficha_personal_idiomas; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE ficha_personal_idiomas (
  nivel_ingles  CHARACTER VARYING(16),
  nivel_frances CHARACTER VARYING(16),
  otros         CHARACTER VARYING(16),
  nivel_otros   CHARACTER VARYING(16),
  id            INTEGER
);


ALTER TABLE public.ficha_personal_idiomas
  OWNER TO qnova;

--
-- Name: ficha_personal_incorporacion; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE ficha_personal_incorporacion (
  empresa             CHARACTER VARYING(64),
  fecha_incorporacion TIMESTAMP WITHOUT TIME ZONE,
  perfil              CHARACTER VARYING(64),
  departamento        CHARACTER VARYING(64),
  id                  INTEGER
);


ALTER TABLE public.ficha_personal_incorporacion
  OWNER TO qnova;

--
-- Name: ficha_personal_otros_cursos; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE ficha_personal_otros_cursos (
  id      SERIAL NOT NULL,
  ficha   INTEGER,
  lugar   CHARACTER VARYING(64),
  inicio  DATE,
  fin     DATE,
  periodo CHARACTER VARYING(32),
  nombre  CHARACTER VARYING(128)
);


ALTER TABLE public.ficha_personal_otros_cursos
  OWNER TO qnova;

--
-- Name: ficha_personal_otros_cursos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('ficha_personal_otros_cursos', 'id'), 1, FALSE);

--
-- Name: ficha_personal_preformacion; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE ficha_personal_preformacion (
  curso1 CHARACTER VARYING(64),
  curso2 CHARACTER VARYING(64),
  curso3 CHARACTER VARYING(64),
  id     INTEGER
);


ALTER TABLE public.ficha_personal_preformacion
  OWNER TO qnova;

--
-- Name: flujogramas; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE flujogramas (
  tipo_fichero INTEGER,
  proceso      INTEGER,
  id           SERIAL NOT NULL,
  size         BIGINT,
  contenido    BYTEA,
  archivo_oid  OID
);


ALTER TABLE public.flujogramas
  OWNER TO qnova;

--
-- Name: flujogramas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('flujogramas', 'id'), 1, TRUE);


SET default_with_oids = FALSE;

--
-- Name: formula_aspectos; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE formula_aspectos (
  formula CHARACTER VARYING(128),
  id      SERIAL NOT NULL
);


ALTER TABLE public.formula_aspectos
  OWNER TO qnova;

--
-- Name: formula_aspectos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('formula_aspectos', 'id'), 2, TRUE);


SET default_with_oids = TRUE;

--
-- Name: historico_cuestionarios; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE historico_cuestionarios (
  id                    SERIAL NOT NULL,
  fecha                 DATE,
  usuario               INTEGER,
  legislacion_aplicable INTEGER,
  preguntas             TEXT [],
  respuestas            BOOLEAN [],
  cumple                BOOLEAN
);


ALTER TABLE public.historico_cuestionarios
  OWNER TO qnova;

--
-- Name: historico_cuestionarios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('historico_cuestionarios', 'id'), 5, TRUE);

--
-- Name: historico_productos; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE historico_productos (
  id                      SERIAL NOT NULL,
  fecha                   DATE,
  usuario                 INTEGER,
  producto                INTEGER,
  valoracion_obtenida     INTEGER,
  valoracion_homologacion INTEGER
);


ALTER TABLE public.historico_productos
  OWNER TO qnova;

--
-- Name: historico_productos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('historico_productos', 'id'), 4, TRUE);


SET default_with_oids = FALSE;

--
-- Name: horario_auditoria; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE horario_auditoria (
  id         SERIAL NOT NULL,
  horainicio TIMESTAMP WITHOUT TIME ZONE,
  horafin    TIMESTAMP WITHOUT TIME ZONE,
  requisito  CHARACTER VARYING(32),
  auditor    CHARACTER VARYING(32),
  area       INTEGER
);


ALTER TABLE public.horario_auditoria
  OWNER TO qnova;

--
-- Name: horario_auditoria_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('horario_auditoria', 'id'), 1, TRUE);

--
-- Name: hospitales; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE hospitales (
  id         SERIAL NOT NULL,
  nombre     CHARACTER VARYING(128),
  activo     BOOLEAN,
  "password" CHARACTER VARYING(32)
);


ALTER TABLE public.hospitales
  OWNER TO qnova;

--
-- Name: hospitales_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('hospitales', 'id'), 3, TRUE);

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
-- Name: incidencias; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE incidencias (
  id            SERIAL                NOT NULL,
  fecha         DATE,
  no_pedido     CHARACTER VARYING(32),
  proveedor     INTEGER,
  comentario    CHARACTER VARYING(255),
  nombre        CHARACTER VARYING(64),
  accion_mejora INTEGER,
  codigo        CHARACTER VARYING(32) NOT NULL,
  activo        BOOLEAN DEFAULT TRUE
);


ALTER TABLE public.incidencias
  OWNER TO qnova;

--
-- Name: incidencias_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('incidencias', 'id'), 3, TRUE);

--
-- Name: indicadores; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE indicadores (
  id                      SERIAL NOT NULL,
  definicion              CHARACTER VARYING(255),
  valor_inicial           INTEGER,
  tecnica                 CHARACTER VARYING(64),
  variables_control       CHARACTER VARYING(128),
  activo                  BOOLEAN,
  frecuencia_seg          INTEGER,
  frecuencia_ana          INTEGER,
  genera_objetivo         BOOLEAN,
  nombre                  CHARACTER VARYING(128),
  responsable_analisis    CHARACTER VARYING(128),
  responsable_seguimiento CHARACTER VARYING(128),
  valor_tolerable         DOUBLE PRECISION,
  valor_tolerable2        INTEGER,
  valor_objetivo          INTEGER
);


ALTER TABLE public.indicadores
  OWNER TO qnova;

--
-- Name: indicadores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('indicadores', 'id'), 4, TRUE);


SET default_with_oids = TRUE;

--
-- Name: legislacion_aplicable; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE legislacion_aplicable (
  id          SERIAL NOT NULL,
  id_ley      INTEGER,
  id_ficha    INTEGER,
  nombre      CHARACTER VARYING(512),
  tipo_area   INTEGER,
  tipo_ambito INTEGER,
  verifica    BOOLEAN,
  activo      BOOLEAN
);


ALTER TABLE public.legislacion_aplicable
  OWNER TO qnova;

--
-- Name: legislacion_aplicable_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('legislacion_aplicable', 'id'), 9, TRUE);


SET default_with_oids = FALSE;

--
-- Name: mantenimientos; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE mantenimientos (
  id             SERIAL NOT NULL,
  equipo         INTEGER,
  tipo           CHARACTER VARYING(16),
  fecha_prevista DATE,
  fecha_realiza  DATE   NOT NULL,
  comentarios    TEXT   NOT NULL,
  motivos        TEXT   NOT NULL
);


ALTER TABLE public.mantenimientos
  OWNER TO qnova;

--
-- Name: mantenimientos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('mantenimientos', 'id'), 10, TRUE);

--
-- Name: mensajes; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE mensajes (
  id           SERIAL NOT NULL,
  destinatario INTEGER,
  titulo       CHARACTER VARYING(512),
  contenido    CHARACTER VARYING(4096),
  fecha        TIMESTAMP WITHOUT TIME ZONE DEFAULT now(),
  activo       BOOLEAN,
  origen       INTEGER
);


ALTER TABLE public.mensajes
  OWNER TO qnova;

--
-- Name: mensajes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('mensajes', 'id'), 158, TRUE);

--
-- Name: menu_idiomas_nuevo; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE menu_idiomas_nuevo (
  id        SERIAL NOT NULL,
  valor     CHARACTER VARYING(256),
  menu      INTEGER,
  idioma_id INTEGER
);


ALTER TABLE public.menu_idiomas_nuevo
  OWNER TO qnova;

--
-- Name: menu_idiomas_nuevo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('menu_idiomas_nuevo', 'id'), 216, TRUE);

--
-- Name: menu_nuevo; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE menu_nuevo (
  id       SERIAL NOT NULL,
  accion   CHARACTER VARYING(64),
  permisos BOOLEAN [],
  padre    INTEGER,
  orden    INTEGER
);


ALTER TABLE public.menu_nuevo
  OWNER TO qnova;

--
-- Name: menu_nuevo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('menu_nuevo', 'id'), 120, TRUE);

--
-- Name: metas_indicadores; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE metas_indicadores (
  id                SERIAL NOT NULL,
  objetivo_id       INTEGER,
  numero_meta       INTEGER,
  plan_accion       TEXT,
  fecha_consecucion DATE,
  responsable       CHARACTER VARYING(256),
  activo            BOOLEAN DEFAULT TRUE,
  recursos          TEXT
);


ALTER TABLE public.metas_indicadores
  OWNER TO qnova;

--
-- Name: metas_indicadores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('metas_indicadores', 'id'), 7, TRUE);

--
-- Name: metas_objetivos; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE metas_objetivos (
  id                SERIAL NOT NULL,
  numero_meta       INTEGER,
  plan_accion       TEXT,
  fecha_consecucion DATE,
  responsable       CHARACTER VARYING(256),
  activo            BOOLEAN DEFAULT TRUE,
  recursos          TEXT,
  objetivo_id       INTEGER
);


ALTER TABLE public.metas_objetivos
  OWNER TO qnova;

--
-- Name: metas_objetivos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('metas_objetivos', 'id'), 10, TRUE);

--
-- Name: normativa; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE normativa (
  id        SERIAL NOT NULL,
  codigo    CHARACTER VARYING(50),
  contenido BYTEA
);


ALTER TABLE public.normativa
  OWNER TO qnova;

--
-- Name: normativa_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('normativa', 'id'), 1, FALSE);


SET default_with_oids = TRUE;

--
-- Name: objetivos; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE objetivos (
  id               SERIAL NOT NULL,
  nombre           CHARACTER VARYING(256),
  indicadores      INTEGER,
  fecha_revision   DATE,
  estado           INTEGER DEFAULT 2,
  activo           BOOLEAN DEFAULT TRUE,
  fecha_aprobacion DATE,
  revisadopor      INTEGER,
  aprobadopor      INTEGER
);


ALTER TABLE public.objetivos
  OWNER TO qnova;

SET default_with_oids = FALSE;

--
-- Name: objetivos_globales; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE objetivos_globales (
  id               SERIAL NOT NULL,
  nombre           CHARACTER VARYING(256),
  revisadopor      INTEGER,
  fecha_revision   DATE,
  aprobadopor      INTEGER,
  estado           INTEGER               DEFAULT 2,
  activo           BOOLEAN               DEFAULT TRUE,
  fecha_aprobacion DATE,
  version          CHARACTER VARYING(16) DEFAULT '1.0.0' :: CHARACTER VARYING
);


ALTER TABLE public.objetivos_globales
  OWNER TO qnova;

--
-- Name: objetivos_globales_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('objetivos_globales', 'id'), 16, TRUE);

--
-- Name: objetivos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('objetivos', 'id'), 12, TRUE);


SET default_with_oids = TRUE;

--
-- Name: objetivos_indicadores; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE objetivos_indicadores (
  id             SERIAL NOT NULL,
  proceso        INTEGER,
  indicador      INTEGER,
  valor_objetivo INTEGER,
  fecha_objetivo DATE,
  observaciones  TEXT,
  activo         BOOLEAN,
  objetivo       INTEGER
);


ALTER TABLE public.objetivos_indicadores
  OWNER TO qnova;

--
-- Name: objetivos_indicadores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('objetivos_indicadores', 'id'), 1, FALSE);


SET default_with_oids = FALSE;

--
-- Name: perfiles; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE perfiles (
  id     SERIAL                NOT NULL,
  nombre CHARACTER VARYING(32) NOT NULL,
  activo BOOLEAN,
  mejora INTEGER
);


ALTER TABLE public.perfiles
  OWNER TO qnova;

--
-- Name: perfiles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('perfiles', 'id'), 7, TRUE);

--
-- Name: plan_formacion; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE plan_formacion (
  id            SERIAL NOT NULL,
  nombre        CHARACTER VARYING(128),
  vigente       BOOLEAN,
  descripcion   TEXT,
  activo        BOOLEAN,
  calidad       BOOLEAN,
  medioambiente BOOLEAN
);


ALTER TABLE public.plan_formacion
  OWNER TO qnova;

--
-- Name: plan_formacion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('plan_formacion', 'id'), 4, TRUE);


SET default_with_oids = TRUE;

--
-- Name: plantilla_curso; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE plantilla_curso (
  id                    SERIAL NOT NULL,
  tipo                  INTEGER,
  objetivos             TEXT,
  contenido             TEXT,
  num_horas             INTEGER,
  material_necesario    TEXT,
  material_suministrado TEXT,
  activo                BOOLEAN,
  nombre                CHARACTER VARYING(128),
  calidad               BOOLEAN,
  medioambiente         BOOLEAN
);


ALTER TABLE public.plantilla_curso
  OWNER TO qnova;

--
-- Name: plantilla_curso_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('plantilla_curso', 'id'), 8, TRUE);

--
-- Name: preguntas_legislacion_aplicable; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE preguntas_legislacion_aplicable (
  id                    SERIAL NOT NULL,
  legislacion_aplicable INTEGER,
  pregunta              TEXT,
  valor_deseado         BOOLEAN,
  activo                BOOLEAN
);


ALTER TABLE public.preguntas_legislacion_aplicable
  OWNER TO qnova;

--
-- Name: preguntas_legislacion_aplicable_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('preguntas_legislacion_aplicable', 'id'), 8, TRUE);


SET default_with_oids = FALSE;

--
-- Name: procesos; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE procesos (
  id       SERIAL NOT NULL,
  nombre   CHARACTER VARYING(64),
  revision CHARACTER VARYING(16),
  padre    INTEGER,
  codigo   CHARACTER VARYING(32),
  activo   BOOLEAN DEFAULT TRUE
);


ALTER TABLE public.procesos
  OWNER TO qnova;

--
-- Name: procesos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('procesos', 'id'), 7, TRUE);

--
-- Name: productos; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE productos (
  id             SERIAL NOT NULL,
  valor          INTEGER,
  proveedor      INTEGER,
  criterios      INTEGER [],
  fecha_revision DATE,
  nombre         CHARACTER VARYING(64),
  activo         BOOLEAN,
  homologado     BOOLEAN
);


ALTER TABLE public.productos
  OWNER TO qnova;

--
-- Name: productos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('productos', 'id'), 4, TRUE);


SET default_with_oids = TRUE;

--
-- Name: profesores; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE profesores (
  id              SERIAL NOT NULL,
  curso           INTEGER,
  usuario_interno INTEGER,
  empresa         CHARACTER VARYING(128),
  activo          BOOLEAN,
  interno         BOOLEAN DEFAULT FALSE
);


ALTER TABLE public.profesores
  OWNER TO qnova;

--
-- Name: profesores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('profesores', 'id'), 6, TRUE);


SET default_with_oids = FALSE;

--
-- Name: programa_auditoria; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE programa_auditoria (
  id       SERIAL                 NOT NULL,
  nombre   CHARACTER VARYING(255) NOT NULL,
  activo   BOOLEAN,
  vigente  BOOLEAN,
  revision CHARACTER VARYING(16)
);


ALTER TABLE public.programa_auditoria
  OWNER TO qnova;

--
-- Name: programa_auditoria_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('programa_auditoria', 'id'), 19, TRUE);

--
-- Name: proveedores; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE proveedores (
  id                    SERIAL                 NOT NULL,
  nombre                CHARACTER VARYING(255) NOT NULL,
  direccion             CHARACTER VARYING(1024),
  telefono              CHARACTER VARYING(20),
  web                   CHARACTER VARYING(255),
  cif                   CHARACTER(20),
  fecha_homologacion    DATE,
  ultima_revision       DATE,
  fecha_deshomologacion DATE,
  codigo_postal         CHARACTER(6),
  activo                BOOLEAN
);


ALTER TABLE public.proveedores
  OWNER TO qnova;

--
-- Name: proveedores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('proveedores', 'id'), 5, TRUE);


SET default_with_oids = TRUE;

--
-- Name: registros; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE registros (
  id     SERIAL NOT NULL,
  nombre CHARACTER VARYING(64),
  accion CHARACTER(32)
);


ALTER TABLE public.registros
  OWNER TO qnova;

--
-- Name: registros_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('registros', 'id'), 2, TRUE);

--
-- Name: requisitos_puesto; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE requisitos_puesto (
  id       SERIAL NOT NULL,
  nombre   CHARACTER VARYING(32),
  fecha    DATE,
  codigo   CHARACTER VARYING(16),
  revision CHARACTER VARYING(16),
  activo   BOOLEAN
);


ALTER TABLE public.requisitos_puesto
  OWNER TO qnova;

--
-- Name: requisitos_puesto_competencias; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE requisitos_puesto_competencias (
  conocimientos TEXT,
  funciones     TEXT,
  id            INTEGER NOT NULL
);


ALTER TABLE public.requisitos_puesto_competencias
  OWNER TO qnova;

--
-- Name: requisitos_puesto_datos_puesto; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE requisitos_puesto_datos_puesto (
  nombre_puesto CHARACTER VARYING(32),
  categoria     CHARACTER VARYING(32),
  depende_de    CHARACTER VARYING(32),
  area          CHARACTER VARYING(32),
  requiere_ant  BOOLEAN,
  antiguedad    SMALLINT,
  observaciones TEXT,
  id            INTEGER NOT NULL
);


ALTER TABLE public.requisitos_puesto_datos_puesto
  OWNER TO qnova;

--
-- Name: requisitos_puesto_formacion; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE requisitos_puesto_formacion (
  titulos_oficiales CHARACTER VARYING(128),
  id                INTEGER NOT NULL
);


ALTER TABLE public.requisitos_puesto_formacion
  OWNER TO qnova;

--
-- Name: requisitos_puesto_ft; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE requisitos_puesto_ft (
  id                SERIAL NOT NULL,
  formacion_tecnica CHARACTER VARYING(128),
  opcional          CHARACTER VARYING(128),
  horas             SMALLINT,
  requisitos        INTEGER
);


ALTER TABLE public.requisitos_puesto_ft
  OWNER TO qnova;

--
-- Name: requisitos_puesto_ft_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('requisitos_puesto_ft', 'id'), 1, TRUE);

--
-- Name: requisitos_puesto_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('requisitos_puesto', 'id'), 6, TRUE);

--
-- Name: requisitos_puesto_promocion; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE requisitos_puesto_promocion (
  liderazgo   BOOLEAN,
  mando       BOOLEAN,
  motivacion  BOOLEAN,
  negociacion BOOLEAN,
  trabajo     BOOLEAN,
  formacion   BOOLEAN,
  autonomia   INTEGER,
  relaciones  INTEGER,
  id          INTEGER NOT NULL
);


ALTER TABLE public.requisitos_puesto_promocion
  OWNER TO qnova;

SET default_with_oids = FALSE;

--
-- Name: seguimientos; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE seguimientos (
  id            SERIAL NOT NULL,
  fecha         DATE,
  observaciones CHARACTER VARYING(1024),
  documento     INTEGER,
  objetivos     INTEGER
);


ALTER TABLE public.seguimientos
  OWNER TO qnova;

--
-- Name: seguimientos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('seguimientos', 'id'), 6, TRUE);

--
-- Name: tareas; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE tareas (
  id              SERIAL NOT NULL,
  usuario_origen  INTEGER,
  usuario_destino INTEGER,
  accion          INTEGER,
  documento       INTEGER,
  activo          BOOLEAN,
  descripcion     CHARACTER VARYING(1024),
  fecha           DATE
);


ALTER TABLE public.tareas
  OWNER TO qnova;

--
-- Name: tareas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('tareas', 'id'), 6, TRUE);

--
-- Name: tipo_acciones; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE tipo_acciones (
  id     SERIAL NOT NULL,
  nombre CHARACTER VARYING(32),
  activo BOOLEAN,
  idioma INTEGER
);


ALTER TABLE public.tipo_acciones
  OWNER TO qnova;

--
-- Name: tipo_acciones_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('tipo_acciones', 'id'), 9, TRUE);

--
-- Name: tipo_acciones_idiomas; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE tipo_acciones_idiomas (
  id        SERIAL NOT NULL,
  valor     CHARACTER VARYING(100),
  mejora    INTEGER,
  idioma_id INTEGER
);


ALTER TABLE public.tipo_acciones_idiomas
  OWNER TO qnova;

--
-- Name: tipo_acciones_idiomas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('tipo_acciones_idiomas', 'id'), 9, TRUE);


SET default_with_oids = TRUE;

--
-- Name: tipo_ambito_aplicacion; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE tipo_ambito_aplicacion (
  id     SERIAL NOT NULL,
  nombre CHARACTER VARYING(64),
  idioma INTEGER
);


ALTER TABLE public.tipo_ambito_aplicacion
  OWNER TO qnova;

--
-- Name: tipo_ambito_aplicacion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('tipo_ambito_aplicacion', 'id'), 5, TRUE);


SET default_with_oids = FALSE;

--
-- Name: tipo_ambito_aplicacion_idiomas; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE tipo_ambito_aplicacion_idiomas (
  id        SERIAL NOT NULL,
  valor     CHARACTER VARYING(100),
  tipoamb   INTEGER,
  idioma_id INTEGER
);


ALTER TABLE public.tipo_ambito_aplicacion_idiomas
  OWNER TO qnova;

--
-- Name: tipo_ambito_aplicacion_idiomas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('tipo_ambito_aplicacion_idiomas', 'id'), 3, TRUE);


SET default_with_oids = TRUE;

--
-- Name: tipo_area_aplicacion; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE tipo_area_aplicacion (
  id     SERIAL NOT NULL,
  nombre CHARACTER VARYING(64),
  idioma INTEGER
);


ALTER TABLE public.tipo_area_aplicacion
  OWNER TO qnova;

--
-- Name: tipo_area_aplicacion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('tipo_area_aplicacion', 'id'), 4, TRUE);

--
-- Name: tipo_aspectos; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE tipo_aspectos (
  id     SERIAL NOT NULL,
  nombre CHARACTER VARYING(256)
);


ALTER TABLE public.tipo_aspectos
  OWNER TO qnova;

--
-- Name: tipo_aspectos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('tipo_aspectos', 'id'), 14, TRUE);


SET default_with_oids = FALSE;

--
-- Name: tipo_botones; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE tipo_botones (
  id     SERIAL NOT NULL,
  nombre CHARACTER VARYING(64)
);


ALTER TABLE public.tipo_botones
  OWNER TO qnova;

--
-- Name: tipo_botones_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('tipo_botones', 'id'), 4, TRUE);

--
-- Name: tipo_documento; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE tipo_documento (
  id                     SERIAL NOT NULL,
  nombre                 CHARACTER VARYING(64),
  tipo                   CHARACTER VARYING(32),
  perfil_ver             BOOLEAN [],
  perfil_nueva           BOOLEAN [],
  perfil_modificar       BOOLEAN [],
  perfil_revisar         BOOLEAN [],
  perfil_aprobar         BOOLEAN [],
  perfil_historico       BOOLEAN [],
  perfil_tareas          BOOLEAN [],
  internoexterno         CHARACTER VARYING(16),
  tipo_permiso_documento BOOLEAN [],
  idioma                 INTEGER
);


ALTER TABLE public.tipo_documento
  OWNER TO qnova;

--
-- Name: tipo_documento_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('tipo_documento', 'id'), 21, TRUE);

--
-- Name: tipo_documento_idiomas; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE tipo_documento_idiomas (
  id        SERIAL NOT NULL,
  valor     CHARACTER VARYING(100),
  tipodoc   INTEGER,
  idioma_id INTEGER
);


ALTER TABLE public.tipo_documento_idiomas
  OWNER TO qnova;

--
-- Name: tipo_documento_idiomas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('tipo_documento_idiomas', 'id'), 25, TRUE);


SET default_with_oids = TRUE;

--
-- Name: tipo_estado_auditoria; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE tipo_estado_auditoria (
  id     SERIAL NOT NULL,
  nombre CHARACTER VARYING(32)
);


ALTER TABLE public.tipo_estado_auditoria
  OWNER TO qnova;

--
-- Name: tipo_estado_auditoria_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('tipo_estado_auditoria', 'id'), 1, FALSE);


SET default_with_oids = FALSE;

--
-- Name: tipo_estados_curso; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE tipo_estados_curso (
  id     SERIAL NOT NULL,
  nombre CHARACTER VARYING(64)
);


ALTER TABLE public.tipo_estados_curso
  OWNER TO qnova;

--
-- Name: tipo_estados_curso_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('tipo_estados_curso', 'id'), 1, FALSE);

--
-- Name: tipo_frecuencia; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE tipo_frecuencia (
  id     SERIAL NOT NULL,
  nombre CHARACTER VARYING(64),
  valor  INTEGER
);


ALTER TABLE public.tipo_frecuencia
  OWNER TO qnova;

--
-- Name: tipo_frecuencia_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('tipo_frecuencia', 'id'), 8, TRUE);

--
-- Name: tipo_frecuencia_idiomas; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE tipo_frecuencia_idiomas (
  id         SERIAL NOT NULL,
  valor      CHARACTER VARYING(100),
  frecuencia INTEGER,
  idioma_id  INTEGER
);


ALTER TABLE public.tipo_frecuencia_idiomas
  OWNER TO qnova;

--
-- Name: tipo_frecuencia_idiomas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('tipo_frecuencia_idiomas', 'id'), 7, TRUE);


SET default_with_oids = TRUE;

--
-- Name: tipo_frecuencia_seg; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE tipo_frecuencia_seg (
  id         SERIAL NOT NULL,
  nombre     CHARACTER VARYING(32),
  incremento INTERVAL
);


ALTER TABLE public.tipo_frecuencia_seg
  OWNER TO qnova;

--
-- Name: tipo_frecuencia_seg_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('tipo_frecuencia_seg', 'id'), 1, TRUE);

--
-- Name: tipo_grado_promocion; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE tipo_grado_promocion (
  id     SERIAL NOT NULL,
  nombre CHARACTER VARYING(32)
);


ALTER TABLE public.tipo_grado_promocion
  OWNER TO qnova;

--
-- Name: tipo_grado_promocion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('tipo_grado_promocion', 'id'), 1, FALSE);


SET default_with_oids = FALSE;

--
-- Name: tipo_gravedad; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE tipo_gravedad (
  id     SERIAL NOT NULL,
  nombre CHARACTER VARYING(64),
  valor  INTEGER
);


ALTER TABLE public.tipo_gravedad
  OWNER TO qnova;

--
-- Name: tipo_gravedad_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('tipo_gravedad', 'id'), 15, TRUE);

--
-- Name: tipo_gravedad_idiomas; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE tipo_gravedad_idiomas (
  id        SERIAL NOT NULL,
  valor     CHARACTER VARYING(100),
  gravedad  INTEGER,
  idioma_id INTEGER
);


ALTER TABLE public.tipo_gravedad_idiomas
  OWNER TO qnova;

--
-- Name: tipo_gravedad_idiomas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('tipo_gravedad_idiomas', 'id'), 15, TRUE);


SET default_with_oids = TRUE;

--
-- Name: tipo_impactos; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE tipo_impactos (
  id     SERIAL NOT NULL,
  nombre CHARACTER VARYING(256),
  activo BOOLEAN,
  idioma INTEGER
);


ALTER TABLE public.tipo_impactos
  OWNER TO qnova;

--
-- Name: tipo_impactos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('tipo_impactos', 'id'), 18, TRUE);


SET default_with_oids = FALSE;

--
-- Name: tipo_impactos_idiomas; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE tipo_impactos_idiomas (
  id        SERIAL NOT NULL,
  valor     CHARACTER VARYING(100),
  impactos  INTEGER,
  idioma_id INTEGER
);


ALTER TABLE public.tipo_impactos_idiomas
  OWNER TO qnova;

--
-- Name: tipo_impactos_idiomas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('tipo_impactos_idiomas', 'id'), 14, TRUE);

--
-- Name: tipo_magnitud; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE tipo_magnitud (
  id     SERIAL NOT NULL,
  nombre CHARACTER VARYING(64),
  valor  INTEGER
);


ALTER TABLE public.tipo_magnitud
  OWNER TO qnova;

--
-- Name: tipo_magnitud_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('tipo_magnitud', 'id'), 15, TRUE);

--
-- Name: tipo_magnitud_idiomas; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE tipo_magnitud_idiomas (
  id        SERIAL NOT NULL,
  valor     CHARACTER VARYING(100),
  magnitud  INTEGER,
  idioma_id INTEGER
);


ALTER TABLE public.tipo_magnitud_idiomas
  OWNER TO qnova;

--
-- Name: tipo_magnitud_idiomas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('tipo_magnitud_idiomas', 'id'), 10, TRUE);

--
-- Name: tipo_probabilidad; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE tipo_probabilidad (
  id     SERIAL NOT NULL,
  valor  INTEGER,
  nombre CHARACTER VARYING(64)
);


ALTER TABLE public.tipo_probabilidad
  OWNER TO qnova;

--
-- Name: tipo_probabilidad_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('tipo_probabilidad', 'id'), 4, TRUE);

--
-- Name: tipo_probabilidad_idiomas; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE tipo_probabilidad_idiomas (
  id           SERIAL NOT NULL,
  valor        CHARACTER VARYING(100),
  probabilidad INTEGER,
  idioma_id    INTEGER
);


ALTER TABLE public.tipo_probabilidad_idiomas
  OWNER TO qnova;

--
-- Name: tipo_probabilidad_idiomas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('tipo_probabilidad_idiomas', 'id'), 7, TRUE);

--
-- Name: tipo_severidad; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE tipo_severidad (
  id     SERIAL NOT NULL,
  valor  INTEGER,
  nombre CHARACTER VARYING(64)
);


ALTER TABLE public.tipo_severidad
  OWNER TO qnova;

--
-- Name: tipo_severidad_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('tipo_severidad', 'id'), 4, TRUE);

--
-- Name: tipo_severidad_idiomas; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE tipo_severidad_idiomas (
  id        SERIAL NOT NULL,
  valor     CHARACTER VARYING(100),
  severidad INTEGER,
  idioma_id INTEGER
);


ALTER TABLE public.tipo_severidad_idiomas
  OWNER TO qnova;

--
-- Name: tipo_severidad_idiomas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('tipo_severidad_idiomas', 'id'), 8, TRUE);

--
-- Name: tipo_tarea; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE tipo_tarea (
  id     SERIAL NOT NULL,
  nombre CHARACTER VARYING(64)
);


ALTER TABLE public.tipo_tarea
  OWNER TO qnova;

--
-- Name: tipo_tarea_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('tipo_tarea', 'id'), 3, TRUE);

--
-- Name: tipos_cursos; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE tipos_cursos (
  id     SERIAL NOT NULL,
  nombre CHARACTER VARYING(64)
);


ALTER TABLE public.tipos_cursos
  OWNER TO qnova;

--
-- Name: tipos_cursos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('tipos_cursos', 'id'), 2, TRUE);


SET default_with_oids = TRUE;

--
-- Name: tipos_fichero; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE tipos_fichero (
  id        SERIAL NOT NULL,
  nombre    CHARACTER VARYING(32),
  extension CHARACTER(4),
  mime      CHARACTER VARYING(64)
);


ALTER TABLE public.tipos_fichero
  OWNER TO qnova;

--
-- Name: tipos_fichero_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('tipos_fichero', 'id'), 1, FALSE);


SET default_with_oids = FALSE;

--
-- Name: usuarios; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE usuarios (
  id                    SERIAL                NOT NULL,
  "login"               CHARACTER VARYING(32) NOT NULL,
  "password"            CHARACTER VARYING(32) NOT NULL,
  perfil                INTEGER DEFAULT 0,
  nombre                CHARACTER VARYING(32),
  primer_apellido       CHARACTER VARYING(32),
  segundo_apellido      CHARACTER VARYING(32),
  nif                   CHARACTER(9),
  telefono              CHARACTER VARYING(16),
  email                 CHARACTER VARYING(32),
  anejos                INTEGER [],
  area                  INTEGER,
  ficha                 INTEGER,
  requisitos            INTEGER,
  ultimo_acceso_nulo    TIMESTAMP WITHOUT TIME ZONE,
  ultimo_acceso         TIMESTAMP WITHOUT TIME ZONE,
  numero_accesos        INTEGER DEFAULT 0,
  numero_accesos_nulos  INTEGER DEFAULT 0,
  ultimo_acceso_ip      CHARACTER VARYING(16),
  ultimo_acceso_ip_nulo CHARACTER VARYING(16),
  activo                CHARACTER VARYING(1)
);


ALTER TABLE public.usuarios
  OWNER TO qnova;

--
-- Name: usuarios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('usuarios', 'id'), 24, TRUE);


SET default_with_oids = TRUE;

--
-- Name: valores; Type: TABLE; Schema: public; Owner: qnova; Tablespace: 
--

CREATE TABLE valores (
  id        SERIAL NOT NULL,
  indicador INTEGER,
  fecha     DATE,
  valor     DOUBLE PRECISION,
  activo    BOOLEAN,
  proceso   INTEGER
);


ALTER TABLE public.valores
  OWNER TO qnova;

--
-- Name: valores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: qnova
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('valores', 'id'), 18, TRUE);

--
-- Data for Name: acciones_mejora; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: alumnos; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: areas; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO areas VALUES (0, 'Ninguna', TRUE);

--
-- Data for Name: aspectos; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: auditores; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: auditorias; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: botones; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO botones VALUES (44, 12, 'Cuestionario', 'sndReq(''documentos:cuestionario:formulario:nuevo:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES
  (45, 12, 'Historico', 'sndReq(''documentos:historicocuestionario:listado:nuevo:fila'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (162, 3, 'Detalles', 'sndReq(''documentacion:manual:detalles:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (30, 7, 'Ver', 'sndReq(''documentos:documentopg:comun:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (31, 7, 'Detalles', 'sndReq(''documentacion:documentopg:detalles:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (33, 8, 'Ver', 'sndReq(''documentos:documentope:comun:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (34, 8, 'Detalles', 'sndReq(''documentacion:documentope:detalles:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES
  (151, 9, 'Ver', 'sndReq(''documentos:frl:comun:ver:fila'','''',1)', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}',
   4);
INSERT INTO botones VALUES (152, 9, 'Detalles', 'sndReq(''documentacion:frl:detalles:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (196, 3, 'Permisos', 'sndReq(''documentacion:manual:listado:verpermisos:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (197, 4, 'Permisos', 'sndReq(''documentacion:pambiental:listado:verpermisos:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (234, 107, NULL, 'sndReq(''administracion:modulos:arbol:permisos:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (190, 93, NULL, 'sndReq(''administracion:menu:comun:estructura'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (174, 63, 'Nuevo', 'sndReq(''documentacion:documentonormativa:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (83, 26, 'Nuevo', 'sndReq(''aambientales:aspecto:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (105, 35, 'Historico', 'sndReq(''inicio:historicomensajes:listado:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (160, 3, 'Nuevo', 'sndReq(''documentacion:manual:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (1, 14, 'Nuevo', 'sndReq(''mejora:acmejora:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (75, 24, 'Editar', 'sndReq(''auditorias:auditoria:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (216, 102, NULL, 'sndReq(''administracion:probabilidad:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (117, 41, 'Nuevo', 'sndReq(''administracion:mejora:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (123, 44, 'Nuevo', 'sndReq(''administracion:tipoamb:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (121, 43, 'Nuevo', 'sndReq(''administracion:tipoarea:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (153, 58, 'Nuevo', 'sndReq(''administracion:impacto:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (64, 23, 'Nuevo', 'sndReq(''auditorias:programa:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (70, 24, 'Agregar', 'sndReq(''auditorias:auditoria:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (40, 12, 'Nuevo', 'sndReq(''documentacion:legislacion:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (29, 7, 'Nuevo', 'sndReq(''documentacion:documentopg:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (199, 7, 'Permisos', 'sndReq(''documentacion:documentopg:listado:verpermisos:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (200, 8, 'Permisos', 'sndReq(''documentacion:documentope:listado:verpermisos:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (128, 50, 'Nuevo', 'sndReq(''indicadores:objetivo:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (4, 45, 'Nuevo', 'sndReq(''proveedores:proveedor:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (10, 46, 'Nuevo', 'sndReq(''proveedores:incidenciafila:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (13, 47, 'Nuevo', 'sndReq(''proveedores:contactosfila:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (16, 48, 'Nuevo', 'sndReq(''proveedores:productofila:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (217, 103, NULL, 'sndReq(''administracion:severidad:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (218, 102, NULL, 'sndReq(''administracion:probabilidad:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (219, 103, NULL, 'sndReq(''administracion:severidad:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (222, 50, 'Aprobar', 'sndReq(''indicadores:objetivo:comun:aprobar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (223, 50, 'Revisar', 'sndReq(''indicadores:objetivo:comun:revisar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (224, 101, NULL, 'sndReq(''aambientales:aspectoemergencia:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES
  (201, 62, 'Permisos', 'sndReq(''documentacion:documentoaai:listado:verpermisos:fila'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (202, 9, 'Permisos', 'sndReq(''documentacion:frl:listado:verpermisos:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (203, 13, 'Permisos', 'sndReq(''documentacion:formatos:listado:verpermisos:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (159, 5, 'Editar', 'sndReq(''documentacion:objetivos:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (158, 5, 'Nuevo', 'sndReq(''documentacion:objetivos:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (169, 5, 'Revisar', 'sndReq(''documentacion:objetivos:comun:revisar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (204, 97, NULL, 'sndReq(''administracion:gravedad:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (205, 96, NULL, 'sndReq(''administracion:magnitud:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (206, 98, NULL, 'sndReq(''administracion:frecuencia:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (208, 97, NULL, 'sndReq(''administracion:gravedad:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (209, 96, NULL, 'sndReq(''administracion:magnitud:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (210, 98, NULL, 'sndReq(''administracion:frecuencia:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (213, 100, NULL, 'sndReq(''administracion:formula:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (86, 28, 'Nuevo', 'parent.sndReq(''catalogo:proceso:formulario:nuevo:radio'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (87, 28, 'Editar', 'parent.sndReq(''catalogo:proceso:formulario:editar:radio'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (88, 28, 'Detalles', 'parent.sndReq(''catalogo:proceso:detalles:ver:radio'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (90, 28, 'Ver Matriz', 'parent.sndReq(''catalogo:proceso:listado:vermatriz:radio'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (131, 28, 'Indicadores', 'parent.sndReq(''catalogo:indicadores:listado:ver:radio'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (132, 4, 'Nueva', 'sndReq(''documentacion:pambiental:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (53, 19, 'Nuevo', 'sndReq(''formacion:planes:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (79, 31, 'Nuevo', 'sndReq(''indicadores:indicador:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (82, 31, 'Matriz', 'sndReq(''indicadores:indicadores:listado:vermatriz'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (192, 94, NULL, 'sndReq(''administracion:idioma:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (188, 93, NULL, 'sndReq(''administracion:menu:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (187, 93, NULL, 'sndReq(''administracion:menu:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (215, 50, NULL, 'sndReq(''indicadores:objetivos:listado:verpdf:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (91, 32, 'Nuevo', 'sndReq(''administracion:usuario:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (95, 33, 'Nuevo', 'sndReq(''administracion:perfil:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (102, 35, 'Nuevo', 'sndReq(''administracion:mensajes:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (32, 8, 'Nuevo', 'sndReq(''documentacion:documentope:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (150, 9, 'Nuevo', 'sndReq(''documentacion:frl:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (57, 20, 'Nuevo', 'sndReq(''equipos:equipo:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (46, 17, 'Nuevo', 'sndReq(''formacion:cursos:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (166, 61, 'Nuevo', 'sndReq(''administracion:tipocurso:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (170, 13, 'Nuevo', 'sndReq(''documentacion:formatos:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (173, 62, 'Nuevo', 'sndReq(''documentacion:documentoaai:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (115, 40, 'Nuevo', 'sndReq(''administracion:criterio:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (119, 42, 'Nuevo', 'sndReq(''administracion:cliente:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (185, 64, 'Nuevo', 'sndReq(''administracion:tipodocumento:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (146, 2, 'Eliminar', 'sndReq(''inicio:tarea:comun:baja:general'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES (24, 49, 'Dar de Baja', 'sndReq(''proveedores:proveedores:comun:baja:general'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES (92, 32, 'Dar de Baja', 'sndReq(''administracion:usuarios:comun:baja:general'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES (96, 33, 'Dar de Baja', 'sndReq(''administracion:perfiles:comun:baja:general'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES (103, 35, 'Dar de Baja', 'sndReq(''administracion:mensajes:comun:baja:general'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES (106, 36, 'Dar de Baja', 'sndReq(''administracion:tareas:comun:baja:general'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES (107, 37, 'Dar de alta', 'sndReq(''administracion:documentos:comun:alta:general'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES (108, 37, 'Dar de baja', 'sndReq(''administracion:documentos:comun:baja:general'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES
  (114, 39, 'Dar de baja', 'sndReq(''administracion:documentosformato:comun:baja:general'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES (155, 58, 'Baja', 'sndReq(''administracion:impacto:comun:baja:general'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES (85, 26, 'Dar de Baja', 'sndReq(''aambientales:aspecto:comun:baja:general'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES (68, 23, 'Dar de Baja', 'sndReq(''auditorias:programa:comun:baja:general'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES (72, 24, 'Dar de Baja', 'sndReq(''auditorias:auditoria:comun:baja:general'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES (59, 20, 'Dar de Baja', 'sndReq(''equipos:listado:comun:baja:general'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES (48, 17, 'Dar de Baja', 'sndReq(''formacion:plantilla_curso:comun:baja:general'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES (6, 45, 'Dar de Baja', 'sndReq(''proveedores:proveedores:comun:baja:general'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES
  (172, 39, 'Dar de alta', 'sndReq(''administracion:documentosformato:comun:alta:general'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES (51, 18, 'Asistentes', 'sndReq(''formacion:inscripcion:listado:asistentes:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (52, 18, 'Detalles', 'sndReq(''formacion:inscripcion:detalles:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (54, 19, 'Editar', 'sndReq(''formacion:planes:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (55, 19, 'Cursos Plan', 'sndReq(''formacion:planes:listado:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (56, 19, 'Hacer Vigente', 'sndReq(''formacion:planes:comun:hacervigente:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (80, 31, 'Editar', 'sndReq(''indicadores:indicador:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (129, 50, 'Editar', 'sndReq(''indicadores:objetivo:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (130, 50, 'Dar de Baja', 'sndReq(''indicadores:objetivo:comun:baja:general'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (23, 49, 'Editar', 'sndReq(''proveedores:proveedor:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (5, 45, 'Editar', 'sndReq(''proveedores:proveedor:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (7, 45, 'Ver Productos', 'sndReq(''proveedores:productos:listado:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (8, 45, 'Ver Contactos', 'sndReq(''proveedores:contactos:listado:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (9, 45, 'Ver Incidencias', 'sndReq(''proveedores:incidencias:listado:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (11, 46, 'Ver', 'sndReq(''proveedores:incidencia:listado:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (12, 46, 'Editar', 'sndReq(''proveedores:incidenciafila:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (14, 47, 'Ver', 'sndReq(''proveedores:contacto:listado:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (15, 47, 'Editar', 'sndReq(''proveedores:contactosfila:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (17, 48, 'Ver', 'sndReq(''proveedores:producto:listado:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (18, 48, 'Editar', 'sndReq(''proveedores:productofila:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (19, 48, 'Criterios', 'sndReq(''proveedores:productos:listado:criterios:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (20, 48, 'Revisar', 'sndReq(''proveedores:producto:comun:revisar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES
  (21, 48, 'Historico', 'sndReq(''proveedores:productos:listado:productoshistorico:fila'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (93, 32, 'Editar', 'sndReq(''administracion:usuario:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES
  (94, 32, 'Cambiar Password', 'sndReq(''administracion:passwordusuario:formulario:editar:fila'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (97, 33, 'Editar', 'sndReq(''administracion:perfil:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (127, 33, 'Copiar Perfil', 'sndReq(''administracion:perfil:comun:copiar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (104, 35, 'Editar', 'sndReq(''administracion:mensajes:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (126, 38, 'Listar', 'sndReq(''documentos:registros:listado:listarfila:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (118, 41, 'Editar', 'sndReq(''administracion:mejora:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (139, 55, 'Preguntas', 'sndReq(''administracion:preguntasleg:listado:nuevo:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (124, 44, 'Editar', 'sndReq(''administracion:tipoamb:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (122, 43, 'Editar', 'sndReq(''administracion:tipoarea:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (154, 58, 'Editar', 'sndReq(''administracion:impacto:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (2, 14, 'Editar', 'sndReq(''mejora:acmejora:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (3, 14, 'Verificar', 'sndReq(''mejora:accmejora:listado:verificar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (140, 14, 'Ver Pdf', 'sndReq(''mejora:mejorapdf:listado:nuevo:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (142, 14, 'Cerrar Accion', 'sndReq(''mejora:cerraraccmejora:listado:nuevo:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (84, 26, 'Editar', 'sndReq(''aambientales:aspecto:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (65, 23, 'Editar', 'sndReq(''auditorias:programa:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (66, 23, 'Copiar', 'sndReq(''auditoria:programa:comun:copiar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (67, 23, 'Hacer Vigente', 'sndReq(''auditoria:programa:comun:hacervigente:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (69, 23, 'Auditorias', 'sndReq(''auditoria:programa:listado:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (73, 24, 'Estado/Detalles', 'sndReq(''auditorias:auditoria:detalles:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (74, 24, 'Plan Auditoria', 'sndReq(''auditorias:plan:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (76, 24, 'Equipo Auditor', 'sndReq(''auditorias:equipoauditor:listado:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (77, 24, 'Informe', 'sndReq(''auditorias:informeauditoria:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (38, 11, 'Listar', 'sndReq(''documentacion:listadoregistros:listado:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (41, 12, 'Ver F.R.L.', 'sndReq(''documentos:legislacion:comun:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (42, 12, 'Editar', 'sndReq(''documentacion:legislacion:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (43, 12, 'Ver Ley', 'sndReq(''documentacion:legislacion:listado:verley:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES
  (25, 10, 'Ver', 'sndReq(''documentos:vigor:comun:ver:fila'','''',1)', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}',
   4);
INSERT INTO botones VALUES (26, 10, 'Detalles', 'sndReq(''documentacion:vigor:detalles:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (27, 57, 'Ver', 'sndReq(''documentos:borrador:comun:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (28, 57, 'Detalles', 'sndReq(''documentacion:borrador:detalles:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES
  (58, 20, 'Ver', 'sndReq(''equipos:equipo:listado:ver:fila'','''',1)', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}',
   4);
INSERT INTO botones VALUES (60, 20, 'Plan Mant.', 'sndReq(''equipos:planmantenimiento:listado:nuevo:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES
  (61, 20, 'Mant. Preventivo', 'sndReq(''equipos:mantenimientoprev:formulario:editar:fila'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES
  (62, 20, 'Mant. Correctivo', 'sndReq(''equipos:mantenimientocorr:formulario:editar:fila'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES
  (63, 21, 'Plan de Mantenimiento', 'sndReq(''equipos:planmantenimiento:listado:ver:fila'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (161, 3, 'Ver', 'sndReq(''documentos:manual:comun:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (171, 13, 'Detalles', 'sndReq(''documentacion:formatos:detalles:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (39, 13, 'Ver', 'sndReq(''documentos:docformatos:comun:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (175, 62, 'Ver', 'sndReq(''documentos:documentoaai:comun:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (176, 62, 'Detalles', 'sndReq(''documentos:documentoaai:detalles:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (179, 5, 'Aprobar', 'sndReq(''documentacion:objetivos:comun:aprobar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (180, 5, 'Metas', 'sndReq(''documentacion:metas:listado:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES
  (181, 5, 'Nueva version', 'sndReq(''documentacion:objetivos:comun:nuevaversion:fila'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (116, 40, 'Editar', 'sndReq(''administracion:criterio:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (120, 42, 'Editar', 'sndReq(''administracion:cliente:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (182, 31, 'Valores', 'sndReq(''indicadores:valoresindicador:listado:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (186, 64, 'Editar', 'sndReq(''administracion:tipodocumento:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (189, 93, NULL, 'sndReq(''administracion:menu:listado:verbotones:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (191, 93, NULL, 'sndReq(''administracion:idiomas:listado:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES
  (193, 24, 'horario Auditoria', 'sndReq(''auditorias:horarioauditoria:listado:ver:fila'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (194, 64, 'Permisos', 'sndReq(''administracion:permisosdocumento:listado:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (183, 31, 'Valores', 'sndReq(''indicadores:graficaindicador:comun:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES (177, 63, 'Ver', 'sndReq(''documentos:documentonormativa:comun:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES
  (178, 63, 'Detalles', 'sndReq(''documentacion:documentonormativa:detalles:ver:fila'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (195, 50, '', 'sndReq(''indicadores:objetivos:listado:vermetas:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (226, 101, NULL, 'sndReq(''aambientales:aspectoemergencia:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (125, 1, 'Enviar Msj', 'sndReq(''inicio:mensajes:formulario:nuevo'','''',1)',
                            '[0:22]={f,f,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f}', 1);
INSERT INTO botones VALUES (239, 5, 'Ver PDF', 'sndReq(''documentacion:objetivos:listado:verpdf:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (235, 108, NULL, 'sndReq(''administracion:hospitales:formulario:nuevo'','''',1)',
                            '{f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (236, 108, NULL, 'sndReq(''administracion:hospitales:comun:baja'','''',1)',
                            '{f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (214, 1, NULL, 'sndReq(''inicio:mensajes:comun:estadisticas'','''',1)',
                            '[0:22]={f,f,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f}', 2);
INSERT INTO botones VALUES (237, 108, NULL, 'sndReq(''administracion:hospitales:comun:generar:fila'','''',1)',
                            '{f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (238, 108, NULL, 'sndReq(''administracion:hospitales:formulario:editar:fila'','''',1)',
                            '{f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (47, 17, 'Editar', 'sndReq(''formacion:cursos:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (49, 18, 'Solicitar Plaza', 'sndReq(''formacion:inscripcion:comun:alta:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (50, 18, 'Solicitar Baja', 'sndReq(''formacion:inscripcion:comun:baja:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (165, 23, 'Nueva Revision', 'sndReq(''auditorias:programa:comun:revision:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (167, 61, 'Editar', 'sndReq(''administracion:tipocurso:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (168, 19, 'Dar de Baja', 'sndReq(''formacion:planes:comun:baja:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (134, 4, 'Detalles', 'sndReq(''documentacion:pambiental:detalles:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (135, 4, 'Ver', 'sndReq(''documentos:pambiental:comun:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (225, 101, NULL, 'sndReq(''aambientales:aspecto:comun:baja:general'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (227, 104, 'Nuevo', 'sndReq(''documentacion:planemeramb:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (228, 104, 'Ver', 'sndReq(''documentos:planemeramb:comun:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (229, 104, 'Detalles', 'sndReq(''documentacion:planemeramb:detalles:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES
  (230, 104, 'Permisos', 'sndReq(''documentacion:planemeramb:listado:verpermisos:fila'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (231, 106, 'Nuevo', 'sndReq(''administracion:ayuda:formulario:nuevo'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones VALUES (232, 106, 'Editar', 'sndReq(''administracion:ayuda:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES
  (233, 5, 'Seguimiento', 'sndReq(''documentacion:objetivos:listado:seguimiento:fila'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (240, 96, 'Idioma', 'sndReq(''administracion:magnitudidioma:listado:idioma:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (241, 97, 'Idioma', 'sndReq(''administracion:gravedadidioma:listado:idioma:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (242, 98, 'Idioma', 'sndReq(''administracion:frecuenciaidioma:listado:idioma:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES
  (243, 102, 'Idioma', 'sndReq(''administracion:probabilidadidioma:listado:idioma:fila'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (244, 103, 'Idioma', 'sndReq(''administracion:severidadidioma:listado:idioma:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (245, 41, 'Idioma', 'sndReq(''administracion:mejoraidioma:listado:idioma:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (246, 44, 'Idioma', 'sndReq(''administracion:tipoambidioma:listado:idioma:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (247, 58, 'Idioma', 'sndReq(''administracion:tipoimpidioma:listado:idioma:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (248, 64, 'Idioma', 'sndReq(''administracion:tipodocidioma:listado:idioma:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES (249, 37, 'Bajar xls', 'sndReq(''administracion:documentossg:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES
  (250, 1, 'Bajar xls', 'sndReq(''inicio:mensajes:excel:ver'','''',1)', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}',
   1);
INSERT INTO botones VALUES (251, 3, 'Bajar xls', 'sndReq(''documentos:manual:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (252, 5, 'Bajar xls', 'sndReq(''documentacion:objetivos:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (253, 7, 'Bajar xls', 'sndReq(''documentacion:pg:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (254, 8, 'Bajar xls', 'sndReq(''documentacion:pe:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (255, 10, 'Bajar xls', 'sndReq(''documentacion:docvigor:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (256, 57, 'Bajar xls', 'sndReq(''documentacion:docborrador:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (257, 62, 'Bajar xls', 'sndReq(''documentacion:aai:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (258, 104, 'Bajar xls', 'sndReq(''documentacion:planamb:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (259, 9, 'Bajar xls', 'sndReq(''documentacion:frl:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (260, 12, 'Bajar xls', 'sndReq(''documentacion:legislacion:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (261, 63, 'Bajar xls', 'sndReq(''documentacion:normativa:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (262, 11, 'Bajar xls', 'sndReq(''documentacion:registros:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (263, 13, 'Bajar xls', 'sndReq(''documentacion:docformatos:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES
  (264, 14, 'Bajar xls', 'sndReq(''mejora:listado:excel:ver'','''',1)', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}',
   1);
INSERT INTO botones VALUES (265, 17, 'Bajar xls', 'sndReq(''formacion:cursos:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (266, 18, 'Bajar xls', 'sndReq(''formacion:inscripcion:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (267, 19, 'Bajar xls', 'sndReq(''formacion:planes:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (268, 23, 'Bajar xls', 'sndReq(''auditoria:programa:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES
  (269, 24, 'Bajar xls', 'sndReq(''auditoria:plan:excel:ver'','''',1)', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}',
   1);
INSERT INTO botones VALUES (270, 31, 'Bajar xls', 'sndReq(''indicadores:indicadores:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (271, 50, 'Bajar xls', 'sndReq(''indicadores:indobjetivos:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (272, 26, 'Bajar xls', 'sndReq(''aambientales:revision:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (273, 101, 'Bajar xls', 'sndReq(''aambientales:aspectoemergencia:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (274, 32, 'Bajar xls', 'sndReq(''administracion:usuario:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (275, 33, 'Bajar xls', 'sndReq(''administracion:perfiles:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (276, 35, 'Bajar xls', 'sndReq(''administracion:adminmensajes:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (277, 36, 'Bajar xls', 'sndReq(''administracion:admintareas:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (278, 93, 'Bajar xls', 'sndReq(''administracion:adminmenus:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (279, 108, 'Bajar xls', 'sndReq(''administracion:hospitales:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (280, 38, 'Bajar xls', 'sndReq(''administracion:adminregistros2:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (281, 39, 'Bajar xls', 'sndReq(''administracion:adminnormativa:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (282, 41, 'Bajar xls', 'sndReq(''administracion:adminmejora:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (283, 43, 'Bajar xls', 'sndReq(''administracion:admintipoarea:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (284, 44, 'Bajar xls', 'sndReq(''administracion:admintipoamb:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (285, 55, 'Bajar xls', 'sndReq(''administracion:adminlegaplicable:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (286, 58, 'Bajar xls', 'sndReq(''administracion:admintipoimpacto:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (287, 61, 'Bajar xls', 'sndReq(''administracion:admintipo_cursos:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (288, 64, 'Bajar xls', 'sndReq(''administracion:admintipo_doc:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (289, 96, 'Bajar xls', 'sndReq(''administracion:magnitud:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (290, 97, 'Bajar xls', 'sndReq(''administracion:gravedad:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (291, 98, 'Bajar xls', 'sndReq(''administracion:frecuencia:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (292, 100, 'Bajar xls', 'sndReq(''administracion:formula:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (293, 102, 'Bajar xls', 'sndReq(''administracion:probabilidad:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (294, 103, 'Bajar xls', 'sndReq(''administracion:severidad:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (295, 106, 'Bajar xls', 'sndReq(''administracion:ayuda:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (296, 107, 'Bajar xls', 'sndReq(''administracion:permisos:excel:ver'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES
  (297, 55, 'Dar de Baja', 'sndReq(''administracion:adminlegaplicable:comun:baja:general'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (298, 28, 'Eliminar', 'parent.sndReq(''procesos:catalogo:comun:baja:radio'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (299, 20, 'Editar', 'sndReq(''equipos:equipo:formulario:editar:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES
  (300, 110, 'Dar de baja', 'sndReq(''administracion:proveedorlistado:comun:baja:general'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES
  (301, 110, 'Dar de alta', 'sndReq(''administracion:proveedorlistado:comun:alta:general'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES
  (302, 111, 'Dar de baja', 'sndReq(''administracion:proveedorincidencias:comun:baja:general'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES
  (303, 111, 'Dar de alta', 'sndReq(''administracion:proveedorincidencias:comun:alta:general'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES
  (304, 112, 'Dar de baja', 'sndReq(''administracion:proveedorcontactos:comun:baja:general'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES
  (305, 112, 'Dar de alta', 'sndReq(''administracion:proveedorcontactos:comun:alta:general'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES
  (306, 113, 'Dar de baja', 'sndReq(''administracion:proveedorproductos:comun:baja:general'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES
  (307, 113, 'Dar de alta', 'sndReq(''administracion:proveedorproductos:comun:alta:general'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES
  (310, 117, 'Dar de baja', 'sndReq(''administracion:auditoriaanual:comun:baja:general'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES
  (311, 117, 'Dar de alta', 'sndReq(''administracion:auditoriaanual:comun:alta:general'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES
  (312, 118, 'Dar de baja', 'sndReq(''administracion:auditoriavigor:comun:baja:general'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES
  (313, 118, 'Dar de alta', 'sndReq(''administracion:auditoriavigor:comun:alta:general'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES
  (308, 115, 'Dar de baja', 'sndReq(''administracion:equiposlistado:comun:baja:general'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES
  (309, 115, 'Dar de alta', 'sndReq(''administracion:equiposlistado:comun:alta:general'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES
  (314, 120, 'Dar de baja', 'sndReq(''administracion:indicadoresobjetivo:comun:baja:general'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES
  (315, 120, 'Dar de alta', 'sndReq(''administracion:indicadoresobjetivo:comun:alta:general'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones VALUES (22, 49, 'Ver', 'sndReq(''proveedores:proveedor:listado:ver:fila'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones VALUES
  (316, 55, 'Dar de Alta', 'sndReq(''administracion:adminlegaplicable:comun:alta:general'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones VALUES (89, 28, 'Ver Ficha', 'parent.sndReq(''catalogo:fichaproceso:comun:ver:radio'','''',1)',
                            '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);

--
-- Data for Name: botones_idiomas; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO botones_idiomas VALUES (1, 'Asistentes', 51, 1);
INSERT INTO botones_idiomas VALUES (2, 'Detalles', 52, 1);
INSERT INTO botones_idiomas VALUES (3, 'Nuevo', 53, 1);
INSERT INTO botones_idiomas VALUES (4, 'Editar', 54, 1);
INSERT INTO botones_idiomas VALUES (5, 'Cursos Plan', 55, 1);
INSERT INTO botones_idiomas VALUES (6, 'Hacer Vigente', 56, 1);
INSERT INTO botones_idiomas VALUES (7, 'Nuevo', 79, 1);
INSERT INTO botones_idiomas VALUES (8, 'Editar', 80, 1);
INSERT INTO botones_idiomas VALUES (9, 'Matriz', 82, 1);
INSERT INTO botones_idiomas VALUES (10, 'Nuevo', 128, 1);
INSERT INTO botones_idiomas VALUES (11, 'Editar', 129, 1);
INSERT INTO botones_idiomas VALUES (12, 'Dar de Baja', 130, 1);
INSERT INTO botones_idiomas VALUES (13, 'Enviar Msj', 125, 1);
INSERT INTO botones_idiomas VALUES (14, 'Eliminar', 146, 1);
INSERT INTO botones_idiomas VALUES (15, 'Editar', 23, 1);
INSERT INTO botones_idiomas VALUES (16, 'Dar de Baja', 24, 1);
INSERT INTO botones_idiomas VALUES (17, 'Nuevo', 86, 1);
INSERT INTO botones_idiomas VALUES (18, 'Editar', 87, 1);
INSERT INTO botones_idiomas VALUES (19, 'Detalles', 88, 1);
INSERT INTO botones_idiomas VALUES (20, 'Ver Ficha', 89, 1);
INSERT INTO botones_idiomas VALUES (21, 'Ver Matriz', 90, 1);
INSERT INTO botones_idiomas VALUES (22, 'Indicadores', 131, 1);
INSERT INTO botones_idiomas VALUES (23, 'Nuevo', 4, 1);
INSERT INTO botones_idiomas VALUES (24, 'Editar', 5, 1);
INSERT INTO botones_idiomas VALUES (25, 'Ver Productos', 7, 1);
INSERT INTO botones_idiomas VALUES (26, 'Ver Contactos', 8, 1);
INSERT INTO botones_idiomas VALUES (27, 'Ver Incidencias', 9, 1);
INSERT INTO botones_idiomas VALUES (28, 'Nuevo', 10, 1);
INSERT INTO botones_idiomas VALUES (29, 'Ver', 11, 1);
INSERT INTO botones_idiomas VALUES (30, 'Editar', 12, 1);
INSERT INTO botones_idiomas VALUES (31, 'Nuevo', 13, 1);
INSERT INTO botones_idiomas VALUES (32, 'Ver', 14, 1);
INSERT INTO botones_idiomas VALUES (33, 'Editar', 15, 1);
INSERT INTO botones_idiomas VALUES (34, 'Nuevo', 16, 1);
INSERT INTO botones_idiomas VALUES (35, 'Ver', 17, 1);
INSERT INTO botones_idiomas VALUES (36, 'Editar', 18, 1);
INSERT INTO botones_idiomas VALUES (37, 'Criterios', 19, 1);
INSERT INTO botones_idiomas VALUES (38, 'Revisar', 20, 1);
INSERT INTO botones_idiomas VALUES (39, 'Historico', 21, 1);
INSERT INTO botones_idiomas VALUES (40, 'Ver', 22, 1);
INSERT INTO botones_idiomas VALUES (41, 'Nuevo', 91, 1);
INSERT INTO botones_idiomas VALUES (42, 'Dar de Baja', 92, 1);
INSERT INTO botones_idiomas VALUES (43, 'Editar', 93, 1);
INSERT INTO botones_idiomas VALUES (44, 'Cambiar Password', 94, 1);
INSERT INTO botones_idiomas VALUES (45, 'Nuevo', 95, 1);
INSERT INTO botones_idiomas VALUES (46, 'Dar de Baja', 96, 1);
INSERT INTO botones_idiomas VALUES (47, 'Editar', 97, 1);
INSERT INTO botones_idiomas VALUES (50, 'Copiar Perfil', 127, 1);
INSERT INTO botones_idiomas VALUES (51, 'Nuevo', 102, 1);
INSERT INTO botones_idiomas VALUES (52, 'Dar de Baja', 103, 1);
INSERT INTO botones_idiomas VALUES (53, 'Editar', 104, 1);
INSERT INTO botones_idiomas VALUES (54, 'Historico', 105, 1);
INSERT INTO botones_idiomas VALUES (55, 'Dar de Baja', 106, 1);
INSERT INTO botones_idiomas VALUES (56, 'Dar de alta', 107, 1);
INSERT INTO botones_idiomas VALUES (57, 'Dar de baja', 108, 1);
INSERT INTO botones_idiomas VALUES (60, 'Listar', 126, 1);
INSERT INTO botones_idiomas VALUES (61, 'Dar de baja', 114, 1);
INSERT INTO botones_idiomas VALUES (62, 'Nuevo', 117, 1);
INSERT INTO botones_idiomas VALUES (63, 'Editar', 118, 1);
INSERT INTO botones_idiomas VALUES (64, 'Preguntas', 139, 1);
INSERT INTO botones_idiomas VALUES (65, 'Nuevo', 123, 1);
INSERT INTO botones_idiomas VALUES (66, 'Editar', 124, 1);
INSERT INTO botones_idiomas VALUES (67, 'Nuevo', 121, 1);
INSERT INTO botones_idiomas VALUES (68, 'Editar', 122, 1);
INSERT INTO botones_idiomas VALUES (69, 'Nuevo', 153, 1);
INSERT INTO botones_idiomas VALUES (70, 'Editar', 154, 1);
INSERT INTO botones_idiomas VALUES (71, 'Baja', 155, 1);
INSERT INTO botones_idiomas VALUES (72, 'Nuevo', 1, 1);
INSERT INTO botones_idiomas VALUES (73, 'Editar', 2, 1);
INSERT INTO botones_idiomas VALUES (74, 'Verificar', 3, 1);
INSERT INTO botones_idiomas VALUES (75, 'Ver Pdf', 140, 1);
INSERT INTO botones_idiomas VALUES (76, 'Cerrar Accion', 142, 1);
INSERT INTO botones_idiomas VALUES (77, 'Nuevo', 83, 1);
INSERT INTO botones_idiomas VALUES (78, 'Editar', 84, 1);
INSERT INTO botones_idiomas VALUES (79, 'Dar de Baja', 85, 1);
INSERT INTO botones_idiomas VALUES (80, 'Nuevo', 64, 1);
INSERT INTO botones_idiomas VALUES (81, 'Editar', 65, 1);
INSERT INTO botones_idiomas VALUES (82, 'Copiar', 66, 1);
INSERT INTO botones_idiomas VALUES (83, 'Hacer Vigente', 67, 1);
INSERT INTO botones_idiomas VALUES (84, 'Dar de Baja', 68, 1);
INSERT INTO botones_idiomas VALUES (85, 'Auditorias', 69, 1);
INSERT INTO botones_idiomas VALUES (86, 'Agregar', 70, 1);
INSERT INTO botones_idiomas VALUES (88, 'Dar de Baja', 72, 1);
INSERT INTO botones_idiomas VALUES (89, 'Estado/Detalles', 73, 1);
INSERT INTO botones_idiomas VALUES (90, 'Plan Auditoria', 74, 1);
INSERT INTO botones_idiomas VALUES (91, 'Equipo Auditor', 76, 1);
INSERT INTO botones_idiomas VALUES (92, 'Informe', 77, 1);
INSERT INTO botones_idiomas VALUES (93, 'Listar', 38, 1);
INSERT INTO botones_idiomas VALUES (94, 'Nuevo', 40, 1);
INSERT INTO botones_idiomas VALUES (95, 'Ver F.R.L.', 41, 1);
INSERT INTO botones_idiomas VALUES (96, 'Editar', 42, 1);
INSERT INTO botones_idiomas VALUES (97, 'Ver Ley', 43, 1);
INSERT INTO botones_idiomas VALUES (98, 'Cuestionario', 44, 1);
INSERT INTO botones_idiomas VALUES (99, 'Historico', 45, 1);
INSERT INTO botones_idiomas VALUES (100, 'Ver', 161, 1);
INSERT INTO botones_idiomas VALUES (101, 'Detalles', 162, 1);
INSERT INTO botones_idiomas VALUES (102, 'Nuevo', 29, 1);
INSERT INTO botones_idiomas VALUES (103, 'Ver', 30, 1);
INSERT INTO botones_idiomas VALUES (104, 'Detalles', 31, 1);
INSERT INTO botones_idiomas VALUES (105, 'Nuevo', 32, 1);
INSERT INTO botones_idiomas VALUES (106, 'Ver', 33, 1);
INSERT INTO botones_idiomas VALUES (107, 'Detalles', 34, 1);
INSERT INTO botones_idiomas VALUES (108, 'Nuevo', 150, 1);
INSERT INTO botones_idiomas VALUES (109, 'Ver', 151, 1);
INSERT INTO botones_idiomas VALUES (110, 'Detalles', 152, 1);
INSERT INTO botones_idiomas VALUES (111, 'Ver', 25, 1);
INSERT INTO botones_idiomas VALUES (112, 'Detalles', 26, 1);
INSERT INTO botones_idiomas VALUES (113, 'Ver', 27, 1);
INSERT INTO botones_idiomas VALUES (114, 'Detalles', 28, 1);
INSERT INTO botones_idiomas VALUES (115, 'Nuevo', 57, 1);
INSERT INTO botones_idiomas VALUES (116, 'Ver', 58, 1);
INSERT INTO botones_idiomas VALUES (117, 'Dar de Baja', 59, 1);
INSERT INTO botones_idiomas VALUES (118, 'Plan Mant.', 60, 1);
INSERT INTO botones_idiomas VALUES (119, 'Mant. Preventivo', 61, 1);
INSERT INTO botones_idiomas VALUES (120, 'Mant. Correctivo', 62, 1);
INSERT INTO botones_idiomas VALUES (121, 'Plan de Mantenimiento', 63, 1);
INSERT INTO botones_idiomas VALUES (122, 'Nuevo', 46, 1);
INSERT INTO botones_idiomas VALUES (123, 'Editar', 47, 1);
INSERT INTO botones_idiomas VALUES (124, 'Solicitar Plaza', 49, 1);
INSERT INTO botones_idiomas VALUES (125, 'Solicitar Baja', 50, 1);
INSERT INTO botones_idiomas VALUES (126, 'Nueva Revision', 165, 1);
INSERT INTO botones_idiomas VALUES (127, 'Nuevo', 166, 1);
INSERT INTO botones_idiomas VALUES (128, 'Editar', 167, 1);
INSERT INTO botones_idiomas VALUES (129, 'Dar de Baja', 168, 1);
INSERT INTO botones_idiomas VALUES (130, 'Dar de Baja', 48, 1);
INSERT INTO botones_idiomas VALUES (131, 'Dar de Baja', 6, 1);
INSERT INTO botones_idiomas VALUES (132, 'Nuevo', 158, 1);
INSERT INTO botones_idiomas VALUES (133, 'Nueva', 132, 1);
INSERT INTO botones_idiomas VALUES (134, 'Nuevo', 160, 1);
INSERT INTO botones_idiomas VALUES (135, 'Detalles', 134, 1);
INSERT INTO botones_idiomas VALUES (136, 'Ver', 135, 1);
INSERT INTO botones_idiomas VALUES (137, 'Nuevo', 170, 1);
INSERT INTO botones_idiomas VALUES (138, 'Detalles', 171, 1);
INSERT INTO botones_idiomas VALUES (139, 'Ver', 39, 1);
INSERT INTO botones_idiomas VALUES (140, 'Dar de alta', 172, 1);
INSERT INTO botones_idiomas VALUES (141, 'Nuevo', 173, 1);
INSERT INTO botones_idiomas VALUES (142, 'Nuevo', 174, 1);
INSERT INTO botones_idiomas VALUES (143, 'Ver', 175, 1);
INSERT INTO botones_idiomas VALUES (144, 'Detalles', 176, 1);
INSERT INTO botones_idiomas VALUES (145, 'Ver', 177, 1);
INSERT INTO botones_idiomas VALUES (146, 'Detalles', 178, 1);
INSERT INTO botones_idiomas VALUES (147, 'Editar', 159, 1);
INSERT INTO botones_idiomas VALUES (148, 'Revisar', 169, 1);
INSERT INTO botones_idiomas VALUES (149, 'Aprobar', 179, 1);
INSERT INTO botones_idiomas VALUES (150, 'Metas', 180, 1);
INSERT INTO botones_idiomas VALUES (151, 'Nueva version', 181, 1);
INSERT INTO botones_idiomas VALUES (152, 'Nuevo', 115, 1);
INSERT INTO botones_idiomas VALUES (153, 'Editar', 116, 1);
INSERT INTO botones_idiomas VALUES (154, 'Nuevo', 119, 1);
INSERT INTO botones_idiomas VALUES (155, 'Editar', 120, 1);
INSERT INTO botones_idiomas VALUES (311, 'Valores', 182, 1);
INSERT INTO botones_idiomas VALUES (313, 'Grafica', 183, 1);
INSERT INTO botones_idiomas VALUES (316, 'Nuevo', 185, 1);
INSERT INTO botones_idiomas VALUES (318, 'Editar', 186, 1);
INSERT INTO botones_idiomas VALUES (323, 'Nuevo', 187, 1);
INSERT INTO botones_idiomas VALUES (156, 'Nou', 1, 2);
INSERT INTO botones_idiomas VALUES (157, 'Editar', 2, 2);
INSERT INTO botones_idiomas VALUES (158, 'Verificar', 3, 2);
INSERT INTO botones_idiomas VALUES (159, 'Nou', 4, 2);
INSERT INTO botones_idiomas VALUES (160, 'Editar', 5, 2);
INSERT INTO botones_idiomas VALUES (161, 'Donar de Baixa', 6, 2);
INSERT INTO botones_idiomas VALUES (162, 'Veure Productes', 7, 2);
INSERT INTO botones_idiomas VALUES (163, 'Veure Contactes', 8, 2);
INSERT INTO botones_idiomas VALUES (164, 'Veure Incid�ncies', 9, 2);
INSERT INTO botones_idiomas VALUES (165, 'Nou', 10, 2);
INSERT INTO botones_idiomas VALUES (166, 'Veure', 11, 2);
INSERT INTO botones_idiomas VALUES (167, 'Editar', 12, 2);
INSERT INTO botones_idiomas VALUES (168, 'Nou', 13, 2);
INSERT INTO botones_idiomas VALUES (169, 'Veure', 14, 2);
INSERT INTO botones_idiomas VALUES (170, 'Editar', 15, 2);
INSERT INTO botones_idiomas VALUES (171, 'Nou', 16, 2);
INSERT INTO botones_idiomas VALUES (172, 'Veure', 17, 2);
INSERT INTO botones_idiomas VALUES (173, 'Editar', 18, 2);
INSERT INTO botones_idiomas VALUES (174, 'Criteris', 19, 2);
INSERT INTO botones_idiomas VALUES (175, 'Revisar', 20, 2);
INSERT INTO botones_idiomas VALUES (176, 'Historico', 21, 2);
INSERT INTO botones_idiomas VALUES (177, 'Veure', 22, 2);
INSERT INTO botones_idiomas VALUES (178, 'Editar', 23, 2);
INSERT INTO botones_idiomas VALUES (179, 'Donar de Baixa', 24, 2);
INSERT INTO botones_idiomas VALUES (180, 'Veure', 25, 2);
INSERT INTO botones_idiomas VALUES (181, 'Detalls', 26, 2);
INSERT INTO botones_idiomas VALUES (182, 'Veure', 27, 2);
INSERT INTO botones_idiomas VALUES (183, 'Detalls', 28, 2);
INSERT INTO botones_idiomas VALUES (184, 'Nou', 29, 2);
INSERT INTO botones_idiomas VALUES (185, 'Veure', 30, 2);
INSERT INTO botones_idiomas VALUES (186, 'Detalls', 31, 2);
INSERT INTO botones_idiomas VALUES (187, 'Nou', 32, 2);
INSERT INTO botones_idiomas VALUES (188, 'Veure', 33, 2);
INSERT INTO botones_idiomas VALUES (189, 'Detalls', 34, 2);
INSERT INTO botones_idiomas VALUES (190, 'Llistar', 38, 2);
INSERT INTO botones_idiomas VALUES (191, 'Veure', 39, 2);
INSERT INTO botones_idiomas VALUES (192, 'Nou', 40, 2);
INSERT INTO botones_idiomas VALUES (193, 'Veure F.R.L.', 41, 2);
INSERT INTO botones_idiomas VALUES (194, 'Editar', 42, 2);
INSERT INTO botones_idiomas VALUES (195, 'Veure Ley', 43, 2);
INSERT INTO botones_idiomas VALUES (196, 'Q�estionari', 44, 2);
INSERT INTO botones_idiomas VALUES (197, 'Historico', 45, 2);
INSERT INTO botones_idiomas VALUES (198, 'Nou', 46, 2);
INSERT INTO botones_idiomas VALUES (199, 'Editar', 47, 2);
INSERT INTO botones_idiomas VALUES (200, 'Donar de Baixa', 48, 2);
INSERT INTO botones_idiomas VALUES (201, 'Sol�licitar Pla�a', 49, 2);
INSERT INTO botones_idiomas VALUES (202, 'Sol�licitar Baixa', 50, 2);
INSERT INTO botones_idiomas VALUES (203, 'Assistents', 51, 2);
INSERT INTO botones_idiomas VALUES (204, 'Detalls', 52, 2);
INSERT INTO botones_idiomas VALUES (205, 'Nou', 53, 2);
INSERT INTO botones_idiomas VALUES (206, 'Editar', 54, 2);
INSERT INTO botones_idiomas VALUES (207, 'Cursos Pla', 55, 2);
INSERT INTO botones_idiomas VALUES (208, 'Fer Vigent', 56, 2);
INSERT INTO botones_idiomas VALUES (209, 'Nou', 57, 2);
INSERT INTO botones_idiomas VALUES (210, 'Veure', 58, 2);
INSERT INTO botones_idiomas VALUES (211, 'Donar de Baixa', 59, 2);
INSERT INTO botones_idiomas VALUES (212, 'Plan Mant.', 60, 2);
INSERT INTO botones_idiomas VALUES (213, 'Fer Vigent', 61, 2);
INSERT INTO botones_idiomas VALUES (214, 'Mant. Correctiu', 62, 2);
INSERT INTO botones_idiomas VALUES (215, 'Pla de Manteniment', 63, 2);
INSERT INTO botones_idiomas VALUES (216, 'Nou', 64, 2);
INSERT INTO botones_idiomas VALUES (217, 'Editar', 65, 2);
INSERT INTO botones_idiomas VALUES (218, 'Copiar', 66, 2);
INSERT INTO botones_idiomas VALUES (219, 'Fer Vigent', 67, 2);
INSERT INTO botones_idiomas VALUES (220, 'Donar de Baixa', 68, 2);
INSERT INTO botones_idiomas VALUES (221, 'Auditorias', 69, 2);
INSERT INTO botones_idiomas VALUES (222, 'Agregar', 70, 2);
INSERT INTO botones_idiomas VALUES (224, 'Donar de Baixa', 72, 2);
INSERT INTO botones_idiomas VALUES (225, 'Estat/Detalls', 73, 2);
INSERT INTO botones_idiomas VALUES (227, 'Equip Auditor', 76, 2);
INSERT INTO botones_idiomas VALUES (228, 'Informe', 77, 2);
INSERT INTO botones_idiomas VALUES (229, 'Nou', 79, 2);
INSERT INTO botones_idiomas VALUES (230, 'Editar', 80, 2);
INSERT INTO botones_idiomas VALUES (231, 'Matriu', 82, 2);
INSERT INTO botones_idiomas VALUES (232, 'Nou', 83, 2);
INSERT INTO botones_idiomas VALUES (233, 'Editar', 84, 2);
INSERT INTO botones_idiomas VALUES (234, 'Donar de Baixa', 85, 2);
INSERT INTO botones_idiomas VALUES (235, 'Nou', 86, 2);
INSERT INTO botones_idiomas VALUES (236, 'Editar', 87, 2);
INSERT INTO botones_idiomas VALUES (237, 'Detalls', 88, 2);
INSERT INTO botones_idiomas VALUES (238, 'Veure Fitxa', 89, 2);
INSERT INTO botones_idiomas VALUES (239, 'Veure Matriu', 90, 2);
INSERT INTO botones_idiomas VALUES (240, 'Nou', 91, 2);
INSERT INTO botones_idiomas VALUES (241, 'Donar de Baixa', 92, 2);
INSERT INTO botones_idiomas VALUES (242, 'Editar', 93, 2);
INSERT INTO botones_idiomas VALUES (243, 'Canviar Password', 94, 2);
INSERT INTO botones_idiomas VALUES (244, 'Nou', 95, 2);
INSERT INTO botones_idiomas VALUES (245, 'Donar de Baixa', 96, 2);
INSERT INTO botones_idiomas VALUES (246, 'Editar', 97, 2);
INSERT INTO botones_idiomas VALUES (249, 'Nou', 102, 2);
INSERT INTO botones_idiomas VALUES (250, 'Donar de Baixa', 103, 2);
INSERT INTO botones_idiomas VALUES (251, 'Editar', 104, 2);
INSERT INTO botones_idiomas VALUES (252, 'Historico', 105, 2);
INSERT INTO botones_idiomas VALUES (253, 'Donar de Baixa', 106, 2);
INSERT INTO botones_idiomas VALUES (254, 'Donar d''alta', 107, 2);
INSERT INTO botones_idiomas VALUES (255, 'Donar de Baixa', 108, 2);
INSERT INTO botones_idiomas VALUES (258, 'Donar de Baixa', 114, 2);
INSERT INTO botones_idiomas VALUES (259, 'Nou', 115, 2);
INSERT INTO botones_idiomas VALUES (260, 'Editar', 116, 2);
INSERT INTO botones_idiomas VALUES (261, 'Nou', 117, 2);
INSERT INTO botones_idiomas VALUES (262, 'Editar', 118, 2);
INSERT INTO botones_idiomas VALUES (263, 'Nou', 119, 2);
INSERT INTO botones_idiomas VALUES (264, 'Editar', 120, 2);
INSERT INTO botones_idiomas VALUES (265, 'Nou', 121, 2);
INSERT INTO botones_idiomas VALUES (266, 'Editar', 122, 2);
INSERT INTO botones_idiomas VALUES (267, 'Nou', 123, 2);
INSERT INTO botones_idiomas VALUES (268, 'Editar', 124, 2);
INSERT INTO botones_idiomas VALUES (269, 'Enviar Msj', 125, 2);
INSERT INTO botones_idiomas VALUES (270, 'Llistar', 126, 2);
INSERT INTO botones_idiomas VALUES (271, 'Copiar Perfil', 127, 2);
INSERT INTO botones_idiomas VALUES (272, 'Nou', 128, 2);
INSERT INTO botones_idiomas VALUES (273, 'Editar', 129, 2);
INSERT INTO botones_idiomas VALUES (274, 'Donar de Baixa', 130, 2);
INSERT INTO botones_idiomas VALUES (275, 'Indicadors', 131, 2);
INSERT INTO botones_idiomas VALUES (276, 'Nova', 132, 2);
INSERT INTO botones_idiomas VALUES (277, 'Detalls', 134, 2);
INSERT INTO botones_idiomas VALUES (278, 'Veure', 135, 2);
INSERT INTO botones_idiomas VALUES (279, 'Preguntes', 139, 2);
INSERT INTO botones_idiomas VALUES (280, 'Veure Pdf', 140, 2);
INSERT INTO botones_idiomas VALUES (281, 'Tancar Accion', 142, 2);
INSERT INTO botones_idiomas VALUES (282, 'Eliminar', 146, 2);
INSERT INTO botones_idiomas VALUES (283, 'Nou', 150, 2);
INSERT INTO botones_idiomas VALUES (284, 'Veure', 151, 2);
INSERT INTO botones_idiomas VALUES (285, 'Detalls', 152, 2);
INSERT INTO botones_idiomas VALUES (286, 'Nou', 153, 2);
INSERT INTO botones_idiomas VALUES (287, 'Editar', 154, 2);
INSERT INTO botones_idiomas VALUES (288, 'Baixa', 155, 2);
INSERT INTO botones_idiomas VALUES (289, 'Nou', 158, 2);
INSERT INTO botones_idiomas VALUES (290, 'Editar', 159, 2);
INSERT INTO botones_idiomas VALUES (291, 'Nou', 160, 2);
INSERT INTO botones_idiomas VALUES (292, 'Veure', 161, 2);
INSERT INTO botones_idiomas VALUES (293, 'Detalls', 162, 2);
INSERT INTO botones_idiomas VALUES (294, 'Nova Revision', 165, 2);
INSERT INTO botones_idiomas VALUES (295, 'Nou', 166, 2);
INSERT INTO botones_idiomas VALUES (296, 'Editar', 167, 2);
INSERT INTO botones_idiomas VALUES (297, 'Donar de Baixa', 168, 2);
INSERT INTO botones_idiomas VALUES (298, 'Revisar', 169, 2);
INSERT INTO botones_idiomas VALUES (299, 'Nou', 170, 2);
INSERT INTO botones_idiomas VALUES (300, 'Detalls', 171, 2);
INSERT INTO botones_idiomas VALUES (301, 'Donar d''alta', 172, 2);
INSERT INTO botones_idiomas VALUES (302, 'Nou', 173, 2);
INSERT INTO botones_idiomas VALUES (303, 'Nou', 174, 2);
INSERT INTO botones_idiomas VALUES (304, 'Veure', 175, 2);
INSERT INTO botones_idiomas VALUES (305, 'Detalls', 176, 2);
INSERT INTO botones_idiomas VALUES (306, 'Veure', 177, 2);
INSERT INTO botones_idiomas VALUES (307, 'Detalls', 178, 2);
INSERT INTO botones_idiomas VALUES (308, 'Aprovar', 179, 2);
INSERT INTO botones_idiomas VALUES (309, 'Metes', 180, 2);
INSERT INTO botones_idiomas VALUES (310, 'Nova version', 181, 2);
INSERT INTO botones_idiomas VALUES (312, 'Valors ', 182, 2);
INSERT INTO botones_idiomas VALUES (314, 'Grafica', 183, 2);
INSERT INTO botones_idiomas VALUES (317, 'Editar', 186, 2);
INSERT INTO botones_idiomas VALUES (324, 'Nou', 187, 2);
INSERT INTO botones_idiomas VALUES (325, 'Editar', 188, 1);
INSERT INTO botones_idiomas VALUES (326, 'Editar', 188, 2);
INSERT INTO botones_idiomas VALUES (327, 'Botones', 189, 1);
INSERT INTO botones_idiomas VALUES (328, 'Botons', 189, 2);
INSERT INTO botones_idiomas VALUES (329, 'Estructura', 190, 1);
INSERT INTO botones_idiomas VALUES (330, 'Estructura', 190, 2);
INSERT INTO botones_idiomas VALUES (331, 'Idiomas', 191, 1);
INSERT INTO botones_idiomas VALUES (332, 'Idiomes', 191, 2);
INSERT INTO botones_idiomas VALUES (333, 'Nuevo', 192, 1);
INSERT INTO botones_idiomas VALUES (334, 'Nou', 192, 2);
INSERT INTO botones_idiomas VALUES (335, 'Horario Auditorias', 193, 1);
INSERT INTO botones_idiomas VALUES (336, 'Horari Auditorias', 193, 2);
INSERT INTO botones_idiomas VALUES (337, 'Permisos', 194, 1);
INSERT INTO botones_idiomas VALUES (338, 'Permisos', 194, 2);
INSERT INTO botones_idiomas VALUES (339, 'Metas', 195, 1);
INSERT INTO botones_idiomas VALUES (340, 'Metes', 195, 2);
INSERT INTO botones_idiomas VALUES (315, 'Nou', 185, 2);
INSERT INTO botones_idiomas VALUES (226, 'Pla auditoria', 74, 2);
INSERT INTO botones_idiomas VALUES (341, 'Permisos', 196, 1);
INSERT INTO botones_idiomas VALUES (342, 'Permisos', 196, 2);
INSERT INTO botones_idiomas VALUES (343, 'Permisos', 197, 1);
INSERT INTO botones_idiomas VALUES (344, 'Permisos', 197, 2);
INSERT INTO botones_idiomas VALUES (347, 'Permisos', 199, 1);
INSERT INTO botones_idiomas VALUES (348, 'Permisos', 199, 2);
INSERT INTO botones_idiomas VALUES (349, 'Permisos', 200, 1);
INSERT INTO botones_idiomas VALUES (350, 'Permisos', 200, 2);
INSERT INTO botones_idiomas VALUES (351, 'Permisos', 201, 1);
INSERT INTO botones_idiomas VALUES (352, 'Permisos', 201, 2);
INSERT INTO botones_idiomas VALUES (353, 'Permisos', 202, 1);
INSERT INTO botones_idiomas VALUES (354, 'Permisos', 202, 2);
INSERT INTO botones_idiomas VALUES (355, 'Permisos', 203, 1);
INSERT INTO botones_idiomas VALUES (356, 'Permisos', 203, 2);
INSERT INTO botones_idiomas VALUES (357, 'Nuevo', 204, 1);
INSERT INTO botones_idiomas VALUES (358, 'Nou', 204, 2);
INSERT INTO botones_idiomas VALUES (359, 'Nuevo', 205, 1);
INSERT INTO botones_idiomas VALUES (360, 'Nou', 205, 2);
INSERT INTO botones_idiomas VALUES (361, 'Nuevo', 206, 1);
INSERT INTO botones_idiomas VALUES (362, 'Nou', 206, 2);
INSERT INTO botones_idiomas VALUES (365, 'Editar', 208, 1);
INSERT INTO botones_idiomas VALUES (366, 'Editar', 208, 2);
INSERT INTO botones_idiomas VALUES (367, 'Editar', 209, 1);
INSERT INTO botones_idiomas VALUES (368, 'Editar', 209, 2);
INSERT INTO botones_idiomas VALUES (369, 'Editar', 210, 1);
INSERT INTO botones_idiomas VALUES (370, 'Editar', 210, 2);
INSERT INTO botones_idiomas VALUES (377, 'Editar', 213, 1);
INSERT INTO botones_idiomas VALUES (378, 'Editar', 213, 2);
INSERT INTO botones_idiomas VALUES (379, 'Estadisticas', 214, 1);
INSERT INTO botones_idiomas VALUES (380, 'Estadisticas', 214, 2);
INSERT INTO botones_idiomas VALUES (381, 'Ver Pdf', 215, 1);
INSERT INTO botones_idiomas VALUES (382, 'Veure Pdf', 215, 2);
INSERT INTO botones_idiomas VALUES (383, 'Nuevo', 216, 1);
INSERT INTO botones_idiomas VALUES (384, 'Nou', 216, 2);
INSERT INTO botones_idiomas VALUES (385, 'Nuevo', 217, 1);
INSERT INTO botones_idiomas VALUES (386, 'Nou', 217, 2);
INSERT INTO botones_idiomas VALUES (387, 'Editar', 218, 1);
INSERT INTO botones_idiomas VALUES (388, 'Editar', 218, 2);
INSERT INTO botones_idiomas VALUES (389, 'Editar', 219, 1);
INSERT INTO botones_idiomas VALUES (390, 'Editar', 219, 2);
INSERT INTO botones_idiomas VALUES (391, 'Aprobar', 222, 1);
INSERT INTO botones_idiomas VALUES (392, 'Aprovar', 222, 2);
INSERT INTO botones_idiomas VALUES (393, 'Revisar', 223, 1);
INSERT INTO botones_idiomas VALUES (394, 'Revisar', 223, 2);
INSERT INTO botones_idiomas VALUES (401, 'Nuevo', 224, 1);
INSERT INTO botones_idiomas VALUES (402, 'Nou', 224, 2);
INSERT INTO botones_idiomas VALUES (403, 'Dar de baja', 225, 1);
INSERT INTO botones_idiomas VALUES (404, 'Donar de Baixa', 225, 2);
INSERT INTO botones_idiomas VALUES (405, 'Editar', 226, 1);
INSERT INTO botones_idiomas VALUES (406, 'Editar', 226, 2);
INSERT INTO botones_idiomas VALUES (407, 'Nuevo', 227, 1);
INSERT INTO botones_idiomas VALUES (408, 'Nou', 227, 2);
INSERT INTO botones_idiomas VALUES (409, 'Ver', 228, 1);
INSERT INTO botones_idiomas VALUES (410, 'Veure', 228, 2);
INSERT INTO botones_idiomas VALUES (411, 'Detalles', 229, 1);
INSERT INTO botones_idiomas VALUES (412, 'Detalls', 229, 2);
INSERT INTO botones_idiomas VALUES (413, 'Permisos', 230, 1);
INSERT INTO botones_idiomas VALUES (414, 'Permisos', 230, 2);
INSERT INTO botones_idiomas VALUES (415, 'Nuevo', 231, 1);
INSERT INTO botones_idiomas VALUES (416, 'Nou', 231, 2);
INSERT INTO botones_idiomas VALUES (417, 'Editar', 232, 1);
INSERT INTO botones_idiomas VALUES (418, 'Editar', 232, 2);
INSERT INTO botones_idiomas VALUES (419, 'Seguimiento', 233, 1);
INSERT INTO botones_idiomas VALUES (420, 'Seguiment', 233, 2);
INSERT INTO botones_idiomas VALUES (421, 'Permisos', 234, 1);
INSERT INTO botones_idiomas VALUES (422, 'Permisos', 234, 2);
INSERT INTO botones_idiomas VALUES (87, 'Editar', 75, 1);
INSERT INTO botones_idiomas VALUES (223, 'Editar', 75, 2);
INSERT INTO botones_idiomas VALUES (425, 'Nuevo', 235, 1);
INSERT INTO botones_idiomas VALUES (426, 'Nou', 235, 2);
INSERT INTO botones_idiomas VALUES (427, 'Eliminar', 236, 1);
INSERT INTO botones_idiomas VALUES (428, 'Eliminar', 236, 2);
INSERT INTO botones_idiomas VALUES (429, 'Generar Hospital', 237, 1);
INSERT INTO botones_idiomas VALUES (430, 'Generar Hospital', 237, 2);
INSERT INTO botones_idiomas VALUES (431, 'Editar', 238, 1);
INSERT INTO botones_idiomas VALUES (432, 'Editar', 238, 2);
INSERT INTO botones_idiomas VALUES (433, 'Ver Pdf', 239, 1);
INSERT INTO botones_idiomas VALUES (434, 'Veure Pdf', 239, 2);
INSERT INTO botones_idiomas VALUES (435, 'Idioma', 240, 1);
INSERT INTO botones_idiomas VALUES (436, 'Idioma', 240, 2);
INSERT INTO botones_idiomas VALUES (437, 'Idioma', 241, 1);
INSERT INTO botones_idiomas VALUES (438, 'Idioma', 241, 2);
INSERT INTO botones_idiomas VALUES (439, 'Idioma', 242, 1);
INSERT INTO botones_idiomas VALUES (440, 'Idioma', 242, 2);
INSERT INTO botones_idiomas VALUES (441, 'Idioma', 243, 1);
INSERT INTO botones_idiomas VALUES (442, 'Idioma', 243, 2);
INSERT INTO botones_idiomas VALUES (443, 'Idioma', 244, 1);
INSERT INTO botones_idiomas VALUES (444, 'Idioma', 244, 2);
INSERT INTO botones_idiomas VALUES (445, 'Idioma', 245, 1);
INSERT INTO botones_idiomas VALUES (446, 'Idioma', 245, 2);
INSERT INTO botones_idiomas VALUES (447, 'Idioma', 246, 1);
INSERT INTO botones_idiomas VALUES (448, 'Idioma', 246, 2);
INSERT INTO botones_idiomas VALUES (449, 'Idioma', 247, 1);
INSERT INTO botones_idiomas VALUES (450, 'Idioma', 247, 2);
INSERT INTO botones_idiomas VALUES (451, 'Idioma', 248, 1);
INSERT INTO botones_idiomas VALUES (452, 'Idioma', 248, 2);
INSERT INTO botones_idiomas VALUES (453, 'Bajar xls', 249, 1);
INSERT INTO botones_idiomas VALUES (454, 'Baixar xls', 249, 2);
INSERT INTO botones_idiomas VALUES (455, 'Bajar xls', 250, 1);
INSERT INTO botones_idiomas VALUES (456, 'Baixar xls', 250, 2);
INSERT INTO botones_idiomas VALUES (457, 'Bajar xls', 251, 1);
INSERT INTO botones_idiomas VALUES (458, 'Baixar xls', 251, 2);
INSERT INTO botones_idiomas VALUES (459, 'Bajar xls', 252, 1);
INSERT INTO botones_idiomas VALUES (460, 'Baixar xls', 252, 2);
INSERT INTO botones_idiomas VALUES (461, 'Bajar xls', 253, 1);
INSERT INTO botones_idiomas VALUES (462, 'Baixar xls', 253, 2);
INSERT INTO botones_idiomas VALUES (463, 'Bajar xls', 254, 1);
INSERT INTO botones_idiomas VALUES (464, 'Baixar xls', 254, 2);
INSERT INTO botones_idiomas VALUES (465, 'Bajar xls', 255, 1);
INSERT INTO botones_idiomas VALUES (466, 'Baixar xls', 255, 2);
INSERT INTO botones_idiomas VALUES (467, 'Bajar xls', 256, 1);
INSERT INTO botones_idiomas VALUES (468, 'Baixar xls', 256, 2);
INSERT INTO botones_idiomas VALUES (469, 'Bajar xls', 257, 1);
INSERT INTO botones_idiomas VALUES (470, 'Baixar xls', 257, 2);
INSERT INTO botones_idiomas VALUES (471, 'Bajar xls', 258, 1);
INSERT INTO botones_idiomas VALUES (472, 'Baixar xls', 258, 2);
INSERT INTO botones_idiomas VALUES (473, 'Bajar xls', 259, 1);
INSERT INTO botones_idiomas VALUES (474, 'Baixar xls', 259, 2);
INSERT INTO botones_idiomas VALUES (475, 'Bajar xls', 260, 1);
INSERT INTO botones_idiomas VALUES (476, 'Baixar xls', 260, 2);
INSERT INTO botones_idiomas VALUES (477, 'Bajar xls', 261, 1);
INSERT INTO botones_idiomas VALUES (478, 'Baixar xls', 261, 2);
INSERT INTO botones_idiomas VALUES (479, 'Bajar xls', 262, 1);
INSERT INTO botones_idiomas VALUES (480, 'Baixar xls', 262, 2);
INSERT INTO botones_idiomas VALUES (481, 'Bajar xls', 263, 1);
INSERT INTO botones_idiomas VALUES (482, 'Baixar xls', 263, 2);
INSERT INTO botones_idiomas VALUES (483, 'Bajar xls', 264, 1);
INSERT INTO botones_idiomas VALUES (484, 'Baixar xls', 264, 2);
INSERT INTO botones_idiomas VALUES (485, 'Bajar xls', 265, 1);
INSERT INTO botones_idiomas VALUES (486, 'Baixar xls', 265, 2);
INSERT INTO botones_idiomas VALUES (487, 'Bajar xls', 266, 1);
INSERT INTO botones_idiomas VALUES (488, 'Baixar xls', 266, 2);
INSERT INTO botones_idiomas VALUES (489, 'Bajar xls', 267, 1);
INSERT INTO botones_idiomas VALUES (490, 'Baixar xls', 267, 2);
INSERT INTO botones_idiomas VALUES (491, 'Bajar xls', 268, 1);
INSERT INTO botones_idiomas VALUES (492, 'Baixar xls', 268, 2);
INSERT INTO botones_idiomas VALUES (493, 'Bajar xls', 269, 1);
INSERT INTO botones_idiomas VALUES (494, 'Baixar xls', 269, 2);
INSERT INTO botones_idiomas VALUES (495, 'Bajar xls', 270, 1);
INSERT INTO botones_idiomas VALUES (496, 'Baixar xls', 270, 2);
INSERT INTO botones_idiomas VALUES (497, 'Bajar xls', 271, 1);
INSERT INTO botones_idiomas VALUES (498, 'Baixar xls', 271, 2);
INSERT INTO botones_idiomas VALUES (499, 'Bajar xls', 272, 1);
INSERT INTO botones_idiomas VALUES (500, 'Baixar xls', 272, 2);
INSERT INTO botones_idiomas VALUES (501, 'Bajar xls', 273, 1);
INSERT INTO botones_idiomas VALUES (502, 'Baixar xls', 273, 2);
INSERT INTO botones_idiomas VALUES (503, 'Bajar xls', 274, 1);
INSERT INTO botones_idiomas VALUES (504, 'Baixar xls', 274, 2);
INSERT INTO botones_idiomas VALUES (505, 'Bajar xls', 275, 1);
INSERT INTO botones_idiomas VALUES (506, 'Baixar xls', 275, 2);
INSERT INTO botones_idiomas VALUES (507, 'Bajar xls', 276, 1);
INSERT INTO botones_idiomas VALUES (508, 'Baixar xls', 276, 2);
INSERT INTO botones_idiomas VALUES (509, 'Bajar xls', 277, 1);
INSERT INTO botones_idiomas VALUES (510, 'Baixar xls', 277, 2);
INSERT INTO botones_idiomas VALUES (511, 'Bajar xls', 278, 1);
INSERT INTO botones_idiomas VALUES (512, 'Baixar xls', 278, 2);
INSERT INTO botones_idiomas VALUES (513, 'Bajar xls', 279, 1);
INSERT INTO botones_idiomas VALUES (514, 'Baixar xls', 279, 2);
INSERT INTO botones_idiomas VALUES (515, 'Bajar xls', 280, 1);
INSERT INTO botones_idiomas VALUES (516, 'Baixar xls', 280, 2);
INSERT INTO botones_idiomas VALUES (517, 'Bajar xls', 281, 1);
INSERT INTO botones_idiomas VALUES (518, 'Baixar xls', 281, 2);
INSERT INTO botones_idiomas VALUES (519, 'Bajar xls', 282, 1);
INSERT INTO botones_idiomas VALUES (520, 'Baixar xls', 282, 2);
INSERT INTO botones_idiomas VALUES (521, 'Bajar xls', 283, 1);
INSERT INTO botones_idiomas VALUES (522, 'Baixar xls', 283, 2);
INSERT INTO botones_idiomas VALUES (523, 'Bajar xls', 284, 1);
INSERT INTO botones_idiomas VALUES (524, 'Baixar xls', 284, 2);
INSERT INTO botones_idiomas VALUES (525, 'Bajar xls', 285, 1);
INSERT INTO botones_idiomas VALUES (526, 'Baixar xls', 285, 2);
INSERT INTO botones_idiomas VALUES (527, 'Bajar xls', 286, 1);
INSERT INTO botones_idiomas VALUES (528, 'Baixar xls', 286, 2);
INSERT INTO botones_idiomas VALUES (529, 'Bajar xls', 287, 1);
INSERT INTO botones_idiomas VALUES (530, 'Baixar xls', 287, 2);
INSERT INTO botones_idiomas VALUES (531, 'Bajar xls', 288, 1);
INSERT INTO botones_idiomas VALUES (532, 'Baixar xls', 288, 2);
INSERT INTO botones_idiomas VALUES (533, 'Bajar xls', 289, 1);
INSERT INTO botones_idiomas VALUES (534, 'Baixar xls', 289, 2);
INSERT INTO botones_idiomas VALUES (535, 'Bajar xls', 290, 1);
INSERT INTO botones_idiomas VALUES (536, 'Baixar xls', 290, 2);
INSERT INTO botones_idiomas VALUES (537, 'Bajar xls', 291, 1);
INSERT INTO botones_idiomas VALUES (538, 'Baixar xls', 291, 2);
INSERT INTO botones_idiomas VALUES (539, 'Baixar xls', 292, 2);
INSERT INTO botones_idiomas VALUES (540, 'Bajar xls', 292, 1);
INSERT INTO botones_idiomas VALUES (541, 'Bajar xls', 293, 1);
INSERT INTO botones_idiomas VALUES (542, 'Baixar xls', 293, 2);
INSERT INTO botones_idiomas VALUES (543, 'Bajar xls', 294, 1);
INSERT INTO botones_idiomas VALUES (544, 'Baixar xls', 294, 2);
INSERT INTO botones_idiomas VALUES (545, 'Bajar xls', 295, 1);
INSERT INTO botones_idiomas VALUES (546, 'Baixar xls', 295, 2);
INSERT INTO botones_idiomas VALUES (547, 'Bajar xls', 296, 1);
INSERT INTO botones_idiomas VALUES (548, 'Baixar xls', 296, 2);
INSERT INTO botones_idiomas VALUES (549, 'Dar de Baja', 297, 1);
INSERT INTO botones_idiomas VALUES (550, 'Donar de Baixa', 297, 2);
INSERT INTO botones_idiomas VALUES (553, 'Dar de baja', 298, 1);
INSERT INTO botones_idiomas VALUES (554, 'Donar de baixa', 298, 2);
INSERT INTO botones_idiomas VALUES (555, 'Editar', 299, 1);
INSERT INTO botones_idiomas VALUES (556, 'Editar', 299, 2);
INSERT INTO botones_idiomas VALUES (557, 'Dar de baja', 300, 1);
INSERT INTO botones_idiomas VALUES (558, 'Donar de baixa', 300, 2);
INSERT INTO botones_idiomas VALUES (559, 'Dar de alta', 301, 1);
INSERT INTO botones_idiomas VALUES (560, 'Donar d''alta', 301, 2);
INSERT INTO botones_idiomas VALUES (561, 'Dar de baja', 302, 1);
INSERT INTO botones_idiomas VALUES (562, 'Donar de baixa', 302, 2);
INSERT INTO botones_idiomas VALUES (563, 'Dar de alta', 303, 1);
INSERT INTO botones_idiomas VALUES (564, 'Donar d''alta', 303, 2);
INSERT INTO botones_idiomas VALUES (565, 'Dar de baja', 304, 1);
INSERT INTO botones_idiomas VALUES (566, 'Donar de baixa', 304, 2);
INSERT INTO botones_idiomas VALUES (567, 'Dar de alta', 305, 1);
INSERT INTO botones_idiomas VALUES (568, 'Donar d''alta', 305, 2);
INSERT INTO botones_idiomas VALUES (569, 'Dar de baja', 306, 1);
INSERT INTO botones_idiomas VALUES (570, 'Donar de baixa', 306, 2);
INSERT INTO botones_idiomas VALUES (571, 'Dar de alta', 307, 1);
INSERT INTO botones_idiomas VALUES (572, 'Donar d''alta', 307, 2);
INSERT INTO botones_idiomas VALUES (573, 'Dar de baja', 308, 1);
INSERT INTO botones_idiomas VALUES (574, 'Donar de baixa', 308, 2);
INSERT INTO botones_idiomas VALUES (575, 'Dar de alta', 309, 1);
INSERT INTO botones_idiomas VALUES (576, 'Donar d''alta', 309, 2);
INSERT INTO botones_idiomas VALUES (577, 'Dar de baja', 310, 1);
INSERT INTO botones_idiomas VALUES (578, 'Donar de baixa', 310, 2);
INSERT INTO botones_idiomas VALUES (579, 'Dar de alta', 311, 1);
INSERT INTO botones_idiomas VALUES (580, 'Donar d''alta', 311, 2);
INSERT INTO botones_idiomas VALUES (581, 'Dar de baja', 312, 1);
INSERT INTO botones_idiomas VALUES (582, 'Donar de baixa', 312, 2);
INSERT INTO botones_idiomas VALUES (583, 'Dar de alta', 313, 1);
INSERT INTO botones_idiomas VALUES (584, 'Donar d''alta', 313, 2);
INSERT INTO botones_idiomas VALUES (585, 'Dar de baja', 314, 1);
INSERT INTO botones_idiomas VALUES (586, 'Donar de baixa', 314, 2);
INSERT INTO botones_idiomas VALUES (587, 'Dar de alta', 315, 1);
INSERT INTO botones_idiomas VALUES (588, 'Donar d''alta', 315, 2);
INSERT INTO botones_idiomas VALUES (589, 'Dar de Alta', 316, 1);
INSERT INTO botones_idiomas VALUES (590, 'Donar de Alta', 316, 2);

--
-- Data for Name: clientes; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: contactos_proveedores; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: contenido_adjunto; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: contenido_binario; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: contenido_procesos; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: contenido_texto; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: criterios_homologacion; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: cursos; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: division_ayuda; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO division_ayuda VALUES (199, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Inicio--><br />Mensajes-->Listado</b><br />
En esta secci� el usuario ver� en la página una lista con los mensajes que ha recibido. Estos mensajes pueden ser generados de dos formas: por los usuarios desde el m�dulo de formaci�n o por el administrador desde el m�dulo de mantenimiento.
Siguiendo con la descripci�n del m�dulo, en esta página de �Mensajes�, el usuario ver� que aparecen habilitados dos botones: �Enviar Mensaje� e �Histórico�. Sin embargo, si  el usuario selecciona cualquier mensaje de la lista, podr� \\"Eliminar� y �Ver� los mensajes.</p>',
                                   NULL, 1);
INSERT INTO division_ayuda VALUES (200, 2, 'Ajuda<br />

Inici-->
Missatges-->Llistat<br />
En aquesta secci� l''usuari veur� en la página una llista amb els missatges que ha rebut. Aquests missatges poden ser generats de dues formes: pels usuaris des del m�dul de formaci� o per l''administrador des del m�dul de manteniment. Seguint amb la descripci� del m�dul, en aquesta página de �Missatges�, l''usuari veur� que apareixen habilitats dos botons: �Enviar Missatge� i �Hist�ric�. No obstant aix�, si l''usuari selecciona qualsevol missatge de la llista, podr� "Eliminar� i �Veure� els missatges.',
                                   NULL, 1);
INSERT INTO division_ayuda VALUES (201, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Inicio--><br />Mensajes-->Tareas</b><br />
Desde esta seccion se muestra un listado con las tareas a realizar enviadas por otros usuarios. En dicho listado aparece el nombre del usuario que ha enviado la tarea, junto con el nombre de la tarea a realizar y el nombre del documento en el que se aplica.
Estas tareas son generadas por los usuarios desde el m�dulo de documentos.', NULL, 2);
INSERT INTO division_ayuda VALUES (202, 2,
                                   E'Ajuda<br /> Inici--> <br />Missatges--><br />Tasques Des d\\''aquesta seccion es mostra un llistat amb les tasques a realitzar enviades per altres usuaris. En dita llistada apareix el nom de l\\''usuari que ha enviat la tasca, juntament amb el nom de la tasca a realitzar i el nom del document en el qual s\\''aplica. Aquestes tasques s�n generades pels usuaris des del m�dul de documents. ',
                                   NULL, 2);
INSERT INTO division_ayuda VALUES (203, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Documentacion--><br />Documentos S.G.-->Manual</b><br />
Desde este modulo se puede acceder a toda la documentacion, registros y normativas de la empresa que se encuentren disponibles. Se pueden realizar operaciones como crear, ver, editar, revisar, aprobar documentos, y acceder a un registro con un listado de los mismos.',
                                   NULL, 3);
INSERT INTO division_ayuda VALUES (204, 2,
                                   E'<b>Ajuda <br />Documentacion--><br /> Documents S.G.--><br />Manual</b><br /> Des d\\''aquest modulo es pot accedir a tota la documentacion, registres i normatives de l\\''empresa que es trobin disponibles. Es poden realitzar operacions com crear, veure, editar, revisar, aprovar documents, i accedir a un registre amb un llistat dels mateixos. ',
                                   NULL, 3);
INSERT INTO division_ayuda VALUES (205, 1, '<br /><b>Ayuda</b><br />
<p align="left"><b>Documentacion--><br />Documentos S.G.--><br />Politica</b><br />
En esta seccion se muestra un listado con los documentos de politica, donde se puede insertar nuevos, ver y dar permisos.</p>',
                                   NULL, 4);
INSERT INTO division_ayuda VALUES (206, 2, '<br /><b>Ajuda</b><br />
<p align="left"><b>Documentacio--><br />Documets S.G.--><br />Politica</b><br />
En aquesta seccion es mostra un llistat amb els documents de politica, on es pot inserir nous, veure i donar permisos.</p>',
                                   NULL, 4);
INSERT INTO division_ayuda VALUES (207, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Documentacion--><br />Documentos S.G.--><br />Objetivos</b><br />
En esta seccion se muestra un listado con la documentacion de objetivos, donde se puede insertar nuevos, aprobar (si se tiene permiso), cambiar las metas, revisar y realizar busquedas.</p>',
                                   NULL, 5);
INSERT INTO division_ayuda VALUES (208, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Documentacio--><br />Documets S.G.--><br />Objectius</b><br />
En aquesta seccion es mostra un llistat amb la documentacion d\\''objectius, on es pot inserir nous, aprovar (si es t� perm�s), canviar les metes, revisar i realitzar busquedas.</p>',
                                   NULL, 5);
INSERT INTO division_ayuda VALUES (211, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Documentacion--><br />Documentos S.G.--><br />P.O</b><br />
En esta seccion se muestra un listado con la documentacion de procedimientos operativos, donde se puede insertar nuevos, aprobar (si se tiene permiso), cambiar las metas, revisar y realizar busquedas.</p>',
                                   NULL, 8);
INSERT INTO division_ayuda VALUES (212, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Documentacio--><br />Documets S.G.--><br />P.O.</b><br />
En aquesta seccion es mostra un llistat amb la documentacion de procediments operatius, on es pot inserir nous, aprovar (si es t� perm�s), canviar les metes, revisar i realitzar busquedas.</p>',
                                   NULL, 8);
INSERT INTO division_ayuda VALUES (209, 1, '<br /><b>Ayuda</b><br />
<p align="left"><b>Documentacion--><br />Documentos S.G.--><br />P.G</b><br />
En esta seccion se muestra un listado con la documentacion de procedimientos geerales, donde se puede insertar nuevos, aprobar (si se tiene permiso), cambiar las metas, revisar y realizar busquedas.</p>',
                                   NULL, 7);
INSERT INTO division_ayuda VALUES (210, 2, '<br /><b>Ajuda</b><br />
<p align="left"><b>Documentacio--><br />Documets S.G.--><br />P.G.</b><br />
En aquesta seccion es mostra un llistat amb la documentacion de procediments geerales, on es pot inserir nous, aprovar (si es t� perm�s), canviar les metes, revisar i realitzar busquedas.</p>',
                                   NULL, 7);
INSERT INTO division_ayuda VALUES (213, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Documentacion--><br />Legislacion--><br />Listado</b><br />
En esta seccion se muestra un listado con la documentacion de la Legislacion, donde se puede insertar nuevos, editar, ambiar permisos y realizar busquedas.</p>',
                                   NULL, 9);
INSERT INTO division_ayuda VALUES (214, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Documentacio--><br />Legislacio--><br />Llistat</b><br />
En aquesta seccion es mostra un llistat amb la documentacion de la Legislacion, on es pot inserir nous, editar, ambiar permisos i realitzar busquedas.</p>',
                                   NULL, 9);
INSERT INTO division_ayuda VALUES (215, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Documentacion--><br />Documentos S.G.--><br />Doc.Vigor</b><br />
En esta secci�n se muestra un listado con los documentos en vigor (con estado vigente) disponibles en la aplicación. Este tipo de documentos pueden ser desde procedimientos generales a instrucciones t�cnicas o manuales. Los documentos se muestran en un listado seg�n su código, nombre, fecha de revisi�n y tipo. Se puede realizar b�squedas y ver en detalle.</p>',
                                   NULL, 10);
INSERT INTO division_ayuda VALUES (216, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Documentacio--><br />Documents S.G.--><br />Doc.Vigor</b><br />
En aquesta secci� es mostra un llistat amb els documents en vigor (amb estat vigent) disponibles en l\\''aplicaci�. Aquest tipus de documents poden ser des de procediments generals a instruccions t�cniques o manuals. Els documents es mostren en un llistat segons el seu codi, nom, data de revisi� i tipus. Es pot realitzar recerques i veure en detall.</p>',
                                   NULL, 10);
INSERT INTO division_ayuda VALUES (217, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Documentacion--><br />Documentos Asociados--><br />Registros</b><br />
Aquí se muestra un listado de todos los tipos disponibles de registros seg�n su nombre. Al seleccionar un elemento del listado (uno y solo uno), aparecer� bajo el mismo el bot�n �Listar�. Pulsando este bot�n, aparecer� otro listado similar al anterior en el que se muestran, seg�n su código, nombre, fecha de revisi�n y versi�n, el n�mero de documentos de ese tipo que hay registrados.</p>',
                                   NULL, 11);
INSERT INTO division_ayuda VALUES (218, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Documentacio--><br />Documents Asociats--><br />Registros</b><br />
Aquí es mostra un llistat de tots els tipus disponibles de registres segons el seu nom. AL seleccionar un element del llistat (u i nom�s u), apareixer� sota el mateix el bot� �Llistar�. Prement aquest bot�, apareixer� altre llistat similar a l\\''anterior en el qual es mostren, segons el seu codi, nom, data de revisi� i versi�, el nombre de documents d\\''aquest tipus que hi ha registrats.</p>',
                                   NULL, 11);
INSERT INTO division_ayuda VALUES (219, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Documentacion--><br />Legislación--><br />Listado</b><br />
Esta secci�n muestra un listado con la legislaci�n aplicable por la empresa seg�n el t�tulo, el nombre del texto en donde se encuentra el decreto ley, su código de ficha, en qu� �rea incide, su �mbito se aplicación y si se cumple o no dicha ley.</p>',
                                   NULL, 12);
INSERT INTO division_ayuda VALUES (220, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Documentacio--><br />Legislaci�--><br />Llistat</b><br />
Aquesta secci� mostra un llistat amb la legislaci� aplicable per l\\''empresa segons el t�tol, el nom del text on es troba el decret llei, el seu codi de fitxa, en quina �rea incideix, el seu �mbit s\\''aplicaci� i si es complix o no aquesta llei.</p>',
                                   NULL, 12);
INSERT INTO division_ayuda VALUES (221, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Documentacion--><br />Documentos Asociados--><br />Doc. y Formatos</b><br />
Esta secci�n muestra un listado con los tipos de documentos y sus formatos de la aplicación. Se podr� realizar b�squedas, insertar nuevos, editarlos y verlos en detalle.</p>',
                                   NULL, 13);
INSERT INTO division_ayuda VALUES (222, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Documentacio--><br />Documents Associats--><br />Doc. i Formats</b><br />
Aquesta secci� mostra un llistat amb els tipus de documents i els seus formats de l\\''aplicaci�. Es podr� realitzar recerques, inserir nous, editar-los i veure\\''ls en detall.</p>',
                                   NULL, 13);
INSERT INTO division_ayuda VALUES (223, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Acc. Mejora--><br />Listado</b><br />
Este m�dulo muestra un listado en el que el usuario podr� ver las acciones de mejora generadas. El listado muestra qu� cliente gener� la acci�n de mejora (si es que fue generada por alg�n cliente), la fecha en la que se gener�, el tipo de acci�n (no conformidad, preventiva, auditor�a,�) y si está o no cerrada.
De igual modo, el usuario podr� realizar b�squedas seg�n el código o estado (cerrada/no cerrada) de la acci�n de mejora que se quiera buscar.
Es importante recordar que si el usuario tiene los permisos adecuados, podr� registrar nuevas acciones de mejora con los datos necesarios, editarlas y verificarlas. En caso contrario, no podr�. 
Esta secci�n muestra un listado con los tipos de documentos y sus formatos de la aplicación. Se podr� realizar b�squedas, insertar nuevos, editarlos y verlos en detalle.</p>',
                                   NULL, 14);
INSERT INTO division_ayuda VALUES (224, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Acc. Millora--><br />Llistat</b><br />
Aquest m�dul mostra un llistat en el qual l\\''usuari podr� veure les accions de millora generades. El llistat mostra quin client va generar l\\''acci� de millora (si �s que va ser generada per algun client), la data en la qual es va generar, el tipus d\\''acci� (no conformitat, preventiva, auditoria,�) i si está o no tancada. D\\''igual manera, l\\''usuari podr� realitzar recerques segons el codi o estat (tancada/no tancada) de l\\''acci� de millora que es vulgui buscar. �s important recordar que si l\\''usuari t� els permisos adequats, podr� registrar noves accions de millora amb les dades necess�ries, editar-les i verificar-les. En cas contrari, no podr�. Aquesta secci� mostra un llistat amb els tipus de documents i els seus formats de l\\''aplicaci�. Es podr� realitzar recerques, inserir nous, editar-los i veure\\''ls en detall.</p>',
                                   NULL, 14);
INSERT INTO division_ayuda VALUES (225, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Formación--><br />Ficha Personal--><br />Listado</b><br />
Esta secci�n muestra un listado de registros del tipo �Ficha de Personal�. Estos son los registros creados desde el m�dulo de Documentaci�n. De esta forma, el usuario con los permisos adecuados podr� consultar la ficha de cada persona de la organizaci�n.
Por otra parte, el usuario conectado tambi�n podr� crear una nueva ficha completando todos los datos y campos necesarios que aparezcan en los formularios.</p>',
                                   NULL, 15);
INSERT INTO division_ayuda VALUES (226, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Formacio--><br />Fitxa Personal--><br />Llistat</b><br />
Aquesta secci� mostra un llistat de registres del tipus �Fitxa de Personal�. Aquests s�n els registres creats des del m�dul de Documentaci�. D\\''aquesta forma, l\\''usuari amb els permisos adequats podr� consultar la fitxa de cada persona de l\\''organitzaci�. Per altra banda, l\\''usuari connectat tamb� podr� crear una nova fitxa completant tots les dades i camps necessaris que apareguin en els formularis.</p>',
                                   NULL, 15);
INSERT INTO division_ayuda VALUES (227, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Formación--><br />Requisitos del puesto--><br />Listado</b><br />
En esta secci�n se muestra el listado de registros del tipo �Requisitos del puesto�. Al igual que en la secci�n anterior, un usuario con los permisos adecuados podr� consultar cu�les son los requisitos necesarios para un determinado puesto en la organizaci�n.</p>',
                                   NULL, 16);
INSERT INTO division_ayuda VALUES (228, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Formacio--><br />Requisits del lloc--><br />Llistat</b><br />
En aquesta secci� es mostra el llistat de registres del tipus �Requisits del lloc�. Igual que en la secci� anterior, un usuari amb els permisos adequats podr� consultar quins s�n els requisits necessaris per a un determinat lloc en l\\''organitzaci�.</p>',
                                   NULL, 16);
INSERT INTO division_ayuda VALUES (229, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Formación--><br />Cursos--><br />Listado</b><br />
En esta secci�n se muestra un listado de los modelos de cursos disponibles que forman parte de los planes de formaci�n para los usuarios. El/los usuario/s con los permisos adecuados, podr�/n crear nuevos cursos, editar los datos de un curso existente o eliminar un curso de la lista.</p>',
                                   NULL, 17);
INSERT INTO division_ayuda VALUES (230, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Formacio--><br />Cursos--><br />Llistat</b><br />
En aquesta secci� es mostra un llistat dels models de cursos disponibles que formen part dels plans de formaci� per als usuaris. L\\''els/usuari/s amb els permisos adequats, podr�/n crear nous cursos, editar les dades d\\''un curs existent o eliminar un curs de la llista.</p>',
                                   NULL, 17);
INSERT INTO division_ayuda VALUES (231, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Formación--><br />Inscripci�n--><br />Listado</b><br />
En esta secci�n se muestra el listado de cursos del plan de formaci�n vigente, en el que  los usuarios pueden solicitar plaza.
Adem�s, se indica qu� cursos están abiertos para as� poder inscribirse o no en ellos. En el caso de que los cursos están cerrados (en curso o realizados) o no disponibles (en preparaci�n o suspendidos), los usuarios no podr�n solicitar plaza para realizar los mismos.
El usuario conectado podr�, entonces, solicitar plaza para un curso abierto para inscripci�n, anular su plaza si la solicit�, ver los asistentes y todos los detalles del curso (siempre que tenga permiso para todas estas opciones). 
Pero es importante tener en cuenta que, tanto si se solicita o se anula una plaza, ambas operaciones serán efectivas solo si las confirma el responsable del curso.</p>',
                                   NULL, 18);
INSERT INTO division_ayuda VALUES (232, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Formacio--><br />Iscripci�--><br />Llistat</b><br />
En aquesta secci� es mostra el llistat de cursos del pla de formaci� vigent, en el qual els usuaris poden sol�licitar pla�a. A m�s, s\\''indica quins cursos estan oberts per a aix� poder inscriure\\''s o no en ells. En el cas que els cursos estiguin tancats (en curs o realitzats) o no disponibles (en preparaci� o suspesos), els usuaris no podran sol�licitar pla�a per a realitzar els mateixos. L\\''usuari connectat podr�, llavors, sol�licitar pla�a per a un curs obert per a inscripci�, anul�lar la seva pla�a si la va sol�licitar, veure els assistents i tots els detalls del curs (sempre que tingui perm�s per a totes aquestes opcions). Per� �s important tenir en compte que, tant si se sol�licita o s\\''anul�la una pla�a, ambdues operacions seran efectives nom�s si les confirma el responsable del curs.</p>',
                                   NULL, 18);
INSERT INTO division_ayuda VALUES (233, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Formación--><br />Planes Formación--><br />Listado</b><br />
En esta secci�n se muestra un listado con los nombres de los planes de formaci�n creados. S�lo uno de ellos estar� vigente, para que los usuarios puedan inscribirse en los cursos incluidos en �l.
Si tiene los permisos adecuados, un usuario podr� crear nuevos planes, editar la informaci�n de un plan ya creado, dar de baja planes de formaci�n e incluso copiarlos.
El usuario podr� solicitar plaza para un curso abierto para inscripci�n, anular su plaza si la solicit�, ver los asistentes al mismo e incluso obtener m�s detalles sobre el curso seleccionado (siempre que tenga permiso para todas estas opciones).
Tanto si se solicita o se anula una plaza, ambas operaciones serán efectivas solo si las confirma el responsable del curso, al igual que se detalla en la secci�n anterior.</p>',
                                   NULL, 19);
INSERT INTO division_ayuda VALUES (234, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Formacio--><br />PlanesFormaci�--><br />Llistat</b><br />
En aquesta secci� es mostra un llistat amb els noms dels plans de formaci� creats. Nom�s uneixo d\\''ells estar� vigent, perqu� els usuaris puguin inscriure\\''s en els cursos inclosos en ell. Si t� els permisos adequats, un usuari podr� crear nous plans, editar la informaci� d\\''un pla ja creat, donar de baixa plans de formaci� i fins i tot copiar-los. L\\''usuari podr� sol�licitar pla�a per a un curs obert per a inscripci�, anul�lar la seva pla�a si la va sol�licitar, veure els assistents al mateix i fins i tot obtenir m�s detalls sobre el curs seleccionat (sempre que tingui perm�s per a totes aquestes opcions). Tant si se sol�licita o s\\''anul�la una pla�a, ambdues operacions seran efectives nom�s si les confirma el responsable del curs, igual que es detalla en la secci� anterior.</p>',
                                   NULL, 19);
INSERT INTO division_ayuda VALUES (235, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Equipos--><br />Listado</b><br />
En esta secci�n se muestra el listado de equipos de la empresa. En este listado, se muestra, adem�s, el n�mero de control y descripci�n de cada equipo.
Al pulsar �Nuevo�, ver� que aparece un formulario por pantalla. Rellenando este formulario, el usuario podr� crear registros sobre un nuevo equipo. 
Si el usuario selecciona un elemento del listado, ver� que se deshabilita  el bot�n �Nuevo� y que se habilitan otros 5 botones: �Dar de Baja�, ��Ver�, �Plan Mant.�, �Mant. Preventivo� y �Mant. Correctivo�.</p>',
                                   NULL, 20);
INSERT INTO division_ayuda VALUES (236, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Equips--><br />Llistat</b><br />
En aquesta secci� es mostra el llistat d\\''equips de l\\''empresa. En aquest llistat, es mostra, a m�s, el nombre de control i descripci� de cada equip. AL pr�mer �Nou�, veur� que apareix un formulari per pantalla. Emplenant aquest formulari, l\\''usuari podr� crear registres sobre un nou equip. Si l\\''usuari selecciona un element del llistat, veur� que es deshabilita el bot� �Nou� i que s\\''habiliten altres 5 botons: �Donar de Baixa�, ��Veure�, �Pla Mant.�, �Mant. Preventiu� i �Mant. Correctiu�.</p>',
                                   NULL, 20);
INSERT INTO division_ayuda VALUES (237, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Equipos--><br />Revisi�n</b><br />
En esta secci�n se muestra un listado con el n�mero de revisiones pasadas por los equipos. Si el usuario selecciona un elemento del listado, ver� que aparece un bot�n mediante el cual podr� acceder al plan de mantenimiento del equipo seleccionado y desde el cual, por otro lado, podr�  ver informaci�n m�s detallada acerca del mismo.</p>',
                                   NULL, 21);
INSERT INTO division_ayuda VALUES (238, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Equips--><br />Revisi�</b><br />
En aquesta secci� es mostra un llistat amb el nombre de revisions passades pels equips. Si l\\''usuari selecciona un element del llistat, veur� que apareix un bot� mitjan�ant el qual podr� accedir al pla de manteniment de l\\''equip seleccionat i des del qual, d\\''altra banda, podr� veure informaci� m�s detallada sobre mateix.</p>',
                                   NULL, 21);
INSERT INTO division_ayuda VALUES (239, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Equipos--><br />Calendario</b><br />
Aquí se muestra un calendario del a�o actual. En �l habr� d�as marcados en diferentes colores. Uno de ellos (azul) marca el d�a actual mientras que el resto enlaza con la secci�n referente a los planes de mantenimiento, en donde, de igual forma que en los apartados anteriores, el usuario podr� agregar nuevos planes de mantenimiento (tanto preventivos como correctivos).</p>',
                                   NULL, 22);
INSERT INTO division_ayuda VALUES (240, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Equips--><br />Calendari</b><br />
Aquí es mostra un calendari de l\\''any actual. En ell haur� dies marcats en diferents colors. Un d\\''ells (blau) marca el dia actual mentre que la resta enlla�a amb la secci� referent als plans de manteniment, on, d\\''igual forma que en els apartats anteriors, l\\''usuari podr� agregar nous plans de manteniment (tant preventius com correctius).</p>',
                                   NULL, 22);
INSERT INTO division_ayuda VALUES (241, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Auditor�as--><br />Programa Anual</b><br />
Desde el m�dulo de auditor�as el usuario podr� disponer de un listado de programas anuales de auditor�as, entre los que  podr� ver si hay alg�n programa vigente en ese momento.
Al pulsar el bot�n \\"nuevo\\", el usuario acceder� a un peque�o formulario desde el que podr� agregar un nuevo programa de auditor�a al listado. Si el env�o de los datos ha sido correcto, aparecer� un mensaje en pantalla indicando �Actualizada fila�.</p>',
                                   NULL, 23);
INSERT INTO division_ayuda VALUES (242, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Auditories--><br />Programa Anual</b><br />
Des del m�dul d\\''auditories l\\''usuari podr� disposar d\\''un llistat de programes anuals d\\''auditories, entre els quals podr� veure si hi ha algun programa vigent en aquest moment. AL pr�mer el bot� \\"nou\\", l\\''usuari accedir� a un petit formulari des del qual podr� agregar un nou programa d\\''auditoria al llistat. Si l\\''enviament de les dades ha estat correcte, apareixer� un missatge en pantalla indicant �Actualitzada fila�.</p>',
                                   NULL, 23);
INSERT INTO division_ayuda VALUES (243, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Auditor�as--><br />Programa en Vigor</b><br />
Esta secci�n funciona de la misma forma que el plan de Audiorias. Lo único que var�a es que, al seleccionar un elemento del listado de los planes de auditor�a, aparecer� el bot�n �Imprimir�.
Una vez realizada una auditor�a el usuario podr� imprimir un informe de la misma con todos los datos necesarios, incluidas las conclusiones y las posibles no conformidades encontradas (estas �ltimas podr�n ser consultadas con detalle en el m�dulo de Acciones de Mejora de la aplicación).
Si el usuario pulsa el bot�n �Imprimir� que aparece debajo del informe, podr� imprimir el documento.</p>', NULL, 24);
INSERT INTO division_ayuda VALUES (244, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Auditories--><br />Programa en Vigor</b><br />
Aquesta secci� funciona de la mateixa forma que el pla de Audiorias. L\\''�nica cosa que varia �s que, al seleccionar un element del llistat dels plans d\\''auditoria, apareixer� el bot� �Imprimir�. Una vegada realitzada una auditoria l\\''usuari podr� imprimir un informe de la mateixa amb totes les dades necess�ries, incloses les conclusions i les possibles no conformitats oposades (aquestes �ltimes podran ser consultades amb detall en el m�dul d\\''Accions de Millora de l\\''aplicaci�). Si l\\''usuari prem el bot� �Imprimir� que apareix sota informe, podr� imprimir el document.
</p>', NULL, 24);
INSERT INTO division_ayuda VALUES (245, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Aspectos Ambientales--><br />Revisi�n Ambiental</b><br />
En esta secci�n se muestra un listado de los diferentes tipos de aspectos ambientales (directos e indirectos) relacionados con la organizaci�n o empresa. En el listado se describe cada tipo seg�n:
el �rea ambiental a la que afecta (por ejemplo, vertidos de aguas residuales, etc.),
     el impacto ambiental que origina,
     la magnitud y gravedad que tienen dichas acciones,
     la frecuencia con la que se producen,
     qu� valoraci�n tienen.</p>', NULL, 26);
INSERT INTO division_ayuda VALUES (246, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Aspectes Ambientals--><br />Revsi� Ambientals</b><br />
En aquesta secci� es mostra un llistat dels diferents tipus d\\''aspectes ambientals (directes i indirectes) relacionats amb l\\''organitzaci� o empresa. En el llistat es descriu cada tipus segons: l\\''�rea ambiental a la qual afecta (per exemple, abocats d\\''aig�es residuals, etc.), l\\''impacte ambiental que origina, la magnitud i gravetat que tenen aquestes accions, la freq��ncia amb la qual es produ�xen, quina valoraci� tenen.</p>',
                                   NULL, 26);
INSERT INTO division_ayuda VALUES (247, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Aspectos Ambientales--><br />Matriz</b><br />
Esta secci�n muestra por pantalla la matriz de aspectos ambientales de la empresa.</p>', NULL, 27);
INSERT INTO division_ayuda VALUES (248, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Aspectes Ambientals--><br />Matriu</b><br />
Aquesta secci� mostra per pantalla la matriu d\\''aspectes ambientals de l\\''empresa.</p>', NULL, 27);
INSERT INTO division_ayuda VALUES (249, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Procesos--><br />Cat�logos</b><br />
En la pantalla principal de este m�dulo aparece un �rbol desplegable con el cat�logo de procesos y subprocesos que tiene la organizaci�n registrados en ese momento.
Encima del �rbol se encuentran las posibles acciones: �Nuevo�, �Editar�, �Detalles�, �Ver Ficha�, �Ver Matriz� y �Limpiar�.</p>',
                                   NULL, 28);
INSERT INTO division_ayuda VALUES (250, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Procesos--><br />Cat�legs</b><br />
En la pantalla principal d\\''aquest m�dul apareix un arbre desplegable amb el cat�leg de processos i subprocesos que t� l\\''organitzaci� registrats en aquest moment. Damunt de l\\''arbre es troben les possibles accions: �Nou�, �Editar�, �Detalls�, �Veure Fitxa�, �Veure Matriu� i �Netejar�.</p>',
                                   NULL, 28);
INSERT INTO division_ayuda VALUES (251, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Indicadores--><br />Listado</b><br />
Esta secci�n muestra  un listado con todos los indicadores de proceso registrados. Bajo el listado aparecer�n habilitados  los botones �Nuevo� y �Matriz�. Si el usuario  selecciona un elemento del listado, ver� que se habilitan  los botones �Dar de Baja� y �Editar� y se deshabilitar�n,a su vez, los anteriores.</p>',
                                   NULL, 31);
INSERT INTO division_ayuda VALUES (252, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Indicadors--><br />Llistat</b><br />
Aquesta secci� mostra un llistat amb tots els indicadors de proc�s registrats. Sota el llistat apareixeran habilitats els botons �Nou� i �Matriu�. Si l\\''usuari selecciona un element del llistat, veur� que s\\''habiliten els botons �Donar de Baixa� i �Editar� i es deshabilitaran,al seu torn, els anteriors.</p>',
                                   NULL, 31);
INSERT INTO division_ayuda VALUES (253, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Aplicaci�n--><br />Usuarios</b><br />
En este apartado se muestra un listado con los diferentes usuarios que hay a registrados en la aplicación. Aquí el administrador podr� dar de alta un nuevo usuario, editar las propiedades de uno ya existente o incluso darlo de baja del sistema. El administrador tambi�n podr� realizar b�squedas introduciendo el primer apellido en el campo de b�squeda.
Bajo el listado aparecer� como habilitado el bot�n �Nuevo�. Si el administrador  selecciona un elemento del listado, se deshabilitar� este bot�n y se habilitar�n �Dar de Baja�, �Editar� y �Cambiar Password�.</p>',
                                   NULL, 32);
INSERT INTO division_ayuda VALUES (254, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Aministraci�--><br />Aplicaci�--><br />Usuaris</b><br />
En aquest apartat es mostra un llistat amb els diferents usuaris que hi ha a registrats en l\\''aplicaci�. Aquí l\\''administrador podr� donar d\\''alta un nou usuari, editar les propietats d\\''un ja existent o fins i tot donar-lo de baixa del sistema. L\\''administrador tamb� podr� realitzar recerques introduint el primer cognom en el camp de recerca. Sota el llistat apareixer� com habilitat el bot� �Nou�. Si l\\''administrador selecciona un element del llistat, es deshabilitar� aquest bot� i s\\''habilitaran �Donar de Baixa�, �Editar� i �Canviar Password�.</p>',
                                   NULL, 32);
INSERT INTO division_ayuda VALUES (255, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Aplicaci�n--><br />Perfiles</b><br />
En esta secci�n se muestra un listado con el nombre de los distintos perfiles  registrados en el sistema. El administrador podr�, entre otras cosas:
crear, editar y eliminar perfiles,
otorgar o denegar permisos a los perfiles,
ver un registro de los mismos.</p>', NULL, 33);
INSERT INTO division_ayuda VALUES (256, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Aministraci�--><br />Aplicaci�--><br />Usuaris</b><br />
En aquesta secci� es mostra un llistat amb el nom dels diferents perfils registrats en el sistema. L\\''administrador podr�, entre altres coses: crear, editar i eliminar perfils, atorgar o denegar permisos als perfils, veure un registre dels mateixos.</p>',
                                   NULL, 33);
INSERT INTO division_ayuda VALUES (257, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Aplicaci�n--><br />Mensajes</b><br />
Se muestra un listado con los mensajes registrados en el sistema. Desde aqu� se pueden crear nuevos mensajes (que aparecer�n en la pantalla de inicio de todos los usuarios con permiso para recibir mensajes), eliminarlos y editarlos. Tambi�n es posible acceder a un hist�rico de mensajes.
Los botones habilitados al acceder a esta secci�n son �Histórico� y �Nuevo�. Si el administrador  selecciona un elemento del listado se habilitar�n los botones �Dar de Baja� y �Editar�.</p>',
                                   NULL, 35);
INSERT INTO division_ayuda VALUES (258, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Aministraci�--><br />Aplicaci�--><br />Missatges</b><br />
Es mostra un llistat amb els missatges registrats en el sistema. Des d\\''aqu� es poden crear nous missatges (que apareixeran en la pantalla d\\''inici de tots els usuaris amb perm�s per a rebre missatges), eliminar-los i editar-los. Tamb� �s possible accedir a un hist�ric de missatges. Els botons habilitats a l\\''accedir a aquesta secci� s�n �Hist�ric� i �Nou�. Si l\\''administrador selecciona un element del llistat s\\''habilitaran els botons �Donar de Baixa� i �Editar�.</p>',
                                   NULL, 35);
INSERT INTO division_ayuda VALUES (259, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Aplicaci�n--><br />Tareas</b><br />
En esta secci�n se muestra un listado de las tareas que han sido enviadas por los usuarios desde el m�dulo de documentaci�n. El administrador podr� realizar b�squedas introduciendo el origen o el destino del mensaje, la acci�n que describe la tarea o el documento con el que está relacionada en los campos de b�squeda.
Por otro lado, si el administrador selecciona una tarea del listado, se habilitar� el bot�n �Dar de Bajar�, mediante el que dar� de baja la tarea que haya seleccionado. Una vez que las tareas hayan sido dadas de baja, no aparecer�n en la pantalla inicial de los usuarios destinatarios de las tareas.</p>',
                                   NULL, 36);
INSERT INTO division_ayuda VALUES (260, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Aministraci�--><br />Aplicaci�--><br />Tasques</b><br />
En aquesta secci� es mostra un llistat de les tasques que han estat enviades pels usuaris des del m�dul de documentaci�. L\\''administrador podr� realitzar recerques introduint l\\''origen o la destinaci� del missatge, l\\''acci� que descriu la tasca o el document amb el qual está relacionada en els camps de recerca. D\\''altra banda, si l\\''administrador selecciona una tasca del llistat, s\\''habilitar� el bot� �Donar de Baixar�, mitjan�ant el qual donar� de baixa la tasca que hagi seleccionat. Una vegada que les tasques hagin estat donades de baixa, no apareixeran en la pantalla inicial dels usuaris destinataris de les tasques.</p>',
                                   NULL, 36);
INSERT INTO division_ayuda VALUES (261, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Documentaci�n--><br />Documentos S.G.</b><br />
En esta secci�n se muestra un listado con los distintos documentos que existen (borradores, documentos en vigor, documentos de procedimiento general o de instrucci�n t�cnica) y los estados que tienen los mismos. 
El administrador podr�, tambi�n, realizar b�squedas introduciendo el código o el nombre del documento en los campos de b�squeda.</p>',
                                   NULL, 37);
INSERT INTO division_ayuda VALUES (262, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Aministraci�--><br />Documentaci�--><br />Documents S.G.</b><br />
En aquesta secci� es mostra un llistat amb els diferents documents que existeixen (esborradors, documents en vigor, documents de procediment general o d\\''instrucci� t�cnica) i els estats que tenen els mateixos. L\\''administrador podr�, tamb�, realitzar recerques introduint el codi o el nom del document en els camps de recerca.</p>',
                                   NULL, 37);
INSERT INTO division_ayuda VALUES (263, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Documentaci�n--><br />Registros</b><br />
En esta secci�n el administrador podr� gestionar los diferentes tipos de registros que hayan sido registrados en la aplicación, de igual modo que ocurre con los documentos. La diferencia está en que en esta secci�n no se permiten realizar b�squedas.
Al seleccionar el administrador un registro del listado se habilitar�n los botones �Dar de Alta�, �Dar de Baja� y �Listar�. Los dos primeros permitir� al administrador dar de alta o baja el registro que haya seleccionado.</p>',
                                   NULL, 38);
INSERT INTO division_ayuda VALUES (264, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Aministraci�--><br />Documentaci�--><br />Registres</b><br />
En aquesta secci� l\\''administrador podr� gestionar els diferents tipus de registres que hagin estat registrats en l\\''aplicaci�, d\\''igual manera que ocorre amb els documents. La difer�ncia está que en aquesta secci� no es permeten realitzar recerques. AL seleccionar l\\''administrador un registre del llistat s\\''habilitaran els botons �Donar d\\''Alta�, �Donar de Baixa� i �Llistar�. Els dos primers permetr� a l\\''administrador donar d\\''alta o baixa el registre que hagi seleccionat.</p>',
                                   NULL, 38);
INSERT INTO division_ayuda VALUES (265, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Documentaci�n--><br />Normativa</b><br />
En esta secci�n se muestra un listado por nombre y código de las normativas registradas en la aplicación. Desde aqu�, el administrador podr� crear, dar de baja y editar normativas. Pulsando �Nuevo� el administrador acceder� a un formulario en donde podr� añadir una nueva normativa al listado.</p>
', NULL, 39);
INSERT INTO division_ayuda VALUES (266, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Aministraci�--><br />Documentaci�--><br />Normativa</b><br />
En aquesta secci� es mostra un llistat per nom i codi de les normatives registrades en l\\''aplicaci�. Des d\\''aqu�, l\\''administrador podr� crear, donar de baixa i editar normatives. Prement �Nou� l\\''administrador accedir� a un formulari on podr� afegir una nova normativa al llistat.</p>',
                                   NULL, 39);
INSERT INTO division_ayuda VALUES (267, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Criterios--></b><br />
Esta secci�n muestra un listado de los diferentes criterios registrados, as� como la valoraci�n que han obtenido cada uno de ellos y el estado que tienen (activo/inactivo). 
Con el bot�n �Nuevo\\", el administrador podr� agregar, a trav�s de un formulario, un nuevo criterio. 
Si por el contrario, el administrador selecciona un elemento del listado de criterios, se habilitar� entonces el bot�n �Editar�. Al pulsar este boon, el administrador podr� modificar los datos del criterio que haya seleccionado.</p>',
                                   NULL, 40);
INSERT INTO division_ayuda VALUES (268, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Aministraci�--><br />Documentaci�--><br />Criteris</b><br />
Aquesta secci� mostra un llistat dels diferents criteris registrats, aix� com la valoraci� que han obtingut cadascun d\\''ells i l\\''estat que tenen (actiu/inactiu). Amb el bot� �Nou\\", l\\''administrador podr� agregar, a trav�s d\\''un formulari, un nou criteri. Si per contra, l\\''administrador selecciona un element del llistat de criteris, s\\''habilitar� llavors el bot� �Editar�. AL pr�mer aquest boon, l\\''administrador podr� modificar les dades del criteri que hagi seleccionat.</p>',
                                   NULL, 40);
INSERT INTO division_ayuda VALUES (269, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Tipos Acc. Mejora--></b><br />
Esta secci�n muestra un listado de los tipos de acciones de mejora y del estado que �stas tienen (activo/inactivo). Al igual que con los criterios, el administrador solo podr� crear y editar acciones de mejora.
Mediante el bot�n �Nuevo\\", el administrador podr� agregar, a trav�s de un formulario, un nuevo tipo de acci�n de mejora. Si selecciona un elemento del listado, ver� que se habilita el bot�n �Editar�. Pulsando sobre �l, el administrador podr� modificar los datos del criterio que haya seleccionado.
Asimismo, el administrador tambi�n podr� realizar b�squedas introduciendo el nombre del tipo de acci�n de mejora a buscar en el campo de b�squeda.</p>',
                                   NULL, 41);
INSERT INTO division_ayuda VALUES (270, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Aministraci�--><br />Tipus Acc.Millora</b><br />
Aquesta secci� mostra un llistat dels tipus d\\''accions de millora i de l\\''estat que aquestes tenen (actiu/inactiu). Igual que amb els criteris, l\\''administrador nom�s podr� crear i editar accions de millora. Mitjan�ant el bot� �Nou\\", l\\''administrador podr� agregar, a trav�s d\\''un formulari, un nou tipus d\\''acci� de millora. Si selecciona un element del llistat, veur� que s\\''habilita el bot� �Editar�. Prement sobre ell, l\\''administrador podr� modificar les dades del criteri que hagi seleccionat. Aix� mateix, l\\''administrador tamb� podr� realitzar recerques introduint el nom del tipus d\\''acci� de millora a buscar en el camp de recerca.</p>',
                                   NULL, 41);
INSERT INTO division_ayuda VALUES (271, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Clientes--></b><br />
Esta secci�n muestra un listado con los nombres, tel�fonos, y personas de contacto de los clientes que tiene las empresa. 
El administrador podr�, desde esta secci�n, realizar b�squedas introduciendo el nombre del cliente en el campo de b�squeda. Adem�s, pulsando �Nuevo�, el administrador podr� añadir al listado, a trav�s de un formulario, nuevos clientes. Si por el contrario  selecciona un elemento del listado, se habilitar� entonces el bot�n �Editar�, que le permitir� modificar los datos del cliente seleccionado.</p>',
                                   NULL, 42);
INSERT INTO division_ayuda VALUES (272, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Aministraci�--><br />Clients</b><br />
Aquesta secci� mostra un llistat amb els noms, tel�fons, i persones de contacte dels clients que t� les empresa. L\\''administrador podr�, des d\\''aquesta secci�, realitzar recerques introduint el nom del client en el camp de recerca. A m�s, prement �Nou�, l\\''administrador podr� afegir al llistat, a trav�s d\\''un formulari, nous clients. Si per contra selecciona un element del llistat, s\\''habilitar� llavors el bot� �Editar�, que li permetr� modificar les dades del client seleccionat.</p>
', NULL, 42);
INSERT INTO division_ayuda VALUES (273, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Tipos de �rea--></b><br />
Aquí se muestra un listado con los diferentes tipos de �reas registrados. Con el bot�n �Nuevo�, el administrador podr�, a trav�s de un formulario, añadir m�s tipos de �reas. Seleccionando un �rea del listado, se habilitar� el bot�n �Editar�, que permitir� al administrador modificar, a trav�s de otro formulario, los datos del �rea seleccionada.</p>',
                                   NULL, 43);
INSERT INTO division_ayuda VALUES (274, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Aministraci�--><br />Tipus d\\''�rea</b><br />
Aquí es mostra un llistat amb els diferents tipus d\\''�rees registrats. Amb el bot� �Nou�, l\\''administrador podr�, a trav�s d\\''un formulari, afegir m�s tipus d\\''�rees. Seleccionant un �rea del llistat, s\\''habilitar� el bot� �Editar�, que permetr� a l\\''administrador modificar, a trav�s d\\''altre formulari, les dades de l\\''�rea seleccionada.</p>',
                                   NULL, 43);
INSERT INTO division_ayuda VALUES (275, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Tipos Ambientes--></b><br />
Aquí se muestra un listado con los diferentes tipos de ambientes registrados. Con el bot�n �Nuevo�, el administrador podr�, a trav�s de un formulario, añadir m�s tipos de ambientes. Seleccionando un �rea del listado, se habilitar� el bot�n �Editar�, que permitir� al administrador modificar, a trav�s de otro formulario, los datos del ambiente seleccionado.</p>',
                                   NULL, 44);
INSERT INTO division_ayuda VALUES (276, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Aministraci�--><br />Tipus Ambientis</b><br />
Aquí es mostra un llistat amb els diferents tipus d\\''ambients registrats. Amb el bot� �Nou�, l\\''administrador podr�, a trav�s d\\''un formulari, afegir m�s tipus d\\''ambients. Seleccionant un �rea del llistat, s\\''habilitar� el bot� �Editar�, que permetr� a l\\''administrador modificar, a trav�s d\\''altre formulari, les dades de l\\''ambient seleccionat.</p>',
                                   NULL, 44);
INSERT INTO division_ayuda VALUES (277, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Proveedores--><br />Listado--></b><br />
En esta secci�n se muestra un listado de los proveedores de la organizaci�n. De cada proveedor se muestra su nombre y n�mero de tel�fono. Si el usuario conectado tiene los permisos adecuados, podr� crear un nuevo registro de proveedor, ver y editar los datos de un proveedor ya existente y eliminarlo.
En esta secci�n tambi�n existe la posibilidad de realizar b�squedas de proveedores, introduciendo en los campos de b�squeda el nombre y/o el n�mero de tel�fono del proveedor a buscar.
De la misma forma, y bajo el listado, aparecer� el bot�n �Nuevo�, que permitir� al usuario crear m�s registros rellenando un formulario. Entre los botones que aparecen en el formulario, está el bot�n �Calendario�.
 As�mismo, es importante recordar que son obligatorios los campos que llevan un peque�o asterisco rojo al lado del t�tulo y que no se pueden dejar sin rellenar.</p>',
                                   NULL, 45);
INSERT INTO division_ayuda VALUES (278, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Prove�dors--><br />Tipus Ambientis</b><br />
En aquesta secci� es mostra un llistat dels prove�dors de l\\''organitzaci�. De cada prove�dor es mostra el seu nom i nombre de tel�fon. Si l\\''usuari connectat t� els permisos adequats, podr� crear un nou registre de prove�dor, veure i editar les dades d\\''un prove�dor ja existent i eliminar-lo. En aquesta secci� tamb� existeix la possibilitat de realitzar recerques de prove�dors, introduint en els camps de recerca el nom i/o el nombre de tel�fon del prove�dor a buscar. De la mateixa forma, i sota el llistat, apareixer� el bot� �Nou�, que permetr� a l\\''usuari crear m�s registres emplenant un formulari. Entre els botons que apareixen en el formulari, está el bot� �Calendari�. As�mismo, �s important recordar que s�n obligatoris els camps que duen un petit asterisc vermell al costat del t�tol i que no es poden deixar sense emplenar.</p>',
                                   NULL, 45);
INSERT INTO division_ayuda VALUES (279, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Proveedores--><br />Incidencias--></b><br />
Esta secci�n muestra un listado con las incidencias detectadas. El usuario, desde aqu�, podr� realizar b�squedas seg�n el nombre de la incidencia. Y si selecciona un elemento del listado ver� que se habilitan botones que le permitir�n ver o editar la informaci�n relacionada con esas incidencias.</p>',
                                   NULL, 46);
INSERT INTO division_ayuda VALUES (280, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Prove�dors--><br />Incid�ncies</b><br />
Aquesta secci� mostra un llistat amb les incid�ncies detectades. L\\''usuari, des d\\''aqu�, podr� realitzar recerques segons el nom de la incid�ncia. I si selecciona un element del llistat veur� que s\\''habiliten botons que li permetran veure o editar la informaci� relacionada amb aquestes incid�ncies.</p>',
                                   NULL, 46);
INSERT INTO division_ayuda VALUES (281, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Proveedores--><br />Contactos--></b><br />
En esta secci�n se muestra el listado de contactos que tiene la organizaci�n. El usuario podr� realizar b�squedas, agregar nuevos contactos, modificar esa informaci�n e incluso ver m�s detalles acerca de los mismos.</p>',
                                   NULL, 47);
INSERT INTO division_ayuda VALUES (282, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Prove�dors--><br />Contactes</b><br />
En aquesta secci� es mostra el llistat de contactes que t� l\\''organitzaci�. L\\''usuari podr� realitzar recerques, agregar nous contactes, modificar aquesta informaci� i fins i tot veure m�s detalls sobre els mateixos.</p>',
                                   NULL, 47);
INSERT INTO division_ayuda VALUES (283, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Proveedores--><br />Productos--></b><br />
En esta secci�n, el usuario podr� ver un listado de los productos seg�n el nombre de los mismos, si están homologados o no, la fecha de homologaci�n (en el caso de que lo están), si están activos, etc. Como ocurre con otras secciones, el usuario tambi�n podr� realizar b�squedas, de la misma forma explicada anteriormente.
Si el usuario pulsa el bot�n �Nuevo�,  acceder� a un formulario en donde podr� registrar un nuevo producto. Si por el contrario selecciona un elemento del listado, aparecer�n habilitados m�s botones bajo el mismo, que le permitir�n ver informaci�n m�s detallada del producto, modificar los datos del mismo, ver los criterios por los que se eval�a, si está o no homologado, agregar o eliminar criterios de valoraci�n, revisar el producto e incluso acceder a un hist�rico de las homologaciones del producto en cuesti�n.</p>',
                                   NULL, 48);
INSERT INTO division_ayuda VALUES (284, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Prove�dors--><br />Productes</b><br />
En aquesta secci�, l\\''usuari podr� veure un llistat dels productes segons el nom dels mateixos, si estan homologats o no, la data d\\''homologaci� (en el cas que ho estiguin), si estan actius, etc. Com ocorre amb altres seccions, l\\''usuari tamb� podr� realitzar recerques, de la mateixa forma explicada anteriorment. Si l\\''usuari prem el bot� �Nou�, accedir� a un formulari on podr� registrar un nou producte. Si per contra selecciona un element del llistat, apareixeran habilitats m�s botons sota el mateix, que li permetran veure informaci� m�s detallada del producte, modificar les dades del mateix, veure els criteris pels quals s\\''avalua, si está o no homologat, agregar o eliminar criteris de valoraci�, revisar el producte i fins i tot accedir a un hist�ric de les homologacions del producte en q�esti�.</p>',
                                   NULL, 48);
INSERT INTO division_ayuda VALUES (285, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Proveedores--><br />P. Homologados--></b><br />
El usuario podr�, en esta secci�n, ver un listado con la informaci�n de los productos que están homologados. Adem�s, tambi�n podr� realizar b�squedas. Si el usuario selecciona un elemento del listado, ver� que se habilitan m�s opciones:. Entre ellas, el usuario podr� �Dar de Baja� al proveedor seleccionado; tambi�n podr� �Ver� informaci�n m�s detallada sobre el mismo e incluso podr� �Editar� los datos del proveedores que haya seleccionado.</p>',
                                   NULL, 49);
INSERT INTO division_ayuda VALUES (286, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Prove�dors--><br />P.Homologats</b><br />
L\\''usuari podr�, en aquesta secci�, veure un llistat amb la informaci� dels productes que estan homologats. A m�s, tamb� podr� realitzar recerques. Si l\\''usuari selecciona un element del llistat, veur� que s\\''habiliten m�s opcions:. Entre elles, l\\''usuari podr� �Donar de Baixa� al prove�dor seleccionat; tamb� podr� �Veure� informaci� m�s detallada sobre el mateix i fins i tot podr� �Editar� les dades del prove�dors que hagi seleccionat</p>',
                                   NULL, 49);
INSERT INTO division_ayuda VALUES (287, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Indicadres--><br />Listado de Objetivos--></b><br />
En esta secci�n se muestra un listado de los objetivos a cumplir por los indicadores. 
Bajo el listado aparecer� habilitado el bot�n �Nuevo�, mediante el cual el usuario podr�, a trav�s del formulario que se mostrar� por pantalla, añadir m�s objetivos a la lista.
Si por el contrario, el usuario selecciona un elemento del listado, ver� que se habilitan  los botones �Dar de Baja� y �Editar�.</p>',
                                   NULL, 50);
INSERT INTO division_ayuda VALUES (288, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Indicadres--><br />Llistat \\''Objetius</b><br />
En aquesta secci� es mostra un llistat dels objectius a complir pels indicadors. Sota el llistat apareixer� habilitat el bot� �Nou�, mitjan�ant el qual l\\''usuari podr�, a trav�s del formulari que es mostrar� per pantalla, afegir m�s objectius a la llista. Si per contra, l\\''usuari selecciona un element del llistat, veur� que s\\''habiliten els botons �Donar de Baixa� i �Editar�.</p>',
                                   NULL, 50);
INSERT INTO division_ayuda VALUES (289, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Legislación Aplicable-></b><br />
Desde esta secci�n el administrador podra gestionar la documentaci�n relativa a la legislaci�n aplicable en la empresa. En esta secci�n se muestra un listado de los documentos de este tipo. Si el administrador selecciona un documento del listado, se habilitar� el bot�n �Preguntas�.</p>',
                                   NULL, 55);
INSERT INTO division_ayuda VALUES (290, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Legislaci� Aplicable</b><br />
Des d\\''aquesta secci� l\\''administrador podra gestionar la documentaci� relativa a la legislaci� aplicable en l\\''empresa. En aquesta secci� es mostra un llistat dels documents d\\''aquest tipus. Si l\\''administrador selecciona un document del llistat, s\\''habilitar� el bot� �Preguntes�.</p>',
                                   NULL, 55);
INSERT INTO division_ayuda VALUES (291, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Documentaci�n--><br />Doc. Borrador</b><br />
En esta secci�n se muestra un listado con los documentos en estado �borrador�, es decir, documentos que todav�a no han sido aprobados y/o revisados, como ya se ha explicado en secciones anteriors. En este listado aparecen los documentos seg�n su código, nombre, tipo de documento, si está o no revisado y n�mero de revisi�n.</p>',
                                   NULL, 57);
INSERT INTO division_ayuda VALUES (292, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Documentaci�--><br />Doc Esborrador</b><br />
En aquesta secci� es mostra un llistat amb els documents en estat �esborrador�, �s a dir, documents que encara no han estat aprovats i/o revisats, com ja s\\''ha explicat en seccions anteriors. En aquest llistat apareixen els documents segons el seu codi, nom, tipus de document, si está o no revisat i nombre de revisi�.</p>',
                                   NULL, 57);
INSERT INTO division_ayuda VALUES (293, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Tipos Impactos Ambientales</b><br />
Aquí se muestra un listado con los diferentes tipos de impactos ambientales. Con el bot�n �Nuevo�, el administrador podr�, a trav�s de un formulario, añadir m�s tipos de impactos ambientales. Seleccionando un �rea del listado, se habilitar� el bot�n �Editar�, que permitir� al administrador modificar, a trav�s de otro formulario, los datos del impacto ambiental seleccionado.</p>',
                                   NULL, 58);
INSERT INTO division_ayuda VALUES (294, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Tipus Impactes Ambientals</b><br />
Aquí es mostra un llistat amb els diferents tipus d\\''impactes ambientals. Amb el bot� �Nou�, l\\''administrador podr�, a trav�s d\\''un formulari, afegir m�s tipus d\\''impactes ambientals. Seleccionant un �rea del llistat, s\\''habilitar� el bot� �Editar�, que permetr� a l\\''administrador modificar, a trav�s d\\''altre formulari, les dades de l\\''impacte ambiental seleccionat.</p>',
                                   NULL, 58);
INSERT INTO division_ayuda VALUES (295, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Tipos Cursos</b><br />
Aquí se muestra un listado con los diferentes tipos de cursos. Con el bot�n �Nuevo�, el administrador podr�, a trav�s de un formulario, añadir m�s tipos de cursos. Seleccionando un �rea del listado, se habilitar� el bot�n �Editar�, que permitir� al administrador modificar, a trav�s de otro formulario, los datos del curso seleccionado.</p>',
                                   NULL, 61);
INSERT INTO division_ayuda VALUES (296, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Tipus Cursos</b><br />
Aquí es mostra un llistat amb els diferents tipus de cursos. Amb el bot� �Nou�, l\\''administrador podr�, a trav�s d\\''un formulari, afegir m�s tipus de cursos. Seleccionant un �rea del llistat, s\\''habilitar� el bot� �Editar�, que permetr� a l\\''administrador modificar, a trav�s d\\''altre formulari, les dades del curs seleccionat.</p>',
                                   NULL, 61);
INSERT INTO division_ayuda VALUES (297, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Documentos--><br />Documentos S.G.--><br />An�lisis Ambiental Inicial</b><br />
Aquí se muestra un listado con los documntos de an�lisis ambiental inicial. Con el bot�n �Nuevo�, el administrador podr�, a trav�s de un formulario, añadir m�s documentos. Seleccionando un �rea del listado, se habilitar� el bot�n �Editar�, que permitir� al administrador modificar, a trav�s de otro formulario, los datos del documento seleccionado.</p>',
                                   NULL, 62);
INSERT INTO division_ayuda VALUES (298, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Documents--><br />Documents S.G.--><br />An�lisi Ambiental Inicial</b><br />
Aquí es mostra un llistat amb els documntos d\\''an�lisi ambiental inicial. Amb el bot� �Nou�, l\\''administrador podr�, a trav�s d\\''un formulari, afegir m�s documents. Seleccionant un �rea del llistat, s\\''habilitar� el bot� �Editar�, que permetr� a l\\''administrador modificar, a trav�s d\\''altre formulari, les dades del document seleccionat.</p>',
                                   NULL, 62);
INSERT INTO division_ayuda VALUES (299, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Documentos--><br />Legislación--><br />Normativa</b><br />
En esta secci�n del m�dulo se muestro un listado de los documentos referentes a normativas, leyes o decretos vigentes.</p>',
                                   NULL, 63);
INSERT INTO division_ayuda VALUES (300, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Documents--><br />Legislaci�--><br />Normativa</b><br />
En aquesta secci� del m�dul es mostro un llistat dels documents referents a normatives, lleis o decrets vigents.</p>',
                                   NULL, 63);
INSERT INTO division_ayuda VALUES (301, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Tipo de Documento</b><br />
Aquí se muestra un listado con los diferentes tipos de documentos. Con el bot�n �Nuevo�, el administrador podr�, a trav�s de un formulario, añadir m�s tipos de documentos. Seleccionando un �rea del listado, se habilitar� el bot�n �Editar�, que permitir� al administrador modificar, a trav�s de otro formulario, los datos del documento seleccionado.</p>',
                                   NULL, 64);
INSERT INTO division_ayuda VALUES (302, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Tipus de Document</b><br />
Aquí es mostra un llistat amb els diferents tipus de documents. Amb el bot� �Nou�, l\\''administrador podr�, a trav�s d\\''un formulari, afegir m�s tipus de documents. Seleccionant un �rea del llistat, s\\''habilitar� el bot� �Editar�, que permetr� a l\\''administrador modificar, a trav�s d\\''altre formulari, les dades del document seleccionat.</p>',
                                   NULL, 64);
INSERT INTO division_ayuda VALUES (303, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Aplicaci�n--><br />Men�s</b><br />
Aquí se muestra un listado con los diferentes menús de la aplicación. Con el bot�n �Nuevo�, el administrador podr�, a trav�s de un formulario, añadir m�s menús. Seleccionando un �rea del listado, se habilitar� el bot�n �Editar�, \\"Botones\\" e \\"Idiomas\\".</p>',
                                   NULL, 93);
INSERT INTO division_ayuda VALUES (304, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Aplicaci�-->Men�s</b><br />
Aquí es mostra un llistat amb els diferents menús de l\\''aplicaci�. Amb el bot� �Nou�, l\\''administrador podr�, a trav�s d\\''un formulari, afegir m�s menús. Seleccionant un �rea del llistat, s\\''habilitar� el bot� �Editar�, \\"Botons\\" i \\"Idiomes\\".</p>',
                                   NULL, 93);
INSERT INTO division_ayuda VALUES (305, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Aplicaci�n--><br />Idiomas</b><br />
En esta secci�n se muestra un listado con los idiomas de la aplicación. Se podr� añadir nuevos idiomas con el bot�n nuevo.</p>',
                                   NULL, 94);
INSERT INTO division_ayuda VALUES (306, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Aplicaci�-->Idiomes</b><br />
En aquesta secci� es mostra un llistat amb els idiomes de l\\''aplicaci�. Es podr� afegir nous idiomes amb el bot� nou.</p>',
                                   NULL, 94);
INSERT INTO division_ayuda VALUES (307, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Aspectos Ambientales--><br />Magnitud</b><br />
En esta secci�n se muestra un listado con las distintas magnitudes de los aspectos ambientales.Se podr� añadir nuevas magnitudes con el bot�n nuevo.</p>',
                                   NULL, 96);
INSERT INTO division_ayuda VALUES (308, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Aspectes Ambientals-->Magnitud</b><br />
En aquesta secci� es mostra un llistat amb les diferents magnituds dels aspectes ambientals.Es podr� afegir noves magnituds amb el bot� nou.</p>',
                                   NULL, 96);
INSERT INTO division_ayuda VALUES (309, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Aspectos Ambientales--><br />Gravedad</b><br />
En esta secci�n se muestra un listado con las distintas tipos de gravedades de los aspectos ambientales.Se podr� añadir nuevos registros con el bot�n nuevo.</p>',
                                   NULL, 97);
INSERT INTO division_ayuda VALUES (310, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Aspectes Ambientals-->Gravetat</b><br />
En aquesta secci� es mostra un llistat amb les diferents tipus de gravetats dels aspectes ambientals.Es podr� afegir nous registres amb el bot� nou.</p>',
                                   NULL, 97);
INSERT INTO division_ayuda VALUES (311, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Aspectos Ambientales--><br />Frecuencia</b><br />
En esta secci�n se muestra un listado con las distintas  de los aspectos ambientales.Se podr� añadir nuevos registros con el bot�n nuevo.</p>',
                                   NULL, 98);
INSERT INTO division_ayuda VALUES (312, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Aspectes Ambientals-->Freq��ncia</b><br />
En aquesta secci� es mostra un llistat amb les diferents freq��ncies dels aspectes ambientals.Es podr� afegir nous registres amb el bot� nou.</p>',
                                   NULL, 98);
INSERT INTO division_ayuda VALUES (313, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Aspectos Ambientales--><br />F�rmula</b><br />
En esta secci�n se muestra un listado con las f�rmulas de los aspectos ambientales. No se pueden añadir mas f�rmulas a este campo.</p>',
                                   NULL, 100);
INSERT INTO division_ayuda VALUES (314, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Aspectes Ambientals-->F�rmula</b><br />
En aquesta secci� es mostra un llistat amb les f�rmules dels aspectes ambientals. No es poden afegir mes f�rmules a aquest camp.</p>',
                                   NULL, 100);
INSERT INTO division_ayuda VALUES (315, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Aspectos Ambientales--><br />Asp. Amb. Emergencia</b><br />
En esta secci�n se muestra un listado con los aspectos ambientales de emergencia. Se puede añadir nuevos registros, editarlos, darlos de baja y realizar b�squedas.</p>',
                                   NULL, 101);
INSERT INTO division_ayuda VALUES (316, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Aspectes Ambientals--><br />Asp. Amb. Emerg�ncia</b><br />
En aquesta secci� es mostra un llistat amb els aspectes ambientals d\\''emerg�ncia. Es pot afegir nous registres, editar-los, donar-los de baixa i realitzar recerques.</p>',
                                   NULL, 101);
INSERT INTO division_ayuda VALUES (317, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Aspectos Ambientales--><br />Probabilidad</b><br />
En esta secci�n se muestra un listado con los tipos de probabilidades de aspectos ambientales. Se puede añadir nuevos registros, editarlos y realizar b�squedas.</p>',
                                   NULL, 102);
INSERT INTO division_ayuda VALUES (318, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Aspectes Ambientals--><br />Probabilitat</b><br />
En aquesta secci� es mostra un llistat amb els tipus de probabilitats d\\''aspectes ambientals. Es pot afegir nous registres, editar-los i realitzar recerques.</p>',
                                   NULL, 102);
INSERT INTO division_ayuda VALUES (319, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Aspectos Ambientales--><br />Severidad</b><br />
En esta secci�n se muestra un listado con los tipos de severidades  de aspectos ambientales. Se puede añadir nuevos registros, editarlos y realizar b�squedas.</p>',
                                   NULL, 103);
INSERT INTO division_ayuda VALUES (320, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Aspectes Ambientals--><br />Severitat</b><br />
En aquesta secci� es mostra un llistat amb els tipus de severitats d\\''aspectes ambientals. Es pot afegir nous registres, editar-los i realitzar recerques.</p>',
                                   NULL, 103);
INSERT INTO division_ayuda VALUES (321, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Documentaci�n--><br />Documentos S.G.--><br />Asp.Amb.Emergencias</b><br />
En esta secci�n se muestra un listado con los tipos documentos de los aspectos ambientales de emergencia. Se puede añadir nuevos registros, editarlos, cambiar permisos y realizar b�squedas.</p>',
                                   NULL, 104);
INSERT INTO division_ayuda VALUES (322, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Documentaic�--><br />Documents S.G.--><br />Asp.Amb.Emergencies</b><br />
En aquesta secci� es mostra un llistat amb els tipus documents dels aspectes ambientals d\\''emerg�ncia. Es pot afegir nous registres, editar-los, canviar permisos i realitzar recerques.</p>',
                                   NULL, 104);
INSERT INTO division_ayuda VALUES (323, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Ayuda</b><br />
En esta secci�n se muestra un listado con los registros de ayuda de la aplicación. Se puede añadir nuevos registros, editarlos y realizar b�squedas.</p>',
                                   NULL, 106);
INSERT INTO division_ayuda VALUES (324, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraic�--><br />Ajuda</b><br />
En aquesta secci� es mostra un llistat amb els registres d\\''ajuda de l\\''aplicaci�. Es pot afegir nous registres, editar-los i realitzar recerques.</p>',
                                   NULL, 106);
INSERT INTO division_ayuda VALUES (325, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Permisos</b><br />
En esta secci�n se muestra un listado con los m�dulos de la aplicación. A cada una se le podr� modificar los permisos pinchando en \\"Permisos\\".</p>
', NULL, 107);
INSERT INTO division_ayuda VALUES (326, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraic�--><br />Permisos</b><br />
En aquesta secci� es mostra un llistat amb els m�duls de l\\''aplicaci�. A cadascuna se li podr� modificar els permisos punxant en \\"Permisos\\".</p>',
                                   NULL, 107);
INSERT INTO division_ayuda VALUES (327, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Acc. Mejora--><br />--><br />Listado-->Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 1, NULL);
INSERT INTO division_ayuda VALUES (328, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Acc. Millora--><br />Llistat-->Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 1, NULL);
INSERT INTO division_ayuda VALUES (329, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Acc. Mejora--><br />--><br />Listado-->Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 2, NULL);
INSERT INTO division_ayuda VALUES (330, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Acc. Millora--><br />Llistat-->Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 2, NULL);
INSERT INTO division_ayuda VALUES (331, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Proveedores--><br />Listado-->Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 4, NULL);
INSERT INTO division_ayuda VALUES (332, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Prove�dors--><br />Llistat-->Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 4, NULL);
INSERT INTO division_ayuda VALUES (333, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Proveedores--><br />--><br />Listado-->Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 5, NULL);
INSERT INTO division_ayuda VALUES (334, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Prove�dors--><br />Llistat-->Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 5, NULL);
INSERT INTO division_ayuda VALUES (335, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Proveedores--><br />Inicidencias--><br />Listado-->Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 10, NULL);
INSERT INTO division_ayuda VALUES (336, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Prove�dors--><br />Incidencias-->br>Llistat-->Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 10, NULL);
INSERT INTO division_ayuda VALUES (337, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Proveedores--><br />Incidencias--><br />Listado-->Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 12, NULL);
INSERT INTO division_ayuda VALUES (338, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Prove�dors--><br />Incidencias--><br />Llistat-->Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 12, NULL);
INSERT INTO division_ayuda VALUES (339, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Proveedores--><br />Contactos--><br />Listado-->Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 13, NULL);
INSERT INTO division_ayuda VALUES (340, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Prove�dors--><br />Contacts-->br>Llistat-->Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 13, NULL);
INSERT INTO division_ayuda VALUES (341, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Proveedores--><br />Contactos--><br />Listado-->Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 15, NULL);
INSERT INTO division_ayuda VALUES (342, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Prove�dors--><br />Contacts--><br />Llistat-->Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 15, NULL);
INSERT INTO division_ayuda VALUES (343, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Proveedores--><br />Productos--><br />Listado-->Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 16, NULL);
INSERT INTO division_ayuda VALUES (344, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Prove�dors--><br />Products-->br>Llistat-->Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 16, NULL);
INSERT INTO division_ayuda VALUES (345, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Proveedores--><br />Productos--><br />Listado-->Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 18, NULL);
INSERT INTO division_ayuda VALUES (346, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Prove�dors--><br />Productos--><br />Llistat-->Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 18, NULL);
INSERT INTO division_ayuda VALUES (347, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Proveedores--><br />Productos--><br />Criterios</b><br />
El usuario puede consultar los criterios de homologaci�n que cumple un determinado producto de un proveedor, modificarlos y consultar un hist�rico de homologaci�n (siempre que tenga los permisos adecuados). 
Los criterios de homologaci�n que cumple un producto determinado de un proveedor tienen una puntuaci�n predefinida. Si la suma de todos los criterios que cumple un producto supera un valor de homologaci�n predefinido para el mismo, entonces el proveedor está homologado para ese producto.</p>',
                                   19, NULL);
INSERT INTO division_ayuda VALUES (348, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Prove�dors--><br />Productos--><br />Criteris</b><br />
L\\''usuari pot consultar els criteris d\\''homologaci� que complix un determinat producte d\\''un prove�dor, modificar-los i consultar un hist�ric d\\''homologaci� (sempre que tingui els permisos adequats). Els criteris d\\''homologaci� que complix un producte determinat d\\''un prove�dor tenen una puntuaci� predefinida. Si la suma de tots els criteris que complix un producte supera un valor d\\''homologaci� predefinido per al mateix, llavors el prove�dor está homologat per a aquest producte.</p>',
                                   19, NULL);
INSERT INTO division_ayuda VALUES (349, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Proveedores--><br />Productos--><br />Revisar</b><br />
Mediante este bot�n, el usuario podr� acceder a un listado con los criterios relacionados con el producto seleccionado del listado, adem�s de ver la valoraci�n obtenida por los mismos. De igual forma, el usuario podr� saber  si el producto en cuesti�n está o no homologado. Si el usuario conectado quiere revisar un producto, deber� pulsar el bot�n �Revisar�, con lo que el producto quedar� revisado autom�ticamente.</p>',
                                   20, NULL);
INSERT INTO division_ayuda VALUES (350, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Prove�dors--><br />Productos--><br />Revisar</b><br />
Mitjan�ant aquest bot�, l\\''usuari podr� accedir a un llistat amb els criteris relacionats amb el producte seleccionat del llistat, a m�s de veure la valoraci� obtinguda pels mateixos. D\\''igual forma, l\\''usuari podr� saber si el producte en q�esti� está o no homologat. Si l\\''usuari connectat vol revisar un producte, haur� de pr�mer el bot� �Revisar�, amb el que el producte quedar� revisat autom�ticament.</p>',
                                   20, NULL);
INSERT INTO division_ayuda VALUES (351, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Proveedores--><br />Productos--><br />Histórico</b><br />
Mediante este bot�n, el usuario puede acceder a un listado en el que se muestran las veces que  ha sido homologado un producto.</p>',
                                   21, NULL);
INSERT INTO division_ayuda VALUES (352, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Prove�dors--><br />Productos--><br />Hist�ric</b><br />
Mitjan�ant aquest bot�, l\\''usuari pot accedir a un llistat en el qual es mostren les vegades que ha estat homologat un producte.</p>',
                                   21, NULL);
INSERT INTO division_ayuda VALUES (353, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Proveedores--><br />Proveedor--><br />Listado-->Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 23, NULL);
INSERT INTO division_ayuda VALUES (354, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Prove�dors--><br />Prove�dor--><br />Llistat-->Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 23, NULL);
INSERT INTO division_ayuda VALUES (355, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Documentaci�n--><br />Documentos P.G--><br />Listado-->Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 29, NULL);
INSERT INTO division_ayuda VALUES (356, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Prove�dors--><br />Documents P.G-->br>Llistat-->Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 29, NULL);
INSERT INTO division_ayuda VALUES (357, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Documentaci�n--><br />Documentos P.O.--><br />Listado-->Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 32, NULL);
INSERT INTO division_ayuda VALUES (358, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Prove�dors--><br />Documents P.O.-->br>Llistat-->Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 32, NULL);
INSERT INTO division_ayuda VALUES (359, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Documentaci�n--><br />Legislación--><br />Listado-->Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 40, NULL);
INSERT INTO division_ayuda VALUES (360, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Prove�dors--><br />Legislaci�-->br>Llistat-->Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 40, NULL);
INSERT INTO division_ayuda VALUES (361, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Proveedores--><br />Legislación--><br />Listado-->Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 42, NULL);
INSERT INTO division_ayuda VALUES (362, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Prove�dors--><br />Legislaci�--><br />Llistat-->Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 42, NULL);
INSERT INTO division_ayuda VALUES (363, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Documentaci�n--><br />Cuestionario--><br />Listado-->Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 44, NULL);
INSERT INTO division_ayuda VALUES (364, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Prove�dors--><br />Q�estionari-->br>Llistat-->Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 44, NULL);
INSERT INTO division_ayuda VALUES (365, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Formación--><br />Cursos--><br />Listado-->Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 46, NULL);
INSERT INTO division_ayuda VALUES (366, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Formaci�--><br />Cursos-->br>Llistat-->Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 46, NULL);
INSERT INTO division_ayuda VALUES (367, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Formación--><br />Cursos--><br />Listado-->Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 47, NULL);
INSERT INTO division_ayuda VALUES (368, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Formaci�--><br />Cursos--><br />Llistat-->Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 47, NULL);
INSERT INTO division_ayuda VALUES (369, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Formación--><br />Planes--><br />Listado-->Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>
', 53, NULL);
INSERT INTO division_ayuda VALUES (370, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Formaci�--><br />Plans-->br>Llistat-->Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 53, NULL);
INSERT INTO division_ayuda VALUES (371, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Formación--><br />Planes--><br />Listado-->Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 54, NULL);
INSERT INTO division_ayuda VALUES (372, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Formaci�--><br />Plans--><br />Llistat-->Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 54, NULL);
INSERT INTO division_ayuda VALUES (373, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Equipos--><br />Listado-->Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 57, NULL);
INSERT INTO division_ayuda VALUES (374, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Equips-->Llistat-->Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 57, NULL);
INSERT INTO division_ayuda VALUES (375, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Equipos--><br />Plan Mantenimiento--><br />Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 60, NULL);
INSERT INTO division_ayuda VALUES (376, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Equips-->Plan Manteniment-->Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 60, NULL);
INSERT INTO division_ayuda VALUES (377, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Formación--><br />Mantenimiento--><br />Listado-->Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 61, NULL);
INSERT INTO division_ayuda VALUES (378, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Formaci�--><br />Mantinement--><br />Llistat-->Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 61, NULL);
INSERT INTO division_ayuda VALUES (379, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Auditor�as--><br />Programa--><br />Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 64, NULL);
INSERT INTO division_ayuda VALUES (380, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Auditories--><br />Programa-->Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 64, NULL);
INSERT INTO division_ayuda VALUES (381, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Auditor�as--><br />Programa--><br />Listado-->Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 65, NULL);
INSERT INTO division_ayuda VALUES (382, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Auditories--><br />Programa--><br />Llistat-->Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 65, NULL);
INSERT INTO division_ayuda VALUES (383, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Auditor�as--><br />Auditor�a--><br />Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 70, NULL);
INSERT INTO division_ayuda VALUES (384, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Auditories--><br />Auditoria-->Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 70, NULL);
INSERT INTO division_ayuda VALUES (385, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Auditor�as--><br />Auditor�a--><br />Listado-->Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 71, NULL);
INSERT INTO division_ayuda VALUES (386, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Auditories--><br />Auditoria--><br />Llistat-->Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 71, NULL);
INSERT INTO division_ayuda VALUES (387, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Auditor�as--><br />Plan--><br />Listado-->Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 74, NULL);
INSERT INTO division_ayuda VALUES (388, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Auditories--><br />Plan--><br />Llistat-->Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 74, NULL);
INSERT INTO division_ayuda VALUES (389, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Indicadores--><br />Listados--><br />Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 79, NULL);
INSERT INTO division_ayuda VALUES (390, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Indicadors--><br />Llistat-->Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 79, NULL);
INSERT INTO division_ayuda VALUES (391, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Indicadores--><br />Listados-->Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 80, NULL);
INSERT INTO division_ayuda VALUES (392, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Indicadors--><br />Llistat-->Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 80, NULL);
INSERT INTO division_ayuda VALUES (393, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Indicadores--><br />Matriz</b><br />
En esta matriz aparecen listados todos los indicadores existentes, los procesos a los que pertenecen, sus valores iniciales, sus valores tolerables, los objetivos a cumplir, los m�todos de medici�n utilizados, la periodicidad con la que se realizan las mediciones y el nombre de la persona encargada de realizarlas.</p>',
                                   82, NULL);
INSERT INTO division_ayuda VALUES (394, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Indicadors--><br />Matriu</b><br />
En aquesta matriu apareixen llistats tots els indicadors existents, els processos als quals pertanyen, els seus valors inicials, els seus valors tolerables, els objectius a complir, els m�todes de mesurament utilitzats, la periodicitat amb la qual es realitzen els mesuraments i el nom de la persona encarregada de realitzar-les.</p>',
                                   82, NULL);
INSERT INTO division_ayuda VALUES (395, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>A.Ambientales--><br />Listados--><br />Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 83, NULL);
INSERT INTO division_ayuda VALUES (396, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>A.Ambientals--><br />Llistat-->Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 83, NULL);
INSERT INTO division_ayuda VALUES (397, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>A.Ambientales--><br />Listados-->Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 84, NULL);
INSERT INTO division_ayuda VALUES (398, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>A.Ambientals--><br />Llistat-->Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 84, NULL);
INSERT INTO division_ayuda VALUES (399, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Procesos-><br />Catalogos--><br />Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 86, NULL);
INSERT INTO division_ayuda VALUES (400, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Processos--><br />Cat�logo-->Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 86, NULL);
INSERT INTO division_ayuda VALUES (401, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Procesos--><br />Cat�logo-->Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 87, NULL);
INSERT INTO division_ayuda VALUES (402, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Processos--><br />Cat�lago-->Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 87, NULL);
INSERT INTO division_ayuda VALUES (403, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Usuario--><br />Nuevo</b><br />
Rellene el formulario para registrar a un nuevo usuario. Dentro del formulario aparece el bot�n �Ver Permisos�. Pulsando este bot�n aparecer� un �rbol desplegable a la derecha del formulario, en donde el administrador podr� ver los permisos que posee el usuario que aparezca seleccionado en el campo �Perfil� de ese mismo formulario.</p>',
                                   91, NULL);
INSERT INTO division_ayuda VALUES (404, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Usuari--><br />Nou</b><br />
Empleni el formulari per a registrar a un nou usuari. Dintre del formulari apareix el bot� �Veure Permisos�. Prement aquest bot� apareixer� un arbre desplegable a la dreta del formulari, on l\\''administrador podr� veure els permisos que posse�x l\\''usuari que aparegui seleccionat en el camp �Perfil� d\\''aquest mateix formulari.</p>',
                                   91, NULL);
INSERT INTO division_ayuda VALUES (405, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Usuarios-->Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>
', 93, NULL);
INSERT INTO division_ayuda VALUES (406, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Usuaris-->Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 93, NULL);
INSERT INTO division_ayuda VALUES (407, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Usuario--><br />Password</b><br />
El usuario podr� cambiar la clave de acceso del usuario que haya seleccionado desde el formulario que aparecer� pulsando este bot�n.</p>',
                                   94, NULL);
INSERT INTO division_ayuda VALUES (408, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Usuari--><br />Password</b><br />
L\\''usuari podr� canviar la clau d\\''acc�s de l\\''usuari que hagi seleccionat des del formulari que apareixer� prement aquest bot�.</p>',
                                   94, NULL);
INSERT INTO division_ayuda VALUES (409, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Perfiles--><br />Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 95, NULL);
INSERT INTO division_ayuda VALUES (410, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Perfils-->Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 95, NULL);
INSERT INTO division_ayuda VALUES (411, 1, '<br /><b>Ayuda</b><br />
<p align="left"><b>Administraci�n--><br />Perfiles-->Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 97, NULL);
INSERT INTO division_ayuda VALUES (412, 2, '<br /><b>Ajuda</b><br />
<p align="left"><b>Administraci�--><br />Perfils-->Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 97, NULL);
INSERT INTO division_ayuda VALUES (413, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Mensajes--><br />Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 102, NULL);
INSERT INTO division_ayuda VALUES (414, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Missatges-->Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 102,
                                   NULL);
INSERT INTO division_ayuda VALUES (415, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Mensajes-->Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 104, NULL);
INSERT INTO division_ayuda VALUES (416, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Missatges-->Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 104, NULL);
INSERT INTO division_ayuda VALUES (417, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Criterios--><br />Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 115, NULL);
INSERT INTO division_ayuda VALUES (418, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Criterios-->Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 115,
                                   NULL);
INSERT INTO division_ayuda VALUES (419, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Criterios-->Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 116, NULL);
INSERT INTO division_ayuda VALUES (420, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Criterios-->Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 116, NULL);
INSERT INTO division_ayuda VALUES (421, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Mejora--><br />Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 117, NULL);
INSERT INTO division_ayuda VALUES (422, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Millora-->Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 117,
                                   NULL);
INSERT INTO division_ayuda VALUES (423, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Mejora-->Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 118, NULL);
INSERT INTO division_ayuda VALUES (424, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Millora-->Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 118, NULL);
INSERT INTO division_ayuda VALUES (425, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Cliente--><br />Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 119, NULL);
INSERT INTO division_ayuda VALUES (426, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Client-->Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 119,
                                   NULL);
INSERT INTO division_ayuda VALUES (427, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Cliente-->Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 120, NULL);
INSERT INTO division_ayuda VALUES (428, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Client-->Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 120, NULL);
INSERT INTO division_ayuda VALUES (429, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Tipo area--><br />Nuevo</b><br />
El usuario podr� cambiar la clave de acceso del usuario que haya seleccionado desde el formulario que aparecer� pulsando este bot�n.</p>',
                                   121, NULL);
INSERT INTO division_ayuda VALUES (430, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Tipo area--><br />Nou</b><br />
L\\''usuari podr� canviar la clau d\\''acc�s de l\\''usuari que hagi seleccionat des del formulari que apareixer� prement aquest bot�.</p>',
                                   121, NULL);
INSERT INTO division_ayuda VALUES (431, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Tipo area-->Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 122, NULL);
INSERT INTO division_ayuda VALUES (432, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Tipus area-->Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 122, NULL);
INSERT INTO division_ayuda VALUES (433, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Tipo ambiental--><br />Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 123, NULL);
INSERT INTO division_ayuda VALUES (434, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Tipus ambiental-->Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 123,
                                   NULL);
INSERT INTO division_ayuda VALUES (435, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Tipo ambiental-->Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 124, NULL);
INSERT INTO division_ayuda VALUES (436, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Tipus ambiental-->Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 124, NULL);
INSERT INTO division_ayuda VALUES (437, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Inicio--><br />Mensajes--><br />Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 125, NULL);
INSERT INTO division_ayuda VALUES (438, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Inicio--><br />Missatges-->Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 125,
                                   NULL);
INSERT INTO division_ayuda VALUES (439, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Indicadores--><br />Objetivos--><br />Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 128, NULL);
INSERT INTO division_ayuda VALUES (440, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Indicadors--><br />Objectius-->Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 128,
                                   NULL);
INSERT INTO division_ayuda VALUES (441, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Documentaci�n--><br />P.Ambiental--><br />Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 132, NULL);
INSERT INTO division_ayuda VALUES (442, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Documentaci�--><br />P.Ambiental-->Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 132,
                                   NULL);
INSERT INTO division_ayuda VALUES (443, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Documentaci�n--><br />Legislación--><br />R.F.L.-->Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 150, NULL);
INSERT INTO division_ayuda VALUES (444, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Documentaci�--><br />Legislaci�--><br />R.F.L.-->Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 150,
                                   NULL);
INSERT INTO division_ayuda VALUES (445, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Impacto ambiental--><br />Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 153, NULL);
INSERT INTO division_ayuda VALUES (446, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Impacte ambiental--><br />Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 153,
                                   NULL);
INSERT INTO division_ayuda VALUES (447, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Impacto ambiental-->Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 154, NULL);
INSERT INTO division_ayuda VALUES (448, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Impacte ambiental--><br />Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 154, NULL);
INSERT INTO division_ayuda VALUES (449, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Documentaci�n--><br />Objetivos--><br />Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 158, NULL);
INSERT INTO division_ayuda VALUES (450, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Documentaci�--><br />Objetius--><br />Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 158,
                                   NULL);
INSERT INTO division_ayuda VALUES (451, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Documentaci�n--><br />Objetivos--><br />Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 159, NULL);
INSERT INTO division_ayuda VALUES (452, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Documentaci�--><br />Objetius--><br />Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 159, NULL);
INSERT INTO division_ayuda VALUES (453, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Documentaci�n--><br />Manual--><br />Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 160, NULL);
INSERT INTO division_ayuda VALUES (454, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Documentaci�--><br />Manual--><br />Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 160,
                                   NULL);
INSERT INTO division_ayuda VALUES (455, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraic�n--><br />Tipo Curso--><br />Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 166, NULL);
INSERT INTO division_ayuda VALUES (456, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Tipus Curso--><br />Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 166,
                                   NULL);
INSERT INTO division_ayuda VALUES (457, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraic�n--><br />Tipo Curso--><br />Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 167, NULL);
INSERT INTO division_ayuda VALUES (458, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Tipus Curso--><br />Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 167, NULL);
INSERT INTO division_ayuda VALUES (459, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Documentaci�n--><br />Formatos--><br />Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 170, NULL);
INSERT INTO division_ayuda VALUES (460, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Documentaci�--><br />Formats--><br />Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 170,
                                   NULL);
INSERT INTO division_ayuda VALUES (461, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Documentaci�n--><br />Doc. AAI--><br />Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 173, NULL);
INSERT INTO division_ayuda VALUES (462, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Documentaci�--><br />Doc. AAI--><br />Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 173,
                                   NULL);
INSERT INTO division_ayuda VALUES (463, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Documentaci�n--><br />Legislación--><br />Normativa-->Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 174, NULL);
INSERT INTO division_ayuda VALUES (464, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Documentaci�--><br />Legislaci�--><br />Normativa-->Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 174,
                                   NULL);
INSERT INTO division_ayuda VALUES (465, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Tipo Documento--><br />Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 185, NULL);
INSERT INTO division_ayuda VALUES (466, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Tipus Document--><br />Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 185,
                                   NULL);
INSERT INTO division_ayuda VALUES (467, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraic�n--><br />Tipo Documento--><br />Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 186, NULL);
INSERT INTO division_ayuda VALUES (468, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Tipus Document--><br />Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 186, NULL);
INSERT INTO division_ayuda VALUES (469, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Men�--><br />Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 187, NULL);
INSERT INTO division_ayuda VALUES (470, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Menu--><br />Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 187,
                                   NULL);
INSERT INTO division_ayuda VALUES (471, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraic�n--><br />Men�--><br />Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 188, NULL);
INSERT INTO division_ayuda VALUES (472, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Menu--><br />Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 188, NULL);
INSERT INTO division_ayuda VALUES (473, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Idioma--><br />Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 192, NULL);
INSERT INTO division_ayuda VALUES (474, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Idioma--><br />Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 192,
                                   NULL);
INSERT INTO division_ayuda VALUES (475, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Asp.Amb Gravedad--><br />Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 204, NULL);
INSERT INTO division_ayuda VALUES (476, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Asp.Amb Gravetat--><br />Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 204,
                                   NULL);
INSERT INTO division_ayuda VALUES (477, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Asp.Amb Magnitud--><br />Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 205, NULL);
INSERT INTO division_ayuda VALUES (478, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Asp.Amb Magnitud--><br />Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 205,
                                   NULL);
INSERT INTO division_ayuda VALUES (479, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Asp.Amb Frecuencia--><br />Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 206, NULL);
INSERT INTO division_ayuda VALUES (480, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Asp.Amb Freq��ncia--><br />Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 206,
                                   NULL);
INSERT INTO division_ayuda VALUES (481, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraic�n--><br />Asp.Amb Gravedad--><br />Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 208, NULL);
INSERT INTO division_ayuda VALUES (482, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Asp.Amb Gravetat--><br />Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 208, NULL);
INSERT INTO division_ayuda VALUES (483, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraic�n--><br />Asp.Amb Magnitud--><br />Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 209, NULL);
INSERT INTO division_ayuda VALUES (485, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Asp.Amb Magnitud--><br />Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 209, NULL);
INSERT INTO division_ayuda VALUES (484, 1, '<br /><b>Ayuda</b><br />
<p align="left"><b>Administraic�n--><br />Asp.Amb Frecuencia--><br />Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 210, NULL);
INSERT INTO division_ayuda VALUES (486, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Asp.Amb Freq��ncia--><br />Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 210, NULL);
INSERT INTO division_ayuda VALUES (487, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraic�n--><br />F�rmula--><br />Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 213, NULL);
INSERT INTO division_ayuda VALUES (488, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />F�rmula--><br />Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 213, NULL);
INSERT INTO division_ayuda VALUES (489, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Asp.Amb Probabilidad--><br />Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 216, NULL);
INSERT INTO division_ayuda VALUES (490, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Asp.Amb Probabilitat--><br />Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 216,
                                   NULL);
INSERT INTO division_ayuda VALUES (491, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Asp.Amb Severidad--><br />Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 217, NULL);
INSERT INTO division_ayuda VALUES (492, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Asp.Amb Severitat--><br />Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 217,
                                   NULL);
INSERT INTO division_ayuda VALUES (493, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraic�n--><br />Asp.Amb Probabilidad--><br />Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 218, NULL);
INSERT INTO division_ayuda VALUES (494, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Asp.Amb Probabilitat--><br />Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 218, NULL);
INSERT INTO division_ayuda VALUES (495, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraic�n--><br />Asp.Amb Severidad--><br />Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p', 219, NULL);
INSERT INTO division_ayuda VALUES (496, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Asp.Amb Severitat--><br />Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 219, NULL);
INSERT INTO division_ayuda VALUES (497, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>A.Ambientales--><br />Asp.Amb. Emergencia--><br />Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 224, NULL);
INSERT INTO division_ayuda VALUES (498, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>A.Ambientals--><br />Asp.Amb Emerg�ncia--><br />Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 224,
                                   NULL);
INSERT INTO division_ayuda VALUES (499, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>A.Ambientales--><br />Asp.Amb. Emergencia--><br />Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 226, NULL);
INSERT INTO division_ayuda VALUES (500, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Asp.Amb Severitat--><br />Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 226,
                                   NULL);
INSERT INTO division_ayuda VALUES (501, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Documentaci�n--><br />Planes Emergencia--><br />Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 227, NULL);
INSERT INTO division_ayuda VALUES (502, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Documentaci�--><br />Plans Emerg�ncia--><br />Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 227,
                                   NULL);
INSERT INTO division_ayuda VALUES (503, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Ayuda--><br />Nuevo</b><br />
Inserte los campos necesarios en el formulario y pulse \\"Enviar\\" para actualizar la base de datos.</p>', 231, NULL);
INSERT INTO division_ayuda VALUES (504, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Ajuda--><br />Nou</b><br />
Insereixi els camps necessaris en el formulari i premi \\"Enviar\\" per a actualitzar la base de dades.</p>', 231,
                                   NULL);
INSERT INTO division_ayuda VALUES (505, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n--><br />Ayuda--><br />Editar</b><br />
Modifique los campos necesarios y pulse Enviar para actualizar los cambios.</p>', 232, NULL);
INSERT INTO division_ayuda VALUES (506, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Administraci�--><br />Ajuda--><br />Editar</b><br />
Modifiqui els camps necessaris i premi Enviar per a actualitzar els canvis.</p>', 232, NULL);
INSERT INTO division_ayuda VALUES (507, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Inicio</b><br />
Esta es la pagina de inicio de la aplicacion. Desde aqui se podra gestionar tanto la calidad como los aspectos ambientales de la empresa seleccionando cualquiera de los elementos del menu superior.</p>',
                                   NULL, 65);
INSERT INTO division_ayuda VALUES (508, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Inici</b><br />
Aquesta �s la pagina d\\''inici de la aplicacion. Des de aqui es podra gestionar tant la qualitat com els aspectes ambientals de l\\''empresa seleccionant qualsevol dels elements del menu superior.</p>',
                                   NULL, 65);
INSERT INTO division_ayuda VALUES (509, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Documentaci�n</b><br />
Modulo de Documentacion. Desde este modulo se puede acceder a toda la documentacion, registros y normativas de la empresa que se encuentren disponibles. Se pueden realizar operaciones como crear, ver, editar, revisar, aprobar documentos, y acceder a un registro con un listado de los mismos. Ademas, tambin se puede acceder a documentos externos y ver la legislacion vigente aplicable por la empresa.</p>',
                                   NULL, 66);
INSERT INTO division_ayuda VALUES (510, 1, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Documentaci�</b><br />
Modulo de Documentacion. Des d\\''aquest modulo es pot accedir a tota la documentacion, registres i normatives de l\\''empresa que es trobin disponibles. Es poden realitzar operacions com crear, veure, editar, revisar, aprovar documents, i accedir a un registre amb un llistat dels mateixos. Ademas, tambin es pot accedir a documents externs i veure la legislacion vigent aplicable per l\\''empresa.</p>',
                                   NULL, 66);
INSERT INTO division_ayuda VALUES (511, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Proveedores</b><br />
Modulo de Proveedores. Desde este modulo se pueden gestionar los proveedores y contactos de la empresa, los productos asi como las incidencias que hayan podido ocurrir.</p>',
                                   NULL, 67);
INSERT INTO division_ayuda VALUES (512, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Prove�dors</b><br />
Modulo de Prove�dors. Des d\\''aquest modulo es poden gestionar els prove�dors i contactes de l\\''empresa, els productes asi com les incid�ncies que hagin pogut oc�rrer.</p>',
                                   NULL, 67);
INSERT INTO division_ayuda VALUES (513, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Acciones Mejora</b><br />
Modulo de Acciones de Mejora. Desde este modulo, y a traves de formularios, se podran gestionar las acciones de mejora que se encuentren registradas, pudiendo crear nuevos registros, editar o verificar los ya existentes.</p>',
                                   NULL, 68);
INSERT INTO division_ayuda VALUES (514, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Accions Millora</b><br />
Modulo d\\''Accions de Millora. Des d\\''aquest modulo, i a traves de formularis, es podran gestionar les accions de millora que es trobin registrades, podent crear nous registres, editar o verificar els ja existents.</p>',
                                   NULL, 68);
INSERT INTO division_ayuda VALUES (515, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Formación</b><br />
Modulo de Formacion. Dentro de este modulo se encuentran las ofertas formativas a la que los usuarios pueden inscribirse. Tambien se puede acceder a la ficha de personal de cada empleado, y ver los requisitos del puesto de trabajo.</p>',
                                   NULL, 69);
INSERT INTO division_ayuda VALUES (516, 2, E'<br /><b>Ajuda</b><br />
<p align=\\"left\\"><b>Formaci�</b><br />
Modulo de Formacion. Dintre d\\''aquest modulo es troben les ofertes formatives a la qual els usuaris poden inscriure\\''s. Tambien es pot accedir a la fitxa de personal de cada empleat, i veure els requisits del lloc de treball.</p>',
                                   NULL, 69);
INSERT INTO division_ayuda VALUES (517, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Equipos</b><br />
Modulo de Equipos. Este modulo permite llevar un control de los equipos existentes en la empresa. En el tambien se pueden definir las revisiones y planes de mantenimiento que necesiten llevarse a cabo en cada equipo. Ademas, este modulo contiene un calendario anual en donde se podran sealar fechas relativas a la realizacion de los planes de mantenimiento o revisiones.</p>',
                                   NULL, 70);
INSERT INTO division_ayuda VALUES (518, 2, '<br /><b>Ajuda</b><br />
<p align="left"><b>Equips</b><br />
Modulo d''Equips. Aquest modulo permet dur un control dels equips existents en l''empresa. En el tambien es poden definir les revisions i plans de manteniment que necessitin portar-se a terme en cada equip. Ademas, aquest modulo cont� un calendari anual on es podran sealar dates relatives a la realizacion dels plans de manteniment o revisions.</p>',
                                   NULL, 70);
INSERT INTO division_ayuda VALUES (519, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Indicadores</b><br />
Modulo de Indicadores. Este modulo se encarga de registro de los indicadores de proceso. Desde aqui un usuario puede crear nuevos indicadores, editar o dar de baja los ya existentes.</p>',
                                   NULL, 72);
INSERT INTO division_ayuda VALUES (520, 2, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Indicadors</b><br />
Modulo d\\''Indicadors. Aquest modulo s\\''encarrega de registre dels indicadors de proc�s. Des de aqui un usuari pot crear nous indicadors, editar o donar de baixa els ja existents.</p>',
                                   NULL, 72);
INSERT INTO division_ayuda VALUES (521, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�n</b><br />
Modulo de Administracion de la Aplicacion. En esta seccion se puede modificar todo lo referente a la informacion de los usuarios registrados, sus claves de acceso, sus perfiles y permisos de acceso. Desde aqui tambien se puede dar de alta o baja a las areas existentes, administrar los mensajes de los usuarios o las tareas a realizar por los mismos.</p>',
                                   NULL, 74);
INSERT INTO division_ayuda VALUES (522, 2, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Administraci�</b><br />
Modulo de Administracion de la Aplicacion. En aquesta seccion es pot modificar tot el referent a la informacion dels usuaris registrats, les seves claus d\\''acc�s, els seus perfils i permisos d\\''acc�s. Des de aqui tambien es pot donar d\\''alta o baixa a les areas existents, administrar els missatges dels usuaris o les tasques a realitzar pels mateixos.</p>',
                                   NULL, 74);
INSERT INTO division_ayuda VALUES (523, 1, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Procesos</b><br />
M�dulo de Procesos. Este modulo es el encargado de gestionar los procedimientos internos de la empresa. Entre otras operaciones, se podran crear, modificar y gestionar procesos, fichas, indicadores e incluso documentos anexos.</p>',
                                   NULL, 76);
INSERT INTO division_ayuda VALUES (524, 2, E'<br /><b>Ayuda</b><br />
<p align=\\"left\\"><b>Processos</b><br />
M�dul de Processos. Aquest modulo �s l\\''encarregat de gestionar els procediments interns de l\\''empresa. Entre altres operacions, es podran crear, modificar i gestionar processos, fitxes, indicadors i fins i tot documents annexos.</p>',
                                   NULL, 76);

--
-- Data for Name: documentos; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: equipos; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: estados_documento; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO estados_documento VALUES (1, 'Vigor');
INSERT INTO estados_documento VALUES (2, 'Borrador');
INSERT INTO estados_documento VALUES (3, 'Pendiente Revision');
INSERT INTO estados_documento VALUES (4, 'Revisado');
INSERT INTO estados_documento VALUES (5, 'Pendiente Aprobacion');
INSERT INTO estados_documento VALUES (6, 'Historico');

--
-- Data for Name: ficha_personal; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: ficha_personal_cambio_departamento; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: ficha_personal_cambio_perfil; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: ficha_personal_datos_personales; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO ficha_personal_datos_personales VALUES
  ('Luis', 'Amigo', '1970-09-30', 'Merida', 'Merida', TRUE, 'Mu bueno', 1, 'Calle Desenga�o 21', '245132', 'Malaga',
   'Malaga');
INSERT INTO ficha_personal_datos_personales VALUES
  ('sdfas', 'sdafsdfasdfsdf', '2006-10-04', 'saf', 'sdfas', TRUE, 'fsdfsdfds', 4, 'sdfasdf', 'e234', 'fsdf',
   'sdfsdfsdfs');
INSERT INTO ficha_personal_datos_personales VALUES
  ('uff', 'pecha de come', '2006-10-28', 'here', 'no', TRUE, 'sdffdasf', 5, 'wher', '124143', 'afdasf', 'asfdsfasf');

--
-- Data for Name: ficha_personal_experiencia_laboral; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: ficha_personal_formacion_academica; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO ficha_personal_formacion_academica VALUES ('asdfs', 'sdfasdfsd', 'fasdf', 4);
INSERT INTO ficha_personal_formacion_academica VALUES ('sdffs', 'affd', 'fdfas', 5);

--
-- Data for Name: ficha_personal_formacion_tecnica; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: ficha_personal_idiomas; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO ficha_personal_idiomas VALUES ('asdf', 'sadfsd', 'fasdfas', 'dfsdf', 4);
INSERT INTO ficha_personal_idiomas VALUES ('afdsf', 'dsafdsf', 'sdf', NULL, 5);

--
-- Data for Name: ficha_personal_incorporacion; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO ficha_personal_incorporacion
VALUES ('Islanda', '2006-09-01 00:00:00', 'Administrador, programador y lo que salga', 'Programacion', 1);
INSERT INTO ficha_personal_incorporacion VALUES ('sdfsdfsadfs', '2006-10-04 00:00:00', 'asdf', 'asdf', 4);
INSERT INTO ficha_personal_incorporacion VALUES ('jar', '2006-10-26 00:00:00', 'gefgfg', 'qqqweqw', 5);

--
-- Data for Name: ficha_personal_otros_cursos; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: ficha_personal_preformacion; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO ficha_personal_preformacion VALUES ('asdfsdf', 'asdfsadfadaf', 'sdfsdafsdf', 4);
INSERT INTO ficha_personal_preformacion VALUES ('asdfdsf', 'sdafasdf', 'asdfdasf', 5);

--
-- Data for Name: flujogramas; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: formula_aspectos; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO formula_aspectos VALUES ('tipo_probabilidad.valor*tipo_severidad.valor', 2);
INSERT INTO formula_aspectos VALUES ('tipo_frecuencia.valor+(2*tipo_gravedad.valor)+tipo_magnitud.valor', 1);

--
-- Data for Name: historico_cuestionarios; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: historico_productos; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: horario_auditoria; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: hospitales; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO hospitales VALUES (2, 'Clinico', TRUE, '32164702f8ffd2b418d780ff02371e4c');
INSERT INTO hospitales VALUES (1, 'Carlos Haya', TRUE, 'dc599a9972fde3045dab59dbd1ae170b');
INSERT INTO hospitales VALUES (3, 'El materno', TRUE, 'eeafbf4d9b3957b139da7b7f2e7f2d4a');

--
-- Data for Name: idiomas; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO idiomas VALUES (1, 'castellano');
INSERT INTO idiomas VALUES (2, 'catalan');

--
-- Data for Name: incidencias; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: indicadores; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: legislacion_aplicable; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: mantenimientos; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: mensajes; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: menu_idiomas_nuevo; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO menu_idiomas_nuevo VALUES (5, 'Usuarios', 32, 1);
INSERT INTO menu_idiomas_nuevo VALUES (1, 'Matriz A. Amb.', 27, 1);
INSERT INTO menu_idiomas_nuevo VALUES (2, 'Calendario', 22, 1);
INSERT INTO menu_idiomas_nuevo VALUES (3, 'Ficha Personal', 15, 1);
INSERT INTO menu_idiomas_nuevo VALUES (4, 'Req. Puesto', 16, 1);
INSERT INTO menu_idiomas_nuevo VALUES (6, 'Perfiles', 33, 1);
INSERT INTO menu_idiomas_nuevo VALUES (7, 'Mensajes', 35, 1);
INSERT INTO menu_idiomas_nuevo VALUES (8, 'Tareas', 36, 1);
INSERT INTO menu_idiomas_nuevo VALUES (9, 'Leg. Aplicable', 55, 1);
INSERT INTO menu_idiomas_nuevo VALUES (10, 'T. Amb. Aplicacion', 44, 1);
INSERT INTO menu_idiomas_nuevo VALUES (11, 'Tipos Area', 43, 1);
INSERT INTO menu_idiomas_nuevo VALUES (12, 'Tipos Imp. Amb.', 58, 1);
INSERT INTO menu_idiomas_nuevo VALUES (13, 'Listado', 14, 1);
INSERT INTO menu_idiomas_nuevo VALUES (15, 'Programa Anual', 23, 1);
INSERT INTO menu_idiomas_nuevo VALUES (16, 'Registros', 11, 1);
INSERT INTO menu_idiomas_nuevo VALUES (17, 'Doc. y Formatos', 13, 1);
INSERT INTO menu_idiomas_nuevo VALUES (18, 'Manual', 3, 1);
INSERT INTO menu_idiomas_nuevo VALUES (19, 'P.G.', 7, 1);
INSERT INTO menu_idiomas_nuevo VALUES (21, 'Doc. Borrador', 57, 1);
INSERT INTO menu_idiomas_nuevo VALUES (23, 'Revisiones', 21, 1);
INSERT INTO menu_idiomas_nuevo VALUES (24, 'Cursos', 17, 1);
INSERT INTO menu_idiomas_nuevo VALUES (25, 'Inscripcion', 18, 1);
INSERT INTO menu_idiomas_nuevo VALUES (27, 'Indicadores', 31, 1);
INSERT INTO menu_idiomas_nuevo VALUES (28, 'Objetivos', 50, 1);
INSERT INTO menu_idiomas_nuevo VALUES (29, 'Mensajes', 1, 1);
INSERT INTO menu_idiomas_nuevo VALUES (30, 'Tareas', 2, 1);
INSERT INTO menu_idiomas_nuevo VALUES (31, 'Catalogo', 28, 1);
INSERT INTO menu_idiomas_nuevo VALUES (32, 'Listado', 45, 1);
INSERT INTO menu_idiomas_nuevo VALUES (33, 'Incidencias', 46, 1);
INSERT INTO menu_idiomas_nuevo VALUES (34, 'Contactos', 47, 1);
INSERT INTO menu_idiomas_nuevo VALUES (35, 'Productos', 48, 1);
INSERT INTO menu_idiomas_nuevo VALUES (36, 'P. Homologados', 49, 1);
INSERT INTO menu_idiomas_nuevo VALUES (37, 'Registros', 38, 1);
INSERT INTO menu_idiomas_nuevo VALUES (38, 'Tipos Acc. Mejora', 41, 1);
INSERT INTO menu_idiomas_nuevo VALUES (39, 'Documentos S.G', 37, 1);
INSERT INTO menu_idiomas_nuevo VALUES (40, 'Doc. y Formatos', 39, 1);
INSERT INTO menu_idiomas_nuevo VALUES (41, 'Tipo Cursos', 61, 1);
INSERT INTO menu_idiomas_nuevo VALUES (42, 'Objetivos', 5, 1);
INSERT INTO menu_idiomas_nuevo VALUES (43, 'Politica', 4, 1);
INSERT INTO menu_idiomas_nuevo VALUES (44, 'P.O.', 8, 1);
INSERT INTO menu_idiomas_nuevo VALUES (46, 'Normativa', 63, 1);
INSERT INTO menu_idiomas_nuevo VALUES (47, 'F.R.L', 9, 1);
INSERT INTO menu_idiomas_nuevo VALUES (48, 'Programa en Vigor', 24, 1);
INSERT INTO menu_idiomas_nuevo VALUES (49, 'Listado', 12, 1);
INSERT INTO menu_idiomas_nuevo VALUES (50, 'Criterios', 40, 1);
INSERT INTO menu_idiomas_nuevo VALUES (26, 'Planes Form.', 19, 1);
INSERT INTO menu_idiomas_nuevo VALUES (103, 'Tipo documento', 64, 1);
INSERT INTO menu_idiomas_nuevo VALUES (111, 'ACC. MEJORA', 68, 1);
INSERT INTO menu_idiomas_nuevo VALUES (113, 'FORMACION', 69, 1);
INSERT INTO menu_idiomas_nuevo VALUES (115, 'EQUIPOS', 70, 1);
INSERT INTO menu_idiomas_nuevo VALUES (117, 'AUDITORIAS', 71, 1);
INSERT INTO menu_idiomas_nuevo VALUES (119, 'INDICADORES', 72, 1);
INSERT INTO menu_idiomas_nuevo VALUES (121, 'A. AMBIENTALES', 73, 1);
INSERT INTO menu_idiomas_nuevo VALUES (123, 'ADMINISTRACION', 74, 1);
INSERT INTO menu_idiomas_nuevo VALUES (105, 'INICIO', 65, 1);
INSERT INTO menu_idiomas_nuevo VALUES (107, 'DOCUMENTACION', 66, 1);
INSERT INTO menu_idiomas_nuevo VALUES (109, 'PROVEEDORES', 67, 1);
INSERT INTO menu_idiomas_nuevo VALUES (125, 'CERRAR SESION', 75, 1);
INSERT INTO menu_idiomas_nuevo VALUES (127, 'PROCESOS', 76, 1);
INSERT INTO menu_idiomas_nuevo VALUES (129, 'Documentos S.G.', 77, 1);
INSERT INTO menu_idiomas_nuevo VALUES (131, 'Legislacion', 78, 1);
INSERT INTO menu_idiomas_nuevo VALUES (133, 'Documentos Asociados', 79, 1);
INSERT INTO menu_idiomas_nuevo VALUES (135, 'Recursos', 80, 1);
INSERT INTO menu_idiomas_nuevo VALUES (137, 'Formacion', 81, 1);
INSERT INTO menu_idiomas_nuevo VALUES (139, 'Aplicacion', 82, 1);
INSERT INTO menu_idiomas_nuevo VALUES (141, 'Documentos', 83, 1);
INSERT INTO menu_idiomas_nuevo VALUES (143, 'Criterios', 84, 1);
INSERT INTO menu_idiomas_nuevo VALUES (145, 'Tipos Acc. Mejora', 85, 1);
INSERT INTO menu_idiomas_nuevo VALUES (147, 'Clientes', 86, 1);
INSERT INTO menu_idiomas_nuevo VALUES (149, 'Tipos area', 87, 1);
INSERT INTO menu_idiomas_nuevo VALUES (151, 'T. Amb. Aplicacion', 88, 1);
INSERT INTO menu_idiomas_nuevo VALUES (153, 'Log. Aplicable', 89, 1);
INSERT INTO menu_idiomas_nuevo VALUES (155, 'Tipos Imp. Amb.', 90, 1);
INSERT INTO menu_idiomas_nuevo VALUES (157, 'Formacion', 91, 1);
INSERT INTO menu_idiomas_nuevo VALUES (159, 'Tipo documento', 92, 1);
INSERT INTO menu_idiomas_nuevo VALUES (162, 'Menus', 93, 1);
INSERT INTO menu_idiomas_nuevo VALUES (52, 'Matriu A. Amb.', 27, 2);
INSERT INTO menu_idiomas_nuevo VALUES (53, 'Calendari', 22, 2);
INSERT INTO menu_idiomas_nuevo VALUES (54, 'Fitxa Personal', 15, 2);
INSERT INTO menu_idiomas_nuevo VALUES (55, 'Req. Lloc', 16, 2);
INSERT INTO menu_idiomas_nuevo VALUES (56, 'Usuaris', 32, 2);
INSERT INTO menu_idiomas_nuevo VALUES (57, 'Perfils', 33, 2);
INSERT INTO menu_idiomas_nuevo VALUES (58, 'Missatges', 35, 2);
INSERT INTO menu_idiomas_nuevo VALUES (59, 'Tasques', 36, 2);
INSERT INTO menu_idiomas_nuevo VALUES (60, 'Leg. Aplicable', 55, 2);
INSERT INTO menu_idiomas_nuevo VALUES (61, 'T. Amb. Aplicacion', 44, 2);
INSERT INTO menu_idiomas_nuevo VALUES (62, 'Tipus Area', 43, 2);
INSERT INTO menu_idiomas_nuevo VALUES (63, 'Tipus Imp. Amb.', 58, 2);
INSERT INTO menu_idiomas_nuevo VALUES (64, 'Llistat', 14, 2);
INSERT INTO menu_idiomas_nuevo VALUES (66, 'Programa Anual', 23, 2);
INSERT INTO menu_idiomas_nuevo VALUES (67, 'Registres', 11, 2);
INSERT INTO menu_idiomas_nuevo VALUES (68, 'Doc. i Formats', 13, 2);
INSERT INTO menu_idiomas_nuevo VALUES (69, 'Manual', 3, 2);
INSERT INTO menu_idiomas_nuevo VALUES (70, 'P.G.', 7, 2);
INSERT INTO menu_idiomas_nuevo VALUES (71, 'Doc. Vigor', 10, 2);
INSERT INTO menu_idiomas_nuevo VALUES (72, 'Doc. Esborrador', 57, 2);
INSERT INTO menu_idiomas_nuevo VALUES (73, 'Llistat', 20, 2);
INSERT INTO menu_idiomas_nuevo VALUES (74, 'Revisions', 21, 2);
INSERT INTO menu_idiomas_nuevo VALUES (75, 'Cursos', 17, 2);
INSERT INTO menu_idiomas_nuevo VALUES (76, 'Inscripcion', 18, 2);
INSERT INTO menu_idiomas_nuevo VALUES (78, 'Indicadors', 31, 2);
INSERT INTO menu_idiomas_nuevo VALUES (79, 'Objectius', 50, 2);
INSERT INTO menu_idiomas_nuevo VALUES (80, 'Missatges', 1, 2);
INSERT INTO menu_idiomas_nuevo VALUES (81, 'Tasques', 2, 2);
INSERT INTO menu_idiomas_nuevo VALUES (82, 'Catalogo', 28, 2);
INSERT INTO menu_idiomas_nuevo VALUES (83, 'Llistat', 45, 2);
INSERT INTO menu_idiomas_nuevo VALUES (84, 'Incid�ncies', 46, 2);
INSERT INTO menu_idiomas_nuevo VALUES (85, 'Contactes', 47, 2);
INSERT INTO menu_idiomas_nuevo VALUES (86, 'Productes', 48, 2);
INSERT INTO menu_idiomas_nuevo VALUES (87, 'P. Homologats', 49, 2);
INSERT INTO menu_idiomas_nuevo VALUES (88, 'Registres', 38, 2);
INSERT INTO menu_idiomas_nuevo VALUES (89, 'Tipus Acc. Millora', 41, 2);
INSERT INTO menu_idiomas_nuevo VALUES (90, 'Documents S.G ', 37, 2);
INSERT INTO menu_idiomas_nuevo VALUES (91, 'Doc. i Formats', 39, 2);
INSERT INTO menu_idiomas_nuevo VALUES (92, 'Tipus Cursos', 61, 2);
INSERT INTO menu_idiomas_nuevo VALUES (93, 'Objectius', 5, 2);
INSERT INTO menu_idiomas_nuevo VALUES (94, 'Politica', 4, 2);
INSERT INTO menu_idiomas_nuevo VALUES (95, 'P.O.', 8, 2);
INSERT INTO menu_idiomas_nuevo VALUES (97, 'Normativa', 63, 2);
INSERT INTO menu_idiomas_nuevo VALUES (98, 'F.R.L', 9, 2);
INSERT INTO menu_idiomas_nuevo VALUES (99, 'Programa en Vigor', 24, 2);
INSERT INTO menu_idiomas_nuevo VALUES (100, 'Llistat', 12, 2);
INSERT INTO menu_idiomas_nuevo VALUES (101, 'Criteris', 40, 2);
INSERT INTO menu_idiomas_nuevo VALUES (102, 'Clients', 42, 2);
INSERT INTO menu_idiomas_nuevo VALUES (77, 'Plans Form.', 19, 2);
INSERT INTO menu_idiomas_nuevo VALUES (104, 'Tipus documento', 64, 2);
INSERT INTO menu_idiomas_nuevo VALUES (112, 'ACC. MILLORA', 68, 2);
INSERT INTO menu_idiomas_nuevo VALUES (114, 'FORMACION', 69, 2);
INSERT INTO menu_idiomas_nuevo VALUES (116, 'EQUIPS', 70, 2);
INSERT INTO menu_idiomas_nuevo VALUES (118, 'AUDITORIAS', 71, 2);
INSERT INTO menu_idiomas_nuevo VALUES (120, 'INDICADORS', 72, 2);
INSERT INTO menu_idiomas_nuevo VALUES (122, 'A. AMBIENTALES', 73, 2);
INSERT INTO menu_idiomas_nuevo VALUES (124, 'ADMINISTRACION', 74, 2);
INSERT INTO menu_idiomas_nuevo VALUES (106, 'INICI', 65, 2);
INSERT INTO menu_idiomas_nuevo VALUES (108, 'DOCUMENTACION', 66, 2);
INSERT INTO menu_idiomas_nuevo VALUES (110, 'PROVE�DORS', 67, 2);
INSERT INTO menu_idiomas_nuevo VALUES (126, 'TANCAR SESION', 75, 2);
INSERT INTO menu_idiomas_nuevo VALUES (128, 'PROCESOS', 76, 2);
INSERT INTO menu_idiomas_nuevo VALUES (130, 'Documents S.G.', 77, 2);
INSERT INTO menu_idiomas_nuevo VALUES (132, 'Legislacion', 78, 2);
INSERT INTO menu_idiomas_nuevo VALUES (134, 'Documents Associats', 79, 2);
INSERT INTO menu_idiomas_nuevo VALUES (136, 'Recursos', 80, 2);
INSERT INTO menu_idiomas_nuevo VALUES (138, 'Formacion', 81, 2);
INSERT INTO menu_idiomas_nuevo VALUES (140, 'Aplicacion', 82, 2);
INSERT INTO menu_idiomas_nuevo VALUES (142, 'Documents', 83, 2);
INSERT INTO menu_idiomas_nuevo VALUES (144, 'Criteris', 84, 2);
INSERT INTO menu_idiomas_nuevo VALUES (146, 'Tipus Acc. Millora', 85, 2);
INSERT INTO menu_idiomas_nuevo VALUES (148, 'Clients', 86, 2);
INSERT INTO menu_idiomas_nuevo VALUES (150, 'Tipus area', 87, 2);
INSERT INTO menu_idiomas_nuevo VALUES (152, 'T. Amb. Aplicacion', 88, 2);
INSERT INTO menu_idiomas_nuevo VALUES (154, 'Log. Aplicable', 89, 2);
INSERT INTO menu_idiomas_nuevo VALUES (156, 'Tipus Imp.Amb.', 90, 2);
INSERT INTO menu_idiomas_nuevo VALUES (158, 'Formacion', 91, 2);
INSERT INTO menu_idiomas_nuevo VALUES (160, 'Tipus document', 92, 2);
INSERT INTO menu_idiomas_nuevo VALUES (161, 'Menus', 93, 2);
INSERT INTO menu_idiomas_nuevo VALUES (163, 'Idiomas', 94, 1);
INSERT INTO menu_idiomas_nuevo VALUES (164, 'Idiomes', 94, 2);
INSERT INTO menu_idiomas_nuevo VALUES (14, 'Aspectos Ambientales', 26, 1);
INSERT INTO menu_idiomas_nuevo VALUES (65, 'Aspectes Ambientals', 26, 2);
INSERT INTO menu_idiomas_nuevo VALUES (165, 'Aspectos Ambientales', 95, 1);
INSERT INTO menu_idiomas_nuevo VALUES (166, 'Aspectes Ambientals', 95, 2);
INSERT INTO menu_idiomas_nuevo VALUES (175, 'Formula', 100, 1);
INSERT INTO menu_idiomas_nuevo VALUES (176, 'Formula', 100, 2);
INSERT INTO menu_idiomas_nuevo VALUES (177, 'Aspectos Ambientales Emergencia', 101, 1);
INSERT INTO menu_idiomas_nuevo VALUES (178, 'Aspectes Ambientals Emergencia', 101, 2);
INSERT INTO menu_idiomas_nuevo VALUES (179, 'Probabilidad', 102, 1);
INSERT INTO menu_idiomas_nuevo VALUES (180, 'Probabilitat', 102, 2);
INSERT INTO menu_idiomas_nuevo VALUES (183, 'Plan Emerg. Amb.', 104, 1);
INSERT INTO menu_idiomas_nuevo VALUES (184, 'Pla Emerg. Amb.', 104, 2);
INSERT INTO menu_idiomas_nuevo VALUES (185, 'Ayuda', 106, 1);
INSERT INTO menu_idiomas_nuevo VALUES (186, 'Ajuda', 106, 2);
INSERT INTO menu_idiomas_nuevo VALUES (45, 'Analisis Amb. Inicial', 62, 1);
INSERT INTO menu_idiomas_nuevo VALUES (96, 'Analisis Amb. Inicial', 62, 2);
INSERT INTO menu_idiomas_nuevo VALUES (187, 'Permisos', 107, 1);
INSERT INTO menu_idiomas_nuevo VALUES (188, 'Permisos', 107, 2);
INSERT INTO menu_idiomas_nuevo VALUES (51, 'Clientes', 42, 1);
INSERT INTO menu_idiomas_nuevo VALUES (189, 'Hospitales', 108, 1);
INSERT INTO menu_idiomas_nuevo VALUES (190, 'Hospitals', 108, 2);
INSERT INTO menu_idiomas_nuevo VALUES (167, 'Cantidad', 96, 1);
INSERT INTO menu_idiomas_nuevo VALUES (168, 'Quantitat', 96, 2);
INSERT INTO menu_idiomas_nuevo VALUES (169, 'Severidad', 97, 1);
INSERT INTO menu_idiomas_nuevo VALUES (170, 'Severitat', 97, 2);
INSERT INTO menu_idiomas_nuevo VALUES (172, 'Frec./Severidad', 98, 1);
INSERT INTO menu_idiomas_nuevo VALUES (171, 'Freq./Severitat', 98, 2);
INSERT INTO menu_idiomas_nuevo VALUES (181, 'Sever./Emergencia', 103, 1);
INSERT INTO menu_idiomas_nuevo VALUES (182, 'Sever./Emergencia', 103, 2);
INSERT INTO menu_idiomas_nuevo VALUES (20, 'Doc. Vigor', 10, 1);
INSERT INTO menu_idiomas_nuevo VALUES (22, 'Listado', 20, 1);
INSERT INTO menu_idiomas_nuevo VALUES (193, 'Proveedores', 109, 1);
INSERT INTO menu_idiomas_nuevo VALUES (194, 'Proveidors', 109, 2);
INSERT INTO menu_idiomas_nuevo VALUES (195, 'Listado', 110, 1);
INSERT INTO menu_idiomas_nuevo VALUES (196, 'Llistat', 110, 2);
INSERT INTO menu_idiomas_nuevo VALUES (197, 'Incidencias', 111, 1);
INSERT INTO menu_idiomas_nuevo VALUES (198, 'Incidencies', 111, 2);
INSERT INTO menu_idiomas_nuevo VALUES (199, 'Contactos', 112, 1);
INSERT INTO menu_idiomas_nuevo VALUES (200, 'Contactes', 112, 2);
INSERT INTO menu_idiomas_nuevo VALUES (201, 'Productos', 113, 1);
INSERT INTO menu_idiomas_nuevo VALUES (202, 'Productes', 113, 2);
INSERT INTO menu_idiomas_nuevo VALUES (203, 'Equipos', 114, 1);
INSERT INTO menu_idiomas_nuevo VALUES (204, 'Equips', 114, 2);
INSERT INTO menu_idiomas_nuevo VALUES (205, 'Listado', 115, 1);
INSERT INTO menu_idiomas_nuevo VALUES (206, 'Llistat', 115, 2);
INSERT INTO menu_idiomas_nuevo VALUES (207, 'Auditorias', 116, 1);
INSERT INTO menu_idiomas_nuevo VALUES (208, 'Auditorias', 116, 2);
INSERT INTO menu_idiomas_nuevo VALUES (209, 'Auditoria anual', 117, 1);
INSERT INTO menu_idiomas_nuevo VALUES (210, 'Auditoria anual', 117, 2);
INSERT INTO menu_idiomas_nuevo VALUES (211, 'Auditoria en vigor', 118, 1);
INSERT INTO menu_idiomas_nuevo VALUES (212, 'Auditoria en vigor', 118, 2);
INSERT INTO menu_idiomas_nuevo VALUES (213, 'Indicadores', 119, 1);
INSERT INTO menu_idiomas_nuevo VALUES (214, 'Indicadors', 119, 2);
INSERT INTO menu_idiomas_nuevo VALUES (215, 'Objetivos', 120, 1);
INSERT INTO menu_idiomas_nuevo VALUES (216, 'Objectius', 120, 2);

--
-- Data for Name: menu_nuevo; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO menu_nuevo VALUES (76, 'procesos', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 0, 2);
INSERT INTO menu_nuevo
VALUES (28, 'procesos:catalogos:arbol:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 76, 3);
INSERT INTO menu_nuevo VALUES (67, 'proveedores', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 0, 4);
INSERT INTO menu_nuevo
VALUES (45, 'proveedores:listado:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 67, 5);
INSERT INTO menu_nuevo
VALUES (46, 'proveedores:incidencias:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 67, 6);
INSERT INTO menu_nuevo
VALUES (47, 'proveedores:contactos:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 67, 7);
INSERT INTO menu_nuevo
VALUES (48, 'proveedores:productos:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 67, 8);
INSERT INTO menu_nuevo
VALUES (49, 'proveedores:phomologados:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 67, 9);
INSERT INTO menu_nuevo VALUES (70, 'equipos', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 0, 10);
INSERT INTO menu_nuevo
VALUES (22, 'equipos:calendario:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 70, 11);
INSERT INTO menu_nuevo
VALUES (21, 'equipos:revision:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 70, 12);
INSERT INTO menu_nuevo
VALUES (20, 'equipos:listado:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 70, 13);
INSERT INTO menu_nuevo VALUES (68, 'mejora', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 0, 14);
INSERT INTO menu_nuevo
VALUES (14, 'mejora:listado:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 68, 15);
INSERT INTO menu_nuevo VALUES (69, 'formacion', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 0, 16);
INSERT INTO menu_nuevo VALUES (80, NULL, '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 69, 17);
INSERT INTO menu_nuevo
VALUES (15, 'formacion:fichapersonal:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 80, 18);
INSERT INTO menu_nuevo
VALUES (16, 'formacion:reqpuesto:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 80, 19);
INSERT INTO menu_nuevo VALUES (81, NULL, '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 69, 20);
INSERT INTO menu_nuevo
VALUES (17, 'formacion:cursos:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 81, 21);
INSERT INTO menu_nuevo
VALUES (18, 'formacion:inscripcion:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 81, 22);
INSERT INTO menu_nuevo
VALUES (19, 'formacion:planes:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 81, 23);
INSERT INTO menu_nuevo VALUES (71, 'auditorias', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 0, 24);
INSERT INTO menu_nuevo
VALUES (23, 'auditorias:programa:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 71, 25);
INSERT INTO menu_nuevo
VALUES (24, 'auditorias:plan:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 71, 26);
INSERT INTO menu_nuevo VALUES (72, 'indicadores', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 0, 27);
INSERT INTO menu_nuevo
VALUES (31, 'indicadores:indicadores:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 72, 28);
INSERT INTO menu_nuevo
VALUES (50, 'indicadores:objetivos:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 72, 29);
INSERT INTO menu_nuevo VALUES (73, 'maspectos', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 0, 30);
INSERT INTO menu_nuevo
VALUES (27, 'aambientales:matriz:detalles:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 73, 31);
INSERT INTO menu_nuevo
VALUES (26, 'aambientales:revision:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 73, 32);
INSERT INTO menu_nuevo
VALUES (101, 'aambientales:revisionemergencia:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 73, 33);
INSERT INTO menu_nuevo VALUES (74, 'administracion', '[0:22]={f,f,f,f,f,f,f,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 0, 34);
INSERT INTO menu_nuevo VALUES (86, NULL, '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 74, 35);
INSERT INTO menu_nuevo
VALUES (42, 'administracion:clientes:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 86, 36);
INSERT INTO menu_nuevo VALUES (84, NULL, '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 74, 37);
INSERT INTO menu_nuevo
VALUES (40, 'administracion:criterios:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 84, 38);
INSERT INTO menu_nuevo VALUES (82, NULL, '[0:22]={f,f,f,f,f,f,f,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 74, 39);
INSERT INTO menu_nuevo
VALUES (32, 'administracion:usuarios:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 82, 40);
INSERT INTO menu_nuevo
VALUES (33, 'administracion:perfiles:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 82, 41);
INSERT INTO menu_nuevo
VALUES (35, 'administracion:mensajes:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 82, 42);
INSERT INTO menu_nuevo
VALUES (36, 'administracion:tareas:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 82, 43);
INSERT INTO menu_nuevo
VALUES (93, 'administracion:menus:listado:nuevo', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 82, 44);
INSERT INTO menu_nuevo
VALUES (94, 'administracion:idiomas:listado:nuevo', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 82, 45);
INSERT INTO menu_nuevo
VALUES (108, 'administracion:hospitales:listado:nuevo', '{f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 82, 46);
INSERT INTO menu_nuevo VALUES (83, NULL, '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 74, 47);
INSERT INTO menu_nuevo
VALUES (38, 'administracion:registros:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 83, 48);
INSERT INTO menu_nuevo
VALUES (37, 'administracion:documentossg:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 83, 49);
INSERT INTO menu_nuevo
VALUES (39, 'administracion:normativa:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 83, 50);
INSERT INTO menu_nuevo VALUES (85, NULL, '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 74, 51);
INSERT INTO menu_nuevo
VALUES (41, 'administracion:tipomejora:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 85, 52);
INSERT INTO menu_nuevo VALUES (87, NULL, '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 74, 53);
INSERT INTO menu_nuevo
VALUES (43, 'administracion:tiposareas:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 87, 54);
INSERT INTO menu_nuevo VALUES (88, NULL, '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 74, 55);
INSERT INTO menu_nuevo
VALUES (44, 'administracion:tiposamb:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 88, 56);
INSERT INTO menu_nuevo VALUES (89, NULL, '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 74, 57);
INSERT INTO menu_nuevo
VALUES (55, 'administracion:legaplicable:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 89, 58);
INSERT INTO menu_nuevo VALUES (90, NULL, '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 74, 59);
INSERT INTO menu_nuevo
VALUES (58, 'administracion:tiposimp:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 90, 60);
INSERT INTO menu_nuevo VALUES (91, NULL, '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 74, 61);
INSERT INTO menu_nuevo
VALUES (61, 'administracion:tipo_cursos:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 91, 62);
INSERT INTO menu_nuevo VALUES (92, NULL, '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 74, 63);
INSERT INTO menu_nuevo
VALUES (64, 'administracion:tipodocumento:listado:nuevo', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 92, 64);
INSERT INTO menu_nuevo VALUES (95, '', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 74, 65);
INSERT INTO menu_nuevo
VALUES (100, 'administracion:aspectos:listado:formula', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 95, 66);
INSERT INTO menu_nuevo
VALUES (102, 'administracion:aspectos:listado:probabilidad', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 95, 67);
INSERT INTO menu_nuevo
VALUES (96, 'administracion:aspectos:listado:magnitud', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 95, 68);
INSERT INTO menu_nuevo
VALUES (97, 'administracion:aspectos:listado:gravedad', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 95, 69);
INSERT INTO menu_nuevo
VALUES (98, 'administracion:aspectos:listado:frecuencia', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 95, 70);
INSERT INTO menu_nuevo
VALUES (103, 'administracion:aspectos:listado:severidad', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 95, 71);
INSERT INTO menu_nuevo
VALUES (106, 'administracion:ayuda:listado:nuevo', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 74, 72);
INSERT INTO menu_nuevo
VALUES (107, 'administracion:modulos:listado:nuevo', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 74, 73);
INSERT INTO menu_nuevo VALUES (65, 'inicio', '[0:22]={f,f,t,f,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,t,f,f,f}', 0, 74);
INSERT INTO menu_nuevo
VALUES (1, 'inicio:mensajes:listado:ver', '[0:22]={f,f,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f}', 65, 75);
INSERT INTO menu_nuevo VALUES (2, 'inicio:tareas:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 65, 76);
INSERT INTO menu_nuevo VALUES (66, 'documentacion', '[0:22]={f,f,f,f,f,f,f,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 0, 77);
INSERT INTO menu_nuevo VALUES (77, NULL, '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 66, 78);
INSERT INTO menu_nuevo
VALUES (3, 'documentacion:manual:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 77, 79);
INSERT INTO menu_nuevo
VALUES (7, 'documentacion:pg:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 77, 80);
INSERT INTO menu_nuevo
VALUES (57, 'documentacion:docborrador:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 77, 81);
INSERT INTO menu_nuevo
VALUES (5, 'documentacion:objetivos:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 77, 82);
INSERT INTO menu_nuevo
VALUES (4, 'documentacion:politica:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 77, 83);
INSERT INTO menu_nuevo
VALUES (8, 'documentacion:pe:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 77, 84);
INSERT INTO menu_nuevo
VALUES (104, 'documentacion:planamb:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 77, 85);
INSERT INTO menu_nuevo
VALUES (62, 'documentacion:aai:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 77, 86);
INSERT INTO menu_nuevo
VALUES (10, 'documentacion:docvigor:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 77, 87);
INSERT INTO menu_nuevo VALUES (78, NULL, '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 66, 88);
INSERT INTO menu_nuevo
VALUES (63, 'documentacion:normativa:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 78, 89);
INSERT INTO menu_nuevo
VALUES (9, 'documentacion:frl:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 78, 90);
INSERT INTO menu_nuevo
VALUES (12, 'documentacion:legislacion:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 78, 91);
INSERT INTO menu_nuevo VALUES (79, NULL, '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 66, 92);
INSERT INTO menu_nuevo
VALUES (11, 'documentacion:registros:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 79, 93);
INSERT INTO menu_nuevo
VALUES (13, 'documentacion:docformatos:listado:ver', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 79, 94);
INSERT INTO menu_nuevo VALUES (75, 'logout', '[0:22]={f,f,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f}', 0, 95);
INSERT INTO menu_nuevo VALUES (109, '', '{f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 74, 109);
INSERT INTO menu_nuevo
VALUES (110, 'administracion:proveedores:listado:ver', '{f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 109, 110);
INSERT INTO menu_nuevo VALUES
  (111, 'administracion:proveedores:listado:incidencia', '{f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 109, 110);
INSERT INTO menu_nuevo
VALUES (112, 'administracion:proveedores:listado:contacto', '{f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 109, 112);
INSERT INTO menu_nuevo
VALUES (113, 'administracion:proveedores:listado:producto', '{f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 109, 113);
INSERT INTO menu_nuevo VALUES (114, '', '{f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 74, 114);
INSERT INTO menu_nuevo
VALUES (115, 'administracion:equipos:listado:ver', '{f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 114, 115);
INSERT INTO menu_nuevo VALUES (116, '', '{f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 74, 116);
INSERT INTO menu_nuevo
VALUES (117, 'administracion:auditoriaanual:listado:ver', '{f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 116, 117);
INSERT INTO menu_nuevo
VALUES (118, 'administracion:auditoriavigor:listado:ver', '{f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 116, 118);
INSERT INTO menu_nuevo VALUES (119, '', '{f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 74, 119);
INSERT INTO menu_nuevo VALUES
  (120, 'administracion:indicadoresobjetivo:listado:ver', '{f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 119, 120);

--
-- Data for Name: metas_indicadores; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO metas_indicadores
VALUES (1, 2, 1, 'Acabar con los charlies', '2006-09-14', 'Javivi', TRUE, 'Una navaja y 3 granadas');
INSERT INTO metas_indicadores
VALUES (2, 2, 2, 'Exterminar vietnamitas', '2006-09-23', 'Pedro', TRUE, '2 tanques de Napalm y 55 metralletas');
INSERT INTO metas_indicadores VALUES (3, 5, 689, 'fasdfasd', '2006-09-15', 'asfas', TRUE, 'dafas');
INSERT INTO metas_indicadores VALUES (4, 5, 2, 'matar humanos', '2006-10-14', 'reaper', TRUE, 'muchos');
INSERT INTO metas_indicadores VALUES (5, 10, 1, '13123', '2006-10-04', '123123', TRUE, '123123');
INSERT INTO metas_indicadores VALUES (6, 9, 121, '1231231', '2006-10-06', '12312312', TRUE, '1231231');
INSERT INTO metas_indicadores VALUES (7, 11, 1, NULL, '2006-11-23', NULL, TRUE, NULL);

--
-- Data for Name: metas_objetivos; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: normativa; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: objetivos; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: objetivos_globales; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: objetivos_indicadores; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: perfiles; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO perfiles VALUES (0, 'Mantenimiento', NULL, NULL);

--
-- Data for Name: plan_formacion; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: plantilla_curso; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: preguntas_legislacion_aplicable; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: procesos; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: productos; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: profesores; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: programa_auditoria; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: proveedores; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: registros; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO registros VALUES (1, 'Ficha de personal', 'listado:fpersonal               ');
INSERT INTO registros VALUES (2, 'Requisitos del puesto', 'listado:freq                    ');

--
-- Data for Name: requisitos_puesto; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: requisitos_puesto_competencias; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: requisitos_puesto_datos_puesto; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO requisitos_puesto_datos_puesto
VALUES ('Programador', 'Picador', 'Analista', 'Programacion', TRUE, 2, 'Programador PHP para proyecto nuevo', 2);
INSERT INTO requisitos_puesto_datos_puesto VALUES ('sadfasdfasdf', 'dfd', 'df', 'dfa', FALSE, NULL, 'sfasf', 4);

--
-- Data for Name: requisitos_puesto_formacion; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: requisitos_puesto_ft; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: requisitos_puesto_promocion; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: seguimientos; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: tareas; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: tipo_acciones; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO tipo_acciones VALUES (1, 'Auditorias', TRUE, 1);
INSERT INTO tipo_acciones VALUES (3, 'Reclamaci�n', TRUE, 1);
INSERT INTO tipo_acciones VALUES (4, 'Preventiva/Correctiva', TRUE, 1);

--
-- Data for Name: tipo_acciones_idiomas; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO tipo_acciones_idiomas VALUES (3, 'Auditorias', 1, 1);
INSERT INTO tipo_acciones_idiomas VALUES (4, 'Reclamacion', 3, 1);
INSERT INTO tipo_acciones_idiomas VALUES (5, 'Preventiva/Correctiva', 4, 1);
INSERT INTO tipo_acciones_idiomas VALUES (6, 'Auditorias', 1, 2);
INSERT INTO tipo_acciones_idiomas VALUES (7, 'Reclamacio', 3, 2);
INSERT INTO tipo_acciones_idiomas VALUES (8, 'Preventiva/Correctiva', 4, 2);
INSERT INTO tipo_acciones_idiomas VALUES (9, 'atata', 7, 1);

--
-- Data for Name: tipo_ambito_aplicacion; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO tipo_ambito_aplicacion VALUES (0, 'Ninguno', 1);

--
-- Data for Name: tipo_ambito_aplicacion_idiomas; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO tipo_ambito_aplicacion_idiomas VALUES (1, 'titotito
', 5, 2);
INSERT INTO tipo_ambito_aplicacion_idiomas VALUES (2, 'Ninguno', 0, 1);
INSERT INTO tipo_ambito_aplicacion_idiomas VALUES (3, 'Cap', 0, 2);

--
-- Data for Name: tipo_area_aplicacion; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO tipo_area_aplicacion VALUES (0, 'Ninguna', 1);
INSERT INTO tipo_area_aplicacion VALUES (2, 'asdad', 1);
INSERT INTO tipo_area_aplicacion VALUES (3, 'asd', 1);
INSERT INTO tipo_area_aplicacion VALUES (4, 'test1', 1);

--
-- Data for Name: tipo_aspectos; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO tipo_aspectos VALUES (1, 'Directos');
INSERT INTO tipo_aspectos VALUES (2, 'Indirectos');
INSERT INTO tipo_aspectos VALUES (3, 'Plan Emergencia');

--
-- Data for Name: tipo_botones; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO tipo_botones VALUES (1, 'noafecta');
INSERT INTO tipo_botones VALUES (2, 'sincheck');
INSERT INTO tipo_botones VALUES (3, 'general');
INSERT INTO tipo_botones VALUES (4, 'fila');

--
-- Data for Name: tipo_documento; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO tipo_documento VALUES
  (2, 'Procedimiento General', 'listado', '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', '{f,f,f,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 'interno', NULL,
   1);
INSERT INTO tipo_documento VALUES (6, 'Politica Integral', 'unico', '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                      '{f,f,f,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 'externo', NULL, 1);
INSERT INTO tipo_documento VALUES (4, 'Docs. y formatos', 'listado', '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                      '{f,f,f,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 'externo', NULL, 1);
INSERT INTO tipo_documento VALUES
  (9, 'Ficha Requisitos Legales', 'listado', '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', '{f,f,f,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 'externo', NULL,
   1);
INSERT INTO tipo_documento VALUES (12, 'Normativa', 'listado', '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                       '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                       '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                       '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                       '{f,f,f,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                       '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                       '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 'externo', NULL, 1);
INSERT INTO tipo_documento VALUES (5, 'Proceso', 'listado', '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                      '{f,f,f,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 'externo', NULL, 1);
INSERT INTO tipo_documento VALUES
  (11, 'Analisis Ambiental Inicial', 'unico', '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
       '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
       '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', '{f,f,f,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
       '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 'externo',
   NULL, 1);
INSERT INTO tipo_documento VALUES
  (3, 'Procedimiento Operativo', 'listado', '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', '{f,f,f,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 'externo', NULL,
   1);
INSERT INTO tipo_documento VALUES (14, 'Plan Emerg. Amb.', 'listado', '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                       '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                       '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                       '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                       '{f,f,f,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                       '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                       '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 'externo', '{}', 1);
INSERT INTO tipo_documento VALUES (1, 'Manual', 'unico', '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                      '{f,f,f,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 'externo', NULL, 1);
INSERT INTO tipo_documento VALUES (8, 'Objetivos', 'unico', '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                      '{f,f,f,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                      '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 'externo', NULL, 1);
INSERT INTO tipo_documento VALUES (13, 'adjuntos', 'listado', '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                       '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                       '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                       '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                       '{f,f,f,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                       '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}',
                                       '{f,f,f,t,t,t,t,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 'externo', NULL, 1);

--
-- Data for Name: tipo_documento_idiomas; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO tipo_documento_idiomas VALUES (2, 'Manual', 1, 1);
INSERT INTO tipo_documento_idiomas VALUES (3, 'Procedimiento General', 2, 1);
INSERT INTO tipo_documento_idiomas VALUES (4, 'Procedimiento Operativo', 3, 1);
INSERT INTO tipo_documento_idiomas VALUES (5, 'Docs. y formatos', 4, 1);
INSERT INTO tipo_documento_idiomas VALUES (6, 'Proceso', 5, 1);
INSERT INTO tipo_documento_idiomas VALUES (7, 'Politica Integral', 6, 1);
INSERT INTO tipo_documento_idiomas VALUES (8, 'Objetivos', 8, 1);
INSERT INTO tipo_documento_idiomas VALUES (9, 'Ficha Requisitos Legales', 9, 1);
INSERT INTO tipo_documento_idiomas VALUES (10, 'Analisis Ambiental Inicial', 11, 1);
INSERT INTO tipo_documento_idiomas VALUES (11, 'Normativa', 12, 1);
INSERT INTO tipo_documento_idiomas VALUES (12, 'Adjuntos', 13, 1);
INSERT INTO tipo_documento_idiomas VALUES (13, 'Plan Emerg. Amb.', 14, 1);
INSERT INTO tipo_documento_idiomas VALUES (14, 'Manual', 1, 2);
INSERT INTO tipo_documento_idiomas VALUES (15, 'Procediment general', 2, 2);
INSERT INTO tipo_documento_idiomas VALUES (16, 'Procediment Operatiu', 3, 2);
INSERT INTO tipo_documento_idiomas VALUES (17, 'Docs. y formatos', 4, 2);
INSERT INTO tipo_documento_idiomas VALUES (18, 'Proces', 5, 2);
INSERT INTO tipo_documento_idiomas VALUES (19, 'Politica Integral', 6, 2);
INSERT INTO tipo_documento_idiomas VALUES (20, 'Objectius', 8, 2);
INSERT INTO tipo_documento_idiomas VALUES (21, 'Fitxa Requisits Legals', 9, 2);
INSERT INTO tipo_documento_idiomas VALUES (22, 'Analisi Ambiental Inicial', 11, 2);
INSERT INTO tipo_documento_idiomas VALUES (23, 'Normatives', 12, 2);
INSERT INTO tipo_documento_idiomas VALUES (24, 'Adjunts', 13, 2);
INSERT INTO tipo_documento_idiomas VALUES (25, 'Pla Emerg. Amb.', 14, 2);

--
-- Data for Name: tipo_estado_auditoria; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO tipo_estado_auditoria VALUES (1, 'Pendiente');
INSERT INTO tipo_estado_auditoria VALUES (2, 'Suspendido');
INSERT INTO tipo_estado_auditoria VALUES (3, 'Realizado');

--
-- Data for Name: tipo_estados_curso; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO tipo_estados_curso VALUES (1, 'Preparacion');
INSERT INTO tipo_estados_curso VALUES (2, 'Abierto');
INSERT INTO tipo_estados_curso VALUES (3, 'Realizado');

--
-- Data for Name: tipo_frecuencia; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO tipo_frecuencia VALUES (5, 'Semestral/Trimestral', 1);
INSERT INTO tipo_frecuencia VALUES (6, 'Mensual', 3);
INSERT INTO tipo_frecuencia VALUES (7, 'Diaria/Quincenal', 5);

--
-- Data for Name: tipo_frecuencia_idiomas; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO tipo_frecuencia_idiomas VALUES (1, 'Semestral/Trimestral', 5, 1);
INSERT INTO tipo_frecuencia_idiomas VALUES (2, 'Mensual', 6, 1);
INSERT INTO tipo_frecuencia_idiomas VALUES (3, 'Diaria/Quincenal', 7, 1);
INSERT INTO tipo_frecuencia_idiomas VALUES (4, 'Semestral/Trimestral', 5, 2);
INSERT INTO tipo_frecuencia_idiomas VALUES (5, 'Mensual', 6, 2);
INSERT INTO tipo_frecuencia_idiomas VALUES (6, 'Diaria/Quinzenal', 7, 2);
INSERT INTO tipo_frecuencia_idiomas VALUES (7, 'affafaf', 8, 1);

--
-- Data for Name: tipo_frecuencia_seg; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO tipo_frecuencia_seg VALUES (3, 'Semestral', '6 mons');
INSERT INTO tipo_frecuencia_seg VALUES (4, 'Anual', '1 year');
INSERT INTO tipo_frecuencia_seg VALUES (1, 'Mensual', '1 mon');
INSERT INTO tipo_frecuencia_seg VALUES (2, 'Trimestral', '3 mons');
INSERT INTO tipo_frecuencia_seg VALUES (5, 'Trianual', '3 years');

--
-- Data for Name: tipo_grado_promocion; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO tipo_grado_promocion VALUES (1, 'Total/Muy Alta');
INSERT INTO tipo_grado_promocion VALUES (2, 'Mucha/Alta');
INSERT INTO tipo_grado_promocion VALUES (3, 'Bastante/Media');
INSERT INTO tipo_grado_promocion VALUES (4, 'Poca/Baja');
INSERT INTO tipo_grado_promocion VALUES (5, 'Ninguna/Nula');

--
-- Data for Name: tipo_gravedad; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO tipo_gravedad VALUES (5, 'Sin repercusiones importantes', 1);
INSERT INTO tipo_gravedad VALUES (6, 'Repercusiones intracentro', 3);
INSERT INTO tipo_gravedad VALUES (7, 'Repercusiones intracentro/Extracentro', 5);

--
-- Data for Name: tipo_gravedad_idiomas; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO tipo_gravedad_idiomas VALUES (3, 'Repercusiones intracentro/extracentro', 7, 1);
INSERT INTO tipo_gravedad_idiomas VALUES (4, 'Sense repercussions importants', 5, 2);
INSERT INTO tipo_gravedad_idiomas VALUES (5, 'Repercussions intracentro', 6, 2);
INSERT INTO tipo_gravedad_idiomas VALUES (6, 'Repercussions intracentro/extracentro', 7, 2);
INSERT INTO tipo_gravedad_idiomas VALUES (2, 'Repercusiones intracentro', 6, 1);
INSERT INTO tipo_gravedad_idiomas VALUES (1, 'Sin repercusiones importantes', 5, 1);
INSERT INTO tipo_gravedad_idiomas VALUES (13, '322423', 14, 1);
INSERT INTO tipo_gravedad_idiomas VALUES (14, '1', 15, 1);
INSERT INTO tipo_gravedad_idiomas VALUES (15, '123123', 13, 2);

--
-- Data for Name: tipo_impactos; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO tipo_impactos VALUES (0, 'Ninguno', TRUE, 1);
INSERT INTO tipo_impactos VALUES (5, 'Emisiones controladas e incontroladas hacia la atmosfera', TRUE, 1);
INSERT INTO tipo_impactos VALUES (6, 'Vertidos controlados e incontrolados en las aguas y alcantarillado', TRUE, 1);
INSERT INTO tipo_impactos VALUES (8, 'Contaminacion del suelo', TRUE, 1);
INSERT INTO tipo_impactos
VALUES (10, 'Emision de energia termica, ruidos, olores, polvo, vibracion e impacto visual', TRUE, 1);
INSERT INTO tipo_impactos VALUES (11, 'Aspectos relacionados con la produccion (embalaje, transporte)', TRUE, 1);
INSERT INTO tipo_impactos VALUES (12, 'Decisiones de indole administrativa y de planificacion', TRUE, 1);
INSERT INTO tipo_impactos VALUES (13, 'Composicion de la gama de productos', TRUE, 1);
INSERT INTO tipo_impactos
VALUES (14, 'El comportamiento ambiental y las practicas de contratistas, subcontratistas y proveedores', TRUE, 1);
INSERT INTO tipo_impactos
VALUES (9, 'Utilizacion del suelo, agua, los combustibles, la energia y otros resursos naturales', TRUE, 1);
INSERT INTO tipo_impactos VALUES (7, 'Residuos', TRUE, 1);

--
-- Data for Name: tipo_impactos_idiomas; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO tipo_impactos_idiomas VALUES (1, 'test', 15, 1);
INSERT INTO tipo_impactos_idiomas VALUES (2, 'Ninguno', 0, 1);
INSERT INTO tipo_impactos_idiomas VALUES (3, 'Emisiones controladas e incontroladas hacia la atmosfera', 5, 1);
INSERT INTO tipo_impactos_idiomas
VALUES (4, 'Vertidos controlados e incontrolados en las aguas y alcantarillado', 6, 1);
INSERT INTO tipo_impactos_idiomas VALUES (5, 'Contaminacion del suelo', 8, 1);
INSERT INTO tipo_impactos_idiomas VALUES (6, 'Residuos', 7, 1);
INSERT INTO tipo_impactos_idiomas
VALUES (7, 'Utilizacio del suelo, agua, los combustibles, la energia y otros recursos naturales', 9, 1);
INSERT INTO tipo_impactos_idiomas
VALUES (8, 'Emision de energia termica, ruidos, olores, polvo, vibracion e impacto visual', 10, 1);
INSERT INTO tipo_impactos_idiomas VALUES (9, 'Aspectos relacionados con la producccion (embalaje, transporte)', 11, 1);
INSERT INTO tipo_impactos_idiomas VALUES (10, 'Decisiones de indole administrativa y de planificacion', 12, 1);
INSERT INTO tipo_impactos_idiomas VALUES (11, 'Composicion de la gama de productos', 13, 1);
INSERT INTO tipo_impactos_idiomas
VALUES (12, 'El comportamiento ambiental y las practicas de contratistas, subcontratistas y proveedores', 14, 1);
INSERT INTO tipo_impactos_idiomas VALUES (13, 'Residus', 7, 2);
INSERT INTO tipo_impactos_idiomas VALUES (14, 'asfsf', 17, 1);

--
-- Data for Name: tipo_magnitud; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO tipo_magnitud VALUES (10, '-5% de generacion que el a�o anterior', 1);
INSERT INTO tipo_magnitud VALUES (11, '+-5% de generacion que el a�o anterior.<br />Ausencia de datos', 3);
INSERT INTO tipo_magnitud VALUES (12, '+5% de generacion que el a�o anterior', 5);

--
-- Data for Name: tipo_magnitud_idiomas; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO tipo_magnitud_idiomas VALUES (5, '+5% de generacion que el a�o anterior', 12, 1);
INSERT INTO tipo_magnitud_idiomas VALUES (2, '-5% de generacio que l''any anterior ', 10, 2);
INSERT INTO tipo_magnitud_idiomas VALUES (4, '+-5% de generacio que l''any anterior.>br>Absencia de dades', 11, 2);
INSERT INTO tipo_magnitud_idiomas VALUES (6, '+5% de generacio que l''any anterior ', 12, 2);
INSERT INTO tipo_magnitud_idiomas VALUES (1, '-5% de generacion que el a�o anterior', 10, 1);
INSERT INTO tipo_magnitud_idiomas VALUES (3, '+-5% de generacion que el a�o anterior.<br />Ausencia de datos', 11, 1);

--
-- Data for Name: tipo_probabilidad; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO tipo_probabilidad VALUES (1, 1, 'baja');
INSERT INTO tipo_probabilidad VALUES (2, 2, 'media');
INSERT INTO tipo_probabilidad VALUES (3, 3, 'alta');

--
-- Data for Name: tipo_probabilidad_idiomas; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO tipo_probabilidad_idiomas VALUES (1, 'baja', 1, 1);
INSERT INTO tipo_probabilidad_idiomas VALUES (2, 'media', 2, 1);
INSERT INTO tipo_probabilidad_idiomas VALUES (3, 'alta', 3, 1);
INSERT INTO tipo_probabilidad_idiomas VALUES (4, 'baixa', 1, 2);
INSERT INTO tipo_probabilidad_idiomas VALUES (5, 'mitja', 2, 2);
INSERT INTO tipo_probabilidad_idiomas VALUES (6, 'alta', 3, 2);
INSERT INTO tipo_probabilidad_idiomas VALUES (7, 'Maxima', 4, 1);

--
-- Data for Name: tipo_severidad; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO tipo_severidad VALUES (1, 1, 'baja');
INSERT INTO tipo_severidad VALUES (2, 2, 'media');
INSERT INTO tipo_severidad VALUES (3, 3, 'alta');

--
-- Data for Name: tipo_severidad_idiomas; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO tipo_severidad_idiomas VALUES (1, 'baja', 1, 1);
INSERT INTO tipo_severidad_idiomas VALUES (2, 'baja', 1, 2);
INSERT INTO tipo_severidad_idiomas VALUES (3, 'media', 2, 1);
INSERT INTO tipo_severidad_idiomas VALUES (4, 'media', 2, 2);
INSERT INTO tipo_severidad_idiomas VALUES (5, 'alta', 3, 1);
INSERT INTO tipo_severidad_idiomas VALUES (6, 'alta', 3, 2);
INSERT INTO tipo_severidad_idiomas VALUES (7, 'test', 4, 1);
INSERT INTO tipo_severidad_idiomas VALUES (8, 'adf', 4, 1);

--
-- Data for Name: tipo_tarea; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO tipo_tarea VALUES (1, 'Revisar');
INSERT INTO tipo_tarea VALUES (2, 'Aprobar');
INSERT INTO tipo_tarea VALUES (3, 'Nueva Version');

--
-- Data for Name: tipos_cursos; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Data for Name: tipos_fichero; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO tipos_fichero VALUES (1, 'pdf', 'pdf ', 'application/pdf');
INSERT INTO tipos_fichero VALUES (3, NULL, 'sxv ', 'application/vnd.sun.xml.writer');
INSERT INTO tipos_fichero VALUES (4, 'txt', 'txt ', 'text/plain');
INSERT INTO tipos_fichero VALUES (5, '', 'html', 'text/html');
INSERT INTO tipos_fichero VALUES (6, 'jpg', 'jpg ', 'image/jpeg');
INSERT INTO tipos_fichero VALUES (7, 'png', 'png ', 'image/png');
INSERT INTO tipos_fichero VALUES (8, 'gif', 'gif ', 'image/gif');
INSERT INTO tipos_fichero VALUES (2, 'doc', 'doc ', 'application/msword');

--
-- Data for Name: usuarios; Type: TABLE DATA; Schema: public; Owner: qnova
--

INSERT INTO usuarios VALUES
  (0, 'admin', '61dfffdf05c033dc67110ade42acf7e2', 0, 'Administrador', 'de toda', 'la aplicacion', '00000000A', NULL,
      NULL, NULL, 0, NULL, NULL, '2006-12-01 18:55:16.465043', '2006-12-05 14:37:19.866691', 1499, 14, NULL, NULL, 't');

--
-- Data for Name: valores; Type: TABLE DATA; Schema: public; Owner: qnova
--


--
-- Name: acciones_mejora_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY acciones_mejora
  ADD CONSTRAINT acciones_mejora_pkey PRIMARY KEY (id);

--
-- Name: alumnos_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY alumnos
  ADD CONSTRAINT alumnos_pkey PRIMARY KEY (id);

--
-- Name: areas_nombre_key; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY areas
  ADD CONSTRAINT areas_nombre_key UNIQUE (nombre);

--
-- Name: areas_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY areas
  ADD CONSTRAINT areas_pkey PRIMARY KEY (id);

--
-- Name: aspectos_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY aspectos
  ADD CONSTRAINT aspectos_pkey PRIMARY KEY (id);

--
-- Name: auditores_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY auditores
  ADD CONSTRAINT auditores_pkey PRIMARY KEY (id);

--
-- Name: auditorias_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY auditorias
  ADD CONSTRAINT auditorias_pkey PRIMARY KEY (id);

--
-- Name: botones_idiomas_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY botones_idiomas
  ADD CONSTRAINT botones_idiomas_pkey PRIMARY KEY (id);

--
-- Name: botones_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY botones
  ADD CONSTRAINT botones_pkey PRIMARY KEY (id);

--
-- Name: clientes_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY clientes
  ADD CONSTRAINT clientes_pkey PRIMARY KEY (id);

--
-- Name: contactos_proveedores_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY contactos_proveedores
  ADD CONSTRAINT contactos_proveedores_pkey PRIMARY KEY (id);

--
-- Name: contenido_adjunto_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY contenido_adjunto
  ADD CONSTRAINT contenido_adjunto_pkey PRIMARY KEY (id);

--
-- Name: contenido_binario_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY contenido_binario
  ADD CONSTRAINT contenido_binario_pkey PRIMARY KEY (id);

--
-- Name: contenido_procesos_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY contenido_procesos
  ADD CONSTRAINT contenido_procesos_pkey PRIMARY KEY (id);

--
-- Name: contenido_texto_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY contenido_texto
  ADD CONSTRAINT contenido_texto_pkey PRIMARY KEY (id);

--
-- Name: criterios_homologacion_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY criterios_homologacion
  ADD CONSTRAINT criterios_homologacion_pkey PRIMARY KEY (id);

--
-- Name: cursos_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY cursos
  ADD CONSTRAINT cursos_pkey PRIMARY KEY (id);

--
-- Name: division_ayuda_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY division_ayuda
  ADD CONSTRAINT division_ayuda_pkey PRIMARY KEY (id);

--
-- Name: documentos_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY documentos
  ADD CONSTRAINT documentos_pkey PRIMARY KEY (id);

--
-- Name: equipos_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY equipos
  ADD CONSTRAINT equipos_pkey PRIMARY KEY (id);

--
-- Name: estados_documento_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY estados_documento
  ADD CONSTRAINT estados_documento_pkey PRIMARY KEY (id);

--
-- Name: ficha_personal_cambio_departamento_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY ficha_personal_cambio_departamento
  ADD CONSTRAINT ficha_personal_cambio_departamento_pkey PRIMARY KEY (id);

--
-- Name: ficha_personal_cambio_perfil_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY ficha_personal_cambio_perfil
  ADD CONSTRAINT ficha_personal_cambio_perfil_pkey PRIMARY KEY (id);

--
-- Name: ficha_personal_experiencia_laboral_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY ficha_personal_experiencia_laboral
  ADD CONSTRAINT ficha_personal_experiencia_laboral_pkey PRIMARY KEY (id);

--
-- Name: ficha_personal_formacion_tecnica_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY ficha_personal_formacion_tecnica
  ADD CONSTRAINT ficha_personal_formacion_tecnica_pkey PRIMARY KEY (id);

--
-- Name: ficha_personal_otros_cursos_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY ficha_personal_otros_cursos
  ADD CONSTRAINT ficha_personal_otros_cursos_pkey PRIMARY KEY (id);

--
-- Name: ficha_personal_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY ficha_personal
  ADD CONSTRAINT ficha_personal_pkey PRIMARY KEY (id);

--
-- Name: flujogramas_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY flujogramas
  ADD CONSTRAINT flujogramas_pkey PRIMARY KEY (id);

--
-- Name: formula_aspectos_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY formula_aspectos
  ADD CONSTRAINT formula_aspectos_pkey PRIMARY KEY (id);

--
-- Name: historico_cuestionarios_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY historico_cuestionarios
  ADD CONSTRAINT historico_cuestionarios_pkey PRIMARY KEY (id);

--
-- Name: historico_productos_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY historico_productos
  ADD CONSTRAINT historico_productos_pkey PRIMARY KEY (id);

--
-- Name: hospitales_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY hospitales
  ADD CONSTRAINT hospitales_pkey PRIMARY KEY (id);

--
-- Name: idiomas_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY idiomas
  ADD CONSTRAINT idiomas_pkey PRIMARY KEY (id);

--
-- Name: impactos_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY tipo_impactos
  ADD CONSTRAINT impactos_pkey PRIMARY KEY (id);

--
-- Name: incidencias_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY incidencias
  ADD CONSTRAINT incidencias_pkey PRIMARY KEY (id);

--
-- Name: indicadores_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY indicadores
  ADD CONSTRAINT indicadores_pkey PRIMARY KEY (id);

--
-- Name: legislacion_aplicable_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY legislacion_aplicable
  ADD CONSTRAINT legislacion_aplicable_pkey PRIMARY KEY (id);

--
-- Name: mantenimientos_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY mantenimientos
  ADD CONSTRAINT mantenimientos_pkey PRIMARY KEY (id);

--
-- Name: mensajes_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY mensajes
  ADD CONSTRAINT mensajes_pkey PRIMARY KEY (id);

--
-- Name: menu_idiomas_nuevo_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY menu_idiomas_nuevo
  ADD CONSTRAINT menu_idiomas_nuevo_pkey PRIMARY KEY (id);

--
-- Name: menu_nuevo_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY menu_nuevo
  ADD CONSTRAINT menu_nuevo_pkey PRIMARY KEY (id);

--
-- Name: metas_indicadores_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY metas_indicadores
  ADD CONSTRAINT metas_indicadores_pkey PRIMARY KEY (id);

--
-- Name: metas_objetivos_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY metas_objetivos
  ADD CONSTRAINT metas_objetivos_pkey PRIMARY KEY (id);

--
-- Name: normativa_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY normativa
  ADD CONSTRAINT normativa_pkey PRIMARY KEY (id);

--
-- Name: objetivos_globales_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY objetivos_globales
  ADD CONSTRAINT objetivos_globales_pkey PRIMARY KEY (id);

--
-- Name: objetivos_indicadores_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY objetivos_indicadores
  ADD CONSTRAINT objetivos_indicadores_pkey PRIMARY KEY (id);

--
-- Name: objetivos_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY objetivos
  ADD CONSTRAINT objetivos_pkey PRIMARY KEY (id);

--
-- Name: perfiles_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY perfiles
  ADD CONSTRAINT perfiles_pkey PRIMARY KEY (id);

--
-- Name: plan_formacion_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY plan_formacion
  ADD CONSTRAINT plan_formacion_pkey PRIMARY KEY (id);

--
-- Name: plantilla_curso_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY plantilla_curso
  ADD CONSTRAINT plantilla_curso_pkey PRIMARY KEY (id);

--
-- Name: preguntas_legislacion_aplicable_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY preguntas_legislacion_aplicable
  ADD CONSTRAINT preguntas_legislacion_aplicable_pkey PRIMARY KEY (id);

--
-- Name: proceso_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY procesos
  ADD CONSTRAINT proceso_pkey PRIMARY KEY (id);

--
-- Name: productos_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY productos
  ADD CONSTRAINT productos_pkey PRIMARY KEY (id);

--
-- Name: profesores_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY profesores
  ADD CONSTRAINT profesores_pkey PRIMARY KEY (id);

--
-- Name: programa_auditoria_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY programa_auditoria
  ADD CONSTRAINT programa_auditoria_pkey PRIMARY KEY (id);

--
-- Name: proveedores_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY proveedores
  ADD CONSTRAINT proveedores_pkey PRIMARY KEY (id);

--
-- Name: registros_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY registros
  ADD CONSTRAINT registros_pkey PRIMARY KEY (id);

--
-- Name: requisitos_puesto_competencias_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY requisitos_puesto_competencias
  ADD CONSTRAINT requisitos_puesto_competencias_pkey PRIMARY KEY (id);

--
-- Name: requisitos_puesto_datos_puesto_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY requisitos_puesto_datos_puesto
  ADD CONSTRAINT requisitos_puesto_datos_puesto_pkey PRIMARY KEY (id);

--
-- Name: requisitos_puesto_formacion_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY requisitos_puesto_formacion
  ADD CONSTRAINT requisitos_puesto_formacion_pkey PRIMARY KEY (id);

--
-- Name: requisitos_puesto_ft_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY requisitos_puesto_ft
  ADD CONSTRAINT requisitos_puesto_ft_pkey PRIMARY KEY (id);

--
-- Name: requisitos_puesto_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY requisitos_puesto
  ADD CONSTRAINT requisitos_puesto_pkey PRIMARY KEY (id);

--
-- Name: requisitos_puesto_promocion_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY requisitos_puesto_promocion
  ADD CONSTRAINT requisitos_puesto_promocion_pkey PRIMARY KEY (id);

--
-- Name: tareas_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY tareas
  ADD CONSTRAINT tareas_pkey PRIMARY KEY (id);

--
-- Name: tipo_acciones_idiomas_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY tipo_acciones_idiomas
  ADD CONSTRAINT tipo_acciones_idiomas_pkey PRIMARY KEY (id);

--
-- Name: tipo_acciones_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY tipo_acciones
  ADD CONSTRAINT tipo_acciones_pkey PRIMARY KEY (id);

--
-- Name: tipo_ambito_aplicacion_idiomas_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY tipo_ambito_aplicacion_idiomas
  ADD CONSTRAINT tipo_ambito_aplicacion_idiomas_pkey PRIMARY KEY (id);

--
-- Name: tipo_aspectos_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY tipo_aspectos
  ADD CONSTRAINT tipo_aspectos_pkey PRIMARY KEY (id);

--
-- Name: tipo_botones_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY tipo_botones
  ADD CONSTRAINT tipo_botones_pkey PRIMARY KEY (id);

--
-- Name: tipo_documento_idiomas_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY tipo_documento_idiomas
  ADD CONSTRAINT tipo_documento_idiomas_pkey PRIMARY KEY (id);

--
-- Name: tipo_documento_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY tipo_documento
  ADD CONSTRAINT tipo_documento_pkey PRIMARY KEY (id);

--
-- Name: tipo_estado_auditoria_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY tipo_estado_auditoria
  ADD CONSTRAINT tipo_estado_auditoria_pkey PRIMARY KEY (id);

--
-- Name: tipo_estados_curso_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY tipo_estados_curso
  ADD CONSTRAINT tipo_estados_curso_pkey PRIMARY KEY (id);

--
-- Name: tipo_frecuencia_idiomas_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY tipo_frecuencia_idiomas
  ADD CONSTRAINT tipo_frecuencia_idiomas_pkey PRIMARY KEY (id);

--
-- Name: tipo_frecuencia_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY tipo_frecuencia
  ADD CONSTRAINT tipo_frecuencia_pkey PRIMARY KEY (id);

--
-- Name: tipo_frecuencia_seg_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY tipo_frecuencia_seg
  ADD CONSTRAINT tipo_frecuencia_seg_pkey PRIMARY KEY (id);

--
-- Name: tipo_grado_promocion_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY tipo_grado_promocion
  ADD CONSTRAINT tipo_grado_promocion_pkey PRIMARY KEY (id);

--
-- Name: tipo_gravedad_idiomas_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY tipo_gravedad_idiomas
  ADD CONSTRAINT tipo_gravedad_idiomas_pkey PRIMARY KEY (id);

--
-- Name: tipo_gravedad_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY tipo_gravedad
  ADD CONSTRAINT tipo_gravedad_pkey PRIMARY KEY (id);

--
-- Name: tipo_impactos_idiomas_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY tipo_impactos_idiomas
  ADD CONSTRAINT tipo_impactos_idiomas_pkey PRIMARY KEY (id);

--
-- Name: tipo_magnitud_idiomas_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY tipo_magnitud_idiomas
  ADD CONSTRAINT tipo_magnitud_idiomas_pkey PRIMARY KEY (id);

--
-- Name: tipo_magnitud_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY tipo_magnitud
  ADD CONSTRAINT tipo_magnitud_pkey PRIMARY KEY (id);

--
-- Name: tipo_probabilidad_idiomas_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY tipo_probabilidad_idiomas
  ADD CONSTRAINT tipo_probabilidad_idiomas_pkey PRIMARY KEY (id);

--
-- Name: tipo_probabilidad_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY tipo_probabilidad
  ADD CONSTRAINT tipo_probabilidad_pkey PRIMARY KEY (id);

--
-- Name: tipo_severidad_idiomas_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY tipo_severidad_idiomas
  ADD CONSTRAINT tipo_severidad_idiomas_pkey PRIMARY KEY (id);

--
-- Name: tipo_severidad_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY tipo_severidad
  ADD CONSTRAINT tipo_severidad_pkey PRIMARY KEY (id);

--
-- Name: tipo_tarea_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY tipo_tarea
  ADD CONSTRAINT tipo_tarea_pkey PRIMARY KEY (id);

--
-- Name: tipos_cursos_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY tipos_cursos
  ADD CONSTRAINT tipos_cursos_pkey PRIMARY KEY (id);

--
-- Name: tipos_fichero_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY tipos_fichero
  ADD CONSTRAINT tipos_fichero_pkey PRIMARY KEY (id);

--
-- Name: usuarios_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY usuarios
  ADD CONSTRAINT usuarios_pkey PRIMARY KEY ("login");

--
-- Name: valores_pkey; Type: CONSTRAINT; Schema: public; Owner: qnova; Tablespace: 
--

ALTER TABLE ONLY valores
  ADD CONSTRAINT valores_pkey PRIMARY KEY (id);

--
-- Name: usuarios_id_idx; Type: INDEX; Schema: public; Owner: qnova; Tablespace: 
--

CREATE UNIQUE INDEX usuarios_id_idx
  ON usuarios USING BTREE (id);

--
-- Name: acciones_mejora_auditoria_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY acciones_mejora
  ADD CONSTRAINT acciones_mejora_auditoria_fkey FOREIGN KEY (auditoria) REFERENCES auditorias (id) ON DELETE CASCADE;

--
-- Name: acciones_mejora_cliente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY acciones_mejora
  ADD CONSTRAINT acciones_mejora_cliente_fkey FOREIGN KEY (cliente) REFERENCES clientes (id);

--
-- Name: acciones_mejora_tipo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY acciones_mejora
  ADD CONSTRAINT acciones_mejora_tipo_fkey FOREIGN KEY (tipo) REFERENCES tipo_acciones (id);

--
-- Name: acciones_mejora_usuario_cerrado_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY acciones_mejora
  ADD CONSTRAINT acciones_mejora_usuario_cerrado_fkey FOREIGN KEY (usuario_cerrado) REFERENCES usuarios (id);

--
-- Name: acciones_mejora_usuario_detectado_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY acciones_mejora
  ADD CONSTRAINT acciones_mejora_usuario_detectado_fkey FOREIGN KEY (usuario_detectado) REFERENCES usuarios (id);

--
-- Name: acciones_mejora_usuario_implantacion_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY acciones_mejora
  ADD CONSTRAINT acciones_mejora_usuario_implantacion_fkey FOREIGN KEY (usuario_implantacion) REFERENCES usuarios (id) ON DELETE CASCADE;

--
-- Name: acciones_mejora_usuario_verifica_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY acciones_mejora
  ADD CONSTRAINT acciones_mejora_usuario_verifica_fkey FOREIGN KEY (usuario_verifica) REFERENCES usuarios (id);

--
-- Name: alumnos_curso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY alumnos
  ADD CONSTRAINT alumnos_curso_fkey FOREIGN KEY (curso) REFERENCES cursos (id) ON DELETE CASCADE;

--
-- Name: alumnos_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY alumnos
  ADD CONSTRAINT alumnos_usuario_fkey FOREIGN KEY (usuario) REFERENCES usuarios (id) ON DELETE CASCADE;

--
-- Name: auditores_auditoria_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY auditores
  ADD CONSTRAINT auditores_auditoria_fkey FOREIGN KEY (auditoria) REFERENCES auditorias (id) ON DELETE CASCADE;

--
-- Name: auditores_usuario_interno_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY auditores
  ADD CONSTRAINT auditores_usuario_interno_fkey FOREIGN KEY (usuario_interno) REFERENCES usuarios (id) ON DELETE CASCADE;

--
-- Name: auditorias_estado_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY auditorias
  ADD CONSTRAINT auditorias_estado_fkey FOREIGN KEY (estado) REFERENCES tipo_estado_auditoria (id) ON DELETE CASCADE;

--
-- Name: auditorias_programa_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY auditorias
  ADD CONSTRAINT auditorias_programa_fkey FOREIGN KEY (programa) REFERENCES programa_auditoria (id);

--
-- Name: botones_idiomas_boton_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY botones_idiomas
  ADD CONSTRAINT botones_idiomas_boton_fkey FOREIGN KEY (boton) REFERENCES botones (id);

--
-- Name: botones_idiomas_idioma_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY botones_idiomas
  ADD CONSTRAINT botones_idiomas_idioma_id_fkey FOREIGN KEY (idioma_id) REFERENCES idiomas (id);

--
-- Name: botones_menu_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY botones
  ADD CONSTRAINT botones_menu_fkey FOREIGN KEY (menu) REFERENCES menu_nuevo (id);

--
-- Name: botones_tipo_botones_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY botones
  ADD CONSTRAINT botones_tipo_botones_fkey FOREIGN KEY (tipo_botones) REFERENCES tipo_botones (id);

--
-- Name: contactos_proveedores_proveedor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY contactos_proveedores
  ADD CONSTRAINT contactos_proveedores_proveedor_fkey FOREIGN KEY (proveedor) REFERENCES proveedores (id) ON DELETE CASCADE;

--
-- Name: contenido_binario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY contenido_binario
  ADD CONSTRAINT contenido_binario_id_fkey FOREIGN KEY (id) REFERENCES documentos (id);

--
-- Name: contenido_binario_tipo_fichero_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY contenido_binario
  ADD CONSTRAINT contenido_binario_tipo_fichero_fkey FOREIGN KEY (tipo_fichero) REFERENCES tipos_fichero (id);

--
-- Name: contenido_procesos_documento_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY contenido_procesos
  ADD CONSTRAINT contenido_procesos_documento_fkey FOREIGN KEY (documento) REFERENCES documentos (id) ON DELETE CASCADE;

--
-- Name: contenido_procesos_proceso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY contenido_procesos
  ADD CONSTRAINT contenido_procesos_proceso_fkey FOREIGN KEY (proceso) REFERENCES procesos (id) ON DELETE CASCADE;

--
-- Name: contenido_texto_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY contenido_texto
  ADD CONSTRAINT contenido_texto_id_fkey FOREIGN KEY (id) REFERENCES documentos (id);

--
-- Name: cursos_estado_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY cursos
  ADD CONSTRAINT cursos_estado_fkey FOREIGN KEY (estado) REFERENCES tipo_estados_curso (id) ON DELETE CASCADE;

--
-- Name: cursos_plan_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY cursos
  ADD CONSTRAINT cursos_plan_fkey FOREIGN KEY (plan) REFERENCES plan_formacion (id) ON DELETE CASCADE;

--
-- Name: cursos_responsable_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY cursos
  ADD CONSTRAINT cursos_responsable_fkey FOREIGN KEY (responsable) REFERENCES usuarios (id) ON DELETE CASCADE;

--
-- Name: cursos_tipo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY cursos
  ADD CONSTRAINT cursos_tipo_fkey FOREIGN KEY (tipo) REFERENCES tipos_cursos (id);

--
-- Name: division_ayuda_idioma_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY division_ayuda
  ADD CONSTRAINT division_ayuda_idioma_fkey FOREIGN KEY (idioma) REFERENCES idiomas (id);

--
-- Name: documentos_aprobado_por_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY documentos
  ADD CONSTRAINT documentos_aprobado_por_fkey FOREIGN KEY (aprobado_por) REFERENCES usuarios (id);

--
-- Name: documentos_area_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY documentos
  ADD CONSTRAINT documentos_area_fkey FOREIGN KEY (area) REFERENCES areas (id);

--
-- Name: documentos_estado_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY documentos
  ADD CONSTRAINT documentos_estado_fkey FOREIGN KEY (estado) REFERENCES estados_documento (id);

--
-- Name: documentos_revisado_por_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY documentos
  ADD CONSTRAINT documentos_revisado_por_fkey FOREIGN KEY (revisado_por) REFERENCES usuarios (id);

--
-- Name: documentos_tipo_documento_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY documentos
  ADD CONSTRAINT documentos_tipo_documento_fkey FOREIGN KEY (tipo_documento) REFERENCES tipo_documento (id);

--
-- Name: ficha_personal_cambio_departamento_ficha_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY ficha_personal_cambio_departamento
  ADD CONSTRAINT ficha_personal_cambio_departamento_ficha_fkey FOREIGN KEY (ficha) REFERENCES ficha_personal (id);

--
-- Name: ficha_personal_cambio_perfil_ficha_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY ficha_personal_cambio_perfil
  ADD CONSTRAINT ficha_personal_cambio_perfil_ficha_fkey FOREIGN KEY (ficha) REFERENCES ficha_personal (id);

--
-- Name: ficha_personal_experiencia_laboral_ficha_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY ficha_personal_experiencia_laboral
  ADD CONSTRAINT ficha_personal_experiencia_laboral_ficha_fkey FOREIGN KEY (ficha) REFERENCES ficha_personal (id) ON DELETE CASCADE;

--
-- Name: ficha_personal_formacion_tecnica_ficha_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY ficha_personal_formacion_tecnica
  ADD CONSTRAINT ficha_personal_formacion_tecnica_ficha_fkey FOREIGN KEY (ficha) REFERENCES ficha_personal (id) ON DELETE CASCADE;

--
-- Name: ficha_personal_otros_cursos_ficha_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY ficha_personal_otros_cursos
  ADD CONSTRAINT ficha_personal_otros_cursos_ficha_fkey FOREIGN KEY (ficha) REFERENCES ficha_personal (id) ON DELETE CASCADE;

--
-- Name: flujogramas_proceso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY flujogramas
  ADD CONSTRAINT flujogramas_proceso_fkey FOREIGN KEY (proceso) REFERENCES contenido_procesos (id);

--
-- Name: flujogramas_tipo_fichero_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY flujogramas
  ADD CONSTRAINT flujogramas_tipo_fichero_fkey FOREIGN KEY (tipo_fichero) REFERENCES tipos_fichero (id);

--
-- Name: historico_cuestionarios_legislacion_aplicable_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY historico_cuestionarios
  ADD CONSTRAINT historico_cuestionarios_legislacion_aplicable_fkey FOREIGN KEY (legislacion_aplicable) REFERENCES legislacion_aplicable (id);

--
-- Name: historico_cuestionarios_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY historico_cuestionarios
  ADD CONSTRAINT historico_cuestionarios_usuario_fkey FOREIGN KEY (usuario) REFERENCES usuarios (id);

--
-- Name: historico_productos_producto_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY historico_productos
  ADD CONSTRAINT historico_productos_producto_fkey FOREIGN KEY (producto) REFERENCES productos (id) ON DELETE CASCADE;

--
-- Name: historico_productos_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY historico_productos
  ADD CONSTRAINT historico_productos_usuario_fkey FOREIGN KEY (usuario) REFERENCES usuarios (id);

--
-- Name: horario_auditoria_area_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY horario_auditoria
  ADD CONSTRAINT horario_auditoria_area_fkey FOREIGN KEY (area) REFERENCES areas (id);

--
-- Name: incidencias_accion_mejora_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY incidencias
  ADD CONSTRAINT incidencias_accion_mejora_fkey FOREIGN KEY (accion_mejora) REFERENCES acciones_mejora (id) ON DELETE CASCADE;

--
-- Name: incidencias_proveedor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY incidencias
  ADD CONSTRAINT incidencias_proveedor_fkey FOREIGN KEY (proveedor) REFERENCES proveedores (id) ON DELETE CASCADE;

--
-- Name: indicadores_frecuencia_ana_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY indicadores
  ADD CONSTRAINT indicadores_frecuencia_ana_fkey FOREIGN KEY (frecuencia_ana) REFERENCES tipo_frecuencia_seg (id);

--
-- Name: indicadores_frecuencia_seg_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY indicadores
  ADD CONSTRAINT indicadores_frecuencia_seg_fkey FOREIGN KEY (frecuencia_seg) REFERENCES tipo_frecuencia_seg (id) ON DELETE CASCADE;

--
-- Name: mantenimientos_equipo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY mantenimientos
  ADD CONSTRAINT mantenimientos_equipo_fkey FOREIGN KEY (equipo) REFERENCES equipos (id);

--
-- Name: mensajes_destinatario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY mensajes
  ADD CONSTRAINT mensajes_destinatario_fkey FOREIGN KEY (destinatario) REFERENCES usuarios (id) ON DELETE CASCADE;

--
-- Name: menu_idiomas_nuevo_idioma_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY menu_idiomas_nuevo
  ADD CONSTRAINT menu_idiomas_nuevo_idioma_id_fkey FOREIGN KEY (idioma_id) REFERENCES idiomas (id);

--
-- Name: metas_objetivos_objetivo_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY metas_objetivos
  ADD CONSTRAINT metas_objetivos_objetivo_id_fkey FOREIGN KEY (objetivo_id) REFERENCES objetivos_globales (id);

--
-- Name: objetivos_globales_aprobadopor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY objetivos_globales
  ADD CONSTRAINT objetivos_globales_aprobadopor_fkey FOREIGN KEY (aprobadopor) REFERENCES usuarios (id);

--
-- Name: objetivos_globales_revisadopor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY objetivos_globales
  ADD CONSTRAINT objetivos_globales_revisadopor_fkey FOREIGN KEY (revisadopor) REFERENCES usuarios (id);

--
-- Name: objetivos_indicadores_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY objetivos
  ADD CONSTRAINT objetivos_indicadores_fkey FOREIGN KEY (indicadores) REFERENCES indicadores (id);

--
-- Name: objetivos_indicadores_indicador_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY objetivos_indicadores
  ADD CONSTRAINT objetivos_indicadores_indicador_fkey FOREIGN KEY (indicador) REFERENCES indicadores (id);

--
-- Name: objetivos_indicadores_proceso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY objetivos_indicadores
  ADD CONSTRAINT objetivos_indicadores_proceso_fkey FOREIGN KEY (proceso) REFERENCES procesos (id);

--
-- Name: perfiles_mejora_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY perfiles
  ADD CONSTRAINT perfiles_mejora_fkey FOREIGN KEY (mejora) REFERENCES acciones_mejora (id);

--
-- Name: plantilla_curso_tipo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY plantilla_curso
  ADD CONSTRAINT plantilla_curso_tipo_fkey FOREIGN KEY (tipo) REFERENCES tipos_cursos (id);

--
-- Name: preguntas_legislacion_aplicable_legislacion_aplicable_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY preguntas_legislacion_aplicable
  ADD CONSTRAINT preguntas_legislacion_aplicable_legislacion_aplicable_fkey FOREIGN KEY (legislacion_aplicable) REFERENCES legislacion_aplicable (id) ON DELETE CASCADE;

--
-- Name: productos_proveedor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY productos
  ADD CONSTRAINT productos_proveedor_fkey FOREIGN KEY (proveedor) REFERENCES proveedores (id) ON DELETE CASCADE;

--
-- Name: profesores_curso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY profesores
  ADD CONSTRAINT profesores_curso_fkey FOREIGN KEY (curso) REFERENCES cursos (id) ON DELETE CASCADE;

--
-- Name: profesores_usuario_interno_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY profesores
  ADD CONSTRAINT profesores_usuario_interno_fkey FOREIGN KEY (usuario_interno) REFERENCES usuarios (id) ON DELETE CASCADE;

--
-- Name: requisitos_puesto_ft_requisitos_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY requisitos_puesto_ft
  ADD CONSTRAINT requisitos_puesto_ft_requisitos_fkey FOREIGN KEY (requisitos) REFERENCES requisitos_puesto (id);

--
-- Name: requisitos_puesto_promocion_autonomia_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY requisitos_puesto_promocion
  ADD CONSTRAINT requisitos_puesto_promocion_autonomia_fkey FOREIGN KEY (autonomia) REFERENCES tipo_grado_promocion (id);

--
-- Name: requisitos_puesto_promocion_relaciones_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY requisitos_puesto_promocion
  ADD CONSTRAINT requisitos_puesto_promocion_relaciones_fkey FOREIGN KEY (relaciones) REFERENCES tipo_grado_promocion (id);

--
-- Name: seguimientos_objetivos_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY seguimientos
  ADD CONSTRAINT seguimientos_objetivos_fkey FOREIGN KEY (objetivos) REFERENCES objetivos_globales (id);

--
-- Name: tareas_accion_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY tareas
  ADD CONSTRAINT tareas_accion_fkey FOREIGN KEY (accion) REFERENCES tipo_tarea (id);

--
-- Name: tareas_documento_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY tareas
  ADD CONSTRAINT tareas_documento_fkey FOREIGN KEY (documento) REFERENCES documentos (id);

--
-- Name: tareas_usuario_destino_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY tareas
  ADD CONSTRAINT tareas_usuario_destino_fkey FOREIGN KEY (usuario_destino) REFERENCES usuarios (id);

--
-- Name: tareas_usuario_origen_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY tareas
  ADD CONSTRAINT tareas_usuario_origen_fkey FOREIGN KEY (usuario_origen) REFERENCES usuarios (id);

--
-- Name: tipo_acciones_idioma_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY tipo_acciones
  ADD CONSTRAINT tipo_acciones_idioma_fkey FOREIGN KEY (idioma) REFERENCES idiomas (id);

--
-- Name: tipo_ambito_aplicacion_idioma_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY tipo_ambito_aplicacion
  ADD CONSTRAINT tipo_ambito_aplicacion_idioma_fkey FOREIGN KEY (idioma) REFERENCES idiomas (id);

--
-- Name: tipo_area_aplicacion_idioma_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY tipo_area_aplicacion
  ADD CONSTRAINT tipo_area_aplicacion_idioma_fkey FOREIGN KEY (idioma) REFERENCES idiomas (id);

--
-- Name: tipo_documento_idioma_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY tipo_documento
  ADD CONSTRAINT tipo_documento_idioma_fkey FOREIGN KEY (idioma) REFERENCES idiomas (id);

--
-- Name: tipo_impactos_idioma_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY tipo_impactos
  ADD CONSTRAINT tipo_impactos_idioma_fkey FOREIGN KEY (idioma) REFERENCES idiomas (id);

--
-- Name: usuarios_ficha_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY usuarios
  ADD CONSTRAINT usuarios_ficha_fkey FOREIGN KEY (ficha) REFERENCES ficha_personal (id);

--
-- Name: usuarios_requisitos_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY usuarios
  ADD CONSTRAINT usuarios_requisitos_fkey FOREIGN KEY (requisitos) REFERENCES requisitos_puesto (id);

--
-- Name: valores_indicador_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY valores
  ADD CONSTRAINT valores_indicador_fkey FOREIGN KEY (indicador) REFERENCES indicadores (id) ON DELETE CASCADE;

--
-- Name: valores_proceso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: qnova
--

ALTER TABLE ONLY valores
  ADD CONSTRAINT valores_proceso_fkey FOREIGN KEY (proceso) REFERENCES contenido_procesos (id);

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

