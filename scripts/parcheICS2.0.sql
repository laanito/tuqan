CREATE TABLE tipo_magnitud_idiomas (
  id        SERIAL PRIMARY KEY,
  valor     VARCHAR(100),
  magnitud  INTEGER,
  idioma_id INTEGER
);

INSERT INTO tipo_magnitud_idiomas (valor, magnitud, idioma_id) VALUES ('-5% de generacion que el año anterior', 5, 1);
INSERT INTO tipo_magnitud_idiomas (valor, magnitud, idioma_id) VALUES ('-5% de generacio que l''ANY anterior ',5,2);
INSERT INTO tipo_magnitud_idiomas (valor,magnitud,idioma_id) VALUES ('+-5% de generacion que el año anterior.<br />Ausencia de datos',6,1);
INSERT INTO tipo_magnitud_idiomas (valor,magnitud,idioma_id) VALUES (' - 5 % de generacio que l''any anterior.<br />Absencia de dades',
                                                                       6, 2);
INSERT INTO tipo_magnitud_idiomas (valor, magnitud, idioma_id) VALUES ('+5% de generacion que el año anterior', 7, 1);
INSERT INTO tipo_magnitud_idiomas (valor, magnitud, idioma_id) VALUES ('+5% de generacio que l''ANY anterior ',7,2);

INSERT INTO botones (menu,texto,accion,permisos,tipo_botones) VALUES (96,'Idioma','sndReq(''administracion:magnitudidioma:listado:idioma:fila'', '''', 1)','{f,
  t, f, f, f, f, f, f, f, f, f, f, f, f, t, f, f, f, f, f, f, f}',4);

INSERT INTO botones_idiomas (valor,boton,idioma_id) VALUES ('Idioma',240,1);
INSERT INTO botones_idiomas (valor,boton,idioma_id) VALUES ('Idioma',240,2);




--
-- Traduccion de Administracion-->Aspectos ambientales-->Severidad
--

CREATE TABLE tipo_gravedad_idiomas (
    id SERIAL PRIMARY KEY,
    valor varchar(100),
    gravedad integer,
    idioma_id integer
 );


INSERT INTO tipo_gravedad_idiomas (valor,gravedad,idioma_id) VALUES ('Sin repercusiones importantes',5,1);
INSERT INTO tipo_gravedad_idiomas (valor,gravedad,idioma_id) VALUES ('Repercusiones intracentro',6,1);
INSERT INTO tipo_gravedad_idiomas (valor,gravedad,idioma_id) VALUES ('Repercusiones intracentro/extracentro',7,1);

INSERT INTO tipo_gravedad_idiomas (valor,gravedad,idioma_id) VALUES ('Sense repercussions importants',5,2);
INSERT INTO tipo_gravedad_idiomas (valor,gravedad,idioma_id) VALUES ('Repercussions intracentro',6,2);
INSERT INTO tipo_gravedad_idiomas (valor,gravedad,idioma_id) VALUES ('Repercussions intracentro/extracentro',7,2);


INSERT INTO botones (menu,texto,accion,permisos,tipo_botones) VALUES (97,'Idioma','sndReq(''administracion:gravedadidioma:listado:idioma:fila'', '''', 1)','{f,
                                                              t, f, f, f, f, f, f, f, f, f, f, f, f, t, f, f, f, f, f,
  f, f}',4);

INSERT INTO botones_idiomas (valor,boton,idioma_id) VALUES ('Idioma',241,1);
INSERT INTO botones_idiomas (valor,boton,idioma_id) VALUES ('Idioma',241,2);



--
-- Traduccion de Administracion-->Aspectos ambientales-->Frecuencia/Severidad
--

CREATE TABLE tipo_frecuencia_idiomas (
    id SERIAL PRIMARY KEY,
    valor varchar(100),
    frecuencia integer,
    idioma_id integer
 );

INSERT INTO tipo_frecuencia_idiomas (valor,frecuencia,idioma_id) VALUES ('Semestral/Trimestral',5,1);
INSERT INTO tipo_frecuencia_idiomas (valor,frecuencia,idioma_id) VALUES ('Mensual',6,1);
INSERT INTO tipo_frecuencia_idiomas (valor,frecuencia,idioma_id) VALUES ('Diaria/Quincenal',7,1);

INSERT INTO tipo_frecuencia_idiomas (valor,frecuencia,idioma_id) VALUES ('Semestral/Trimestral',5,2);
INSERT INTO tipo_frecuencia_idiomas (valor,frecuencia,idioma_id) VALUES ('Mensual',6,2);
INSERT INTO tipo_frecuencia_idiomas (valor,frecuencia,idioma_id) VALUES ('Diaria/Quinzenal',7,2);

INSERT INTO botones (menu,texto,accion,permisos,tipo_botones) VALUES (98,'Idioma','sndReq(''administracion:frecuenciaidioma:listado:idioma:fila'', '''', 1)','{f,
  t, f, f, f, f, f, f, f, f, f, f, f, f, t, f, f, f, f, f, f, f}',4);

INSERT INTO botones_idiomas (valor,boton,idioma_id) VALUES ('Idioma',242,1);
INSERT INTO botones_idiomas (valor,boton,idioma_id) VALUES ('Idioma',242,2);



--
-- Traduccion de Administracion-->Aspectos ambientales-->Probabilidad
--

CREATE TABLE tipo_probabilidad_idiomas (
    id SERIAL PRIMARY KEY,
    valor varchar(100),
    probabilidad integer,
    idioma_id integer
 );

INSERT INTO tipo_probabilidad_idiomas (valor,probabilidad,idioma_id) VALUES ('baja',1,1);
INSERT INTO tipo_probabilidad_idiomas (valor,probabilidad,idioma_id) VALUES ('media',2,1);
INSERT INTO tipo_probabilidad_idiomas (valor,probabilidad,idioma_id) VALUES ('alta',3,1);

INSERT INTO tipo_probabilidad_idiomas (valor,probabilidad,idioma_id) VALUES ('baixa',1,2);
INSERT INTO tipo_probabilidad_idiomas (valor,probabilidad,idioma_id) VALUES ('mitja',2,2);
INSERT INTO tipo_probabilidad_idiomas (valor,probabilidad,idioma_id) VALUES ('alta',3,2);


INSERT INTO botones (menu,texto,accion,permisos,tipo_botones) VALUES (102,'Idioma','sndReq(''administracion:probabilidadidioma:listado:idioma:fila'', '''', 1)','{f,
                                                        t, f, f, f, f, f, f, f, f, f, f, f, f, t, f, f, f, f, f, f, f}',4);

INSERT INTO botones_idiomas (valor,boton,idioma_id) VALUES ('Idioma',243,1);
INSERT INTO botones_idiomas (valor,boton,idioma_id) VALUES ('Idioma',243,2);

--
-- Traduccion de Administracion-->Aspectos ambientales-->Severidad/emergencia
--

CREATE TABLE tipo_severidad_idiomas (
    id SERIAL PRIMARY KEY,
    valor varchar(100),
    severidad integer,
    idioma_id integer
 );

INSERT INTO botones (menu,texto,accion,permisos,tipo_botones) VALUES (103,'Idioma','sndReq(''administracion:severidadidioma:listado:idioma:fila'', '''', 1)','{f,
                                                                                                           t, f, f, f,
                                                                                                           f, f, f, f,
                                                                                                                 f, f,
                                                                                                                 f, f,
                                                                                                                 f, t,
                                                                                                                 f, f,
  f, f, f, f, f}',4);

INSERT INTO botones_idiomas (valor,boton,idioma_id) VALUES ('Idioma',244,1);
INSERT INTO botones_idiomas (valor,boton,idioma_id) VALUES ('Idioma',244,2);


INSERT INTO tipo_severidad_idiomas (valor,severidad,idioma_id) VALUES ('baja',1,1);
INSERT INTO tipo_severidad_idiomas (valor,severidad,idioma_id) VALUES ('media',2,1);
INSERT INTO tipo_severidad_idiomas (valor,severidad,idioma_id) VALUES ('alta',3,1);

INSERT INTO tipo_severidad_idiomas (valor,severidad,idioma_id) VALUES ('baja',1,2);
INSERT INTO tipo_severidad_idiomas (valor,severidad,idioma_id) VALUES ('media',2,2);
INSERT INTO tipo_severidad_idiomas (valor,severidad,idioma_id) VALUES ('alta',3,2);



--
-- Traduccion de Administracion-->Tipos accion mejora
--

CREATE TABLE tipo_acciones_idiomas (
  id SERIAL PRIMARY KEY, 
  valor varchar(100),
  mejora integer,
  idioma_id integer
 );


INSERT INTO botones (menu,texto,accion,permisos,tipo_botones) VALUES (41,'Idioma','sndReq(''administracion:mejoraidioma:listado:idioma:fila'', '''', 1)','{f,
  t, f, f, f, f, f, f, f, f, f, f, f, f, t, f, f, f, f, f, f, f}',4);


INSERT INTO botones_idiomas (valor,boton,idioma_id) VALUES ('Idioma',245,2);
INSERT INTO botones_idiomas (valor,boton,idioma_id) VALUES ('Idioma',245,1);


INSERT INTO tipo_acciones_idiomas (valor,mejora,idioma_id) VALUES ('Auditorias',1,1);
INSERT INTO tipo_acciones_idiomas (valor,mejora,idioma_id) VALUES ('Reclamacion',3,1);
INSERT INTO tipo_acciones_idiomas (valor,mejora,idioma_id) VALUES ('Preventiva/Correctiva',4,1);

INSERT INTO tipo_acciones_idiomas (valor,mejora,idioma_id) VALUES ('Auditorias',1,2);
INSERT INTO tipo_acciones_idiomas (valor,mejora,idioma_id) VALUES ('Reclamacio',3,2);
INSERT INTO tipo_acciones_idiomas (valor,mejora,idioma_id) VALUES ('Preventiva/Correctiva',4,2);



--
-- Traduccion de Administracion-->Tipos ambientales aplicacion
--

CREATE TABLE tipo_ambito_aplicacion_idiomas (
    id SERIAL PRIMARY KEY,
    valor varchar(100),
    tipoamb integer,
    idioma_id integer
);



INSERT INTO botones (menu,texto,accion,permisos,tipo_botones) VALUES (44,'Idioma','sndReq(''administracion:tipoambidioma:listado:idioma:fila'', '''', 1)','{f,
                                               t, f, f, f, f, f, f, f, f, f, f, f, f, t, f, f, f, f, f, f, f}',4);

INSERT INTO botones_idiomas (valor,boton,idioma_id) VALUES ('Idioma',246,1);
INSERT INTO botones_idiomas (valor,boton,idioma_id) VALUES ('Idioma',246,2);

INSERT INTO tipo_ambito_aplicacion_idiomas (valor,tipoamb,idioma_id) VALUES ('Ninguno',0,1);
INSERT INTO tipo_ambito_aplicacion_idiomas (valor,tipoamb,idioma_id) VALUES ('Cap',0,2);


--
-- Traduccion de Administracion-->Tipos Impactos Ambientales
--


CREATE TABLE tipo_impactos_idiomas (
    id SERIAL PRIMARY KEY,
    valor varchar(100),
    impactos integer,
    idioma_id integer
);




INSERT INTO botones (menu,texto,accion,permisos,tipo_botones) VALUES (58,'Idioma','sndReq(''administracion:tipoimpidioma:listado:idioma:fila'', '''', 1)','{f,
                                                                                         t, f, f, f, f, f, f, f, f, f,
                                                                                                  f, f, f, t, f, f, f,
                                                                                                           f, f, f, f}',4);


INSERT INTO botones_idiomas (valor,boton,idioma_id) VALUES ('Idioma',247,1);
INSERT INTO botones_idiomas (valor,boton,idioma_id) VALUES ('Idioma',247,2);


INSERT INTO tipo_impactos_idiomas (valor,impactos,idioma_id) VALUES ('Ninguno',0,1);
INSERT INTO tipo_impactos_idiomas (valor,impactos,idioma_id) VALUES ('Contaminación del agua',13,1);
INSERT INTO tipo_impactos_idiomas (valor,impactos,idioma_id) VALUES ('Contaminación atmosférica',14,1);
INSERT INTO tipo_impactos_idiomas (valor,impactos,idioma_id) VALUES ('Contaminación acústica',15,1);
INSERT INTO tipo_impactos_idiomas (valor,impactos,idioma_id) VALUES ('Contaminación del suelo',16,1);
INSERT INTO tipo_impactos_idiomas (valor,impactos,idioma_id) VALUES ('Agotamiento de recursos',17,1);
INSERT INTO tipo_impactos_idiomas (valor,impactos,idioma_id) VALUES ('Impacto paisajístico',18,1);

INSERT INTO tipo_impactos_idiomas (valor,impactos,idioma_id) VALUES ('Cap',0,2);
INSERT INTO tipo_impactos_idiomas (valor,impactos,idioma_id) VALUES ('Contaminaciò de l''aigua', 13, 2);
INSERT INTO tipo_impactos_idiomas (valor, impactos, idioma_id) VALUES ('Contaminaciò atmosfèrica', 14, 2);
INSERT INTO tipo_impactos_idiomas (valor, impactos, idioma_id) VALUES ('Contaminaciò acùstica', 15, 2);
INSERT INTO tipo_impactos_idiomas (valor, impactos, idioma_id) VALUES ('Contaminaciò del sòl', 16, 2);
INSERT INTO tipo_impactos_idiomas (valor, impactos, idioma_id) VALUES ('Agotament de recursos', 17, 2);
INSERT INTO tipo_impactos_idiomas (valor, impactos, idioma_id) VALUES ('Impacte paisatjìstic', 18, 2);

--
-- Traduccion de Administracion-->Tipos Documentos
--


CREATE TABLE tipo_documento_idiomas (
  id        SERIAL PRIMARY KEY,
  valor     VARCHAR(100),
  tipodoc   INTEGER,
  idioma_id INTEGER
);


INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (64, 'Idioma', 'sndReq(''administracion:tipodocidioma:listado:idioma:fila'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);


INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Idioma', 248, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Idioma', 248, 2);


INSERT INTO tipo_documento_idiomas (valor, tipodoc, idioma_id) VALUES ('Manual', 1, 1);
INSERT INTO tipo_documento_idiomas (valor, tipodoc, idioma_id) VALUES ('Procedimiento General', 2, 1);
INSERT INTO tipo_documento_idiomas (valor, tipodoc, idioma_id) VALUES ('Procedimiento Operativo', 3, 1);
INSERT INTO tipo_documento_idiomas (valor, tipodoc, idioma_id) VALUES ('Docs. y formatos', 4, 1);
INSERT INTO tipo_documento_idiomas (valor, tipodoc, idioma_id) VALUES ('Proceso', 5, 1);
INSERT INTO tipo_documento_idiomas (valor, tipodoc, idioma_id) VALUES ('Politica Integral', 6, 1);
INSERT INTO tipo_documento_idiomas (valor, tipodoc, idioma_id) VALUES ('Objetivos', 8, 1);
INSERT INTO tipo_documento_idiomas (valor, tipodoc, idioma_id) VALUES ('Ficha Requisitos Legales', 9, 1);
INSERT INTO tipo_documento_idiomas (valor, tipodoc, idioma_id) VALUES ('Analisis Ambiental Inicial', 11, 1);
INSERT INTO tipo_documento_idiomas (valor, tipodoc, idioma_id) VALUES ('Normativa', 12, 1);
INSERT INTO tipo_documento_idiomas (valor, tipodoc, idioma_id) VALUES ('Adjuntos', 13, 1);
INSERT INTO tipo_documento_idiomas (valor, tipodoc, idioma_id) VALUES ('Plan Emerg. Amb.', 14, 1);


INSERT INTO tipo_documento_idiomas (valor, tipodoc, idioma_id) VALUES ('Manual', 1, 2);
INSERT INTO tipo_documento_idiomas (valor, tipodoc, idioma_id) VALUES ('Procediment general', 2, 2);
INSERT INTO tipo_documento_idiomas (valor, tipodoc, idioma_id) VALUES ('Procediment Operatiu', 3, 2);
INSERT INTO tipo_documento_idiomas (valor, tipodoc, idioma_id) VALUES ('Docs. y formatos', 4, 2);
INSERT INTO tipo_documento_idiomas (valor, tipodoc, idioma_id) VALUES ('Proces', 5, 2);
INSERT INTO tipo_documento_idiomas (valor, tipodoc, idioma_id) VALUES ('Politica Integral', 6, 2);
INSERT INTO tipo_documento_idiomas (valor, tipodoc, idioma_id) VALUES ('Objectius', 8, 2);
INSERT INTO tipo_documento_idiomas (valor, tipodoc, idioma_id) VALUES ('Fitxa Requisits Legals', 9, 2);
INSERT INTO tipo_documento_idiomas (valor, tipodoc, idioma_id) VALUES ('Analisi Ambiental Inicial', 11, 2);
INSERT INTO tipo_documento_idiomas (valor, tipodoc, idioma_id) VALUES ('Normatives', 12, 2);
INSERT INTO tipo_documento_idiomas (valor, tipodoc, idioma_id) VALUES ('Adjunts', 13, 2);
INSERT INTO tipo_documento_idiomas (valor, tipodoc, idioma_id) VALUES ('Pla Emerg. Amb.', 14, 2);

--
-- Insercion de botones de excel en todos los listados
--

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (37, 'Bajar xls', 'sndReq(''administracion:documentossg:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 249, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 249, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (1, 'Bajar xls', 'sndReq(''inicio:mensajes:excel:ver'','''',1)', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 250, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 250, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (3, 'Bajar xls', 'sndReq(''documentacion:manual:excel:ver'','''',1)', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}',
   1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 251, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 251, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (5, 'Bajar xls', 'sndReq(''documentacion:objetivos:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 252, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 252, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (7, 'Bajar xls', 'sndReq(''documentacion:pg:excel:ver'','''',1)', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 253, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 253, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (8, 'Bajar xls', 'sndReq(''documentacion:pe:excel:ver'','''',1)', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 254, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 254, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (10, 'Bajar xls', 'sndReq(''documentacion:docvigor:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 255, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 255, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (57, 'Bajar xls', 'sndReq(''documentacion:docborrador:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 256, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 256, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (62, 'Bajar xls', 'sndReq(''documentacion:aai:excel:ver'','''',1)', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}',
   1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 257, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 257, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (104, 'Bajar xls', 'sndReq(''documentacion:planamb:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 258, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 258, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (9, 'Bajar xls', 'sndReq(''documentacion:frl:excel:ver'','''',1)', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}',
   1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 259, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 259, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (12, 'Bajar xls', 'sndReq(''documentacion:legislacion:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 260, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 260, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (63, 'Bajar xls', 'sndReq(''documentacion:normativa:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 261, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 261, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (11, 'Bajar xls', 'sndReq(''documentacion:registros:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 262, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 262, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (13, 'Bajar xls', 'sndReq(''documentacion:docformatos:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 263, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 263, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (14, 'Bajar xls', 'sndReq(''mejora:listado:excel:ver'','''',1)', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 264, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 264, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (17, 'Bajar xls', 'sndReq(''formacion:cursos:excel:ver'','''',1)', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}',
   1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 265, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 265, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (18, 'Bajar xls', 'sndReq(''formacion:inscripcion:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 266, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 266, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (19, 'Bajar xls', 'sndReq(''formacion:planes:excel:ver'','''',1)', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}',
   1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 267, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 267, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (23, 'Bajar xls', 'sndReq(''auditoria:programa:excel:ver'','''',1)', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}',
   1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 268, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 268, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (24, 'Bajar xls', 'sndReq(''auditoria:plan:excel:ver'','''',1)', '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 269, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 269, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (31, 'Bajar xls', 'sndReq(''indicadores:indicadores:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 270, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 270, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (50, 'Bajar xls', 'sndReq(''indicadores:indobjetivos:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 271, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 271, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (26, 'Bajar xls', 'sndReq(''aambientales:revision:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 272, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 272, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (101, 'Bajar xls', 'sndReq(''aambientales:aspectoemergencia:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 273, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 273, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (32, 'Bajar xls', 'sndReq(''administracion:usuario:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 274, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 274, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (33, 'Bajar xls', 'sndReq(''administracion:perfiles:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 275, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 275, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (35, 'Bajar xls', 'sndReq(''administracion:adminmensajes:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 276, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 276, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (36, 'Bajar xls', 'sndReq(''administracion:admintareas:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 277, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 277, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (93, 'Bajar xls', 'sndReq(''administracion:adminmenus:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 278, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 278, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (108, 'Bajar xls', 'sndReq(''administracion:hospitales:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 279, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 279, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (38, 'Bajar xls', 'sndReq(''administracion:adminregistros2:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 280, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 280, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (39, 'Bajar xls', 'sndReq(''administracion:adminnormativa:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 281, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 281, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (41, 'Bajar xls', 'sndReq(''administracion:adminmejora:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 282, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 282, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (43, 'Bajar xls', 'sndReq(''administracion:admintipoarea:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 283, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 283, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (44, 'Bajar xls', 'sndReq(''administracion:admintipoamb:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 284, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 284, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (55, 'Bajar xls', 'sndReq(''administracion:adminlegaplicable:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 285, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 285, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (58, 'Bajar xls', 'sndReq(''administracion:admintipoimpacto:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 286, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 286, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (61, 'Bajar xls', 'sndReq(''administracion:admintipo_cursos:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 287, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 287, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (64, 'Bajar xls', 'sndReq(''administracion:admintipo_doc:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 288, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 288, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (96, 'Bajar xls', 'sndReq(''administracion:magnitud:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 289, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 289, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (97, 'Bajar xls', 'sndReq(''administracion:gravedad:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 290, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 290, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (98, 'Bajar xls', 'sndReq(''administracion:frecuencia:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 291, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 291, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (100, 'Bajar xls', 'sndReq(''administracion:formula:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 292, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 292, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (102, 'Bajar xls', 'sndReq(''administracion:probabilidad:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 293, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 293, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (103, 'Bajar xls', 'sndReq(''administracion:severidad:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 294, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 294, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (106, 'Bajar xls', 'sndReq(''administracion:ayuda:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 295, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 295, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (107, 'Bajar xls', 'sndReq(''administracion:permisos:excel:ver'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Bajar xls', 296, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Baixar xls', 296, 2);


INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (55, 'Dar de Baja', 'sndReq(''administracion:adminlegaplicable:comun:baja:general'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 4);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Dar de Baja', 297, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Donar de Baixa', 297, 2);

--
-- Milestone 29 Nov 2006

--
-- Dar de baja en Procesos->catalogo
--

ALTER TABLE procesos
  ADD COLUMN activo BOOLEAN DEFAULT TRUE;

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (28, 'Eliminar', 'parent.sndReq(''procesos:catalogo:comun:baja:radio'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Dar de baja', 298, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Donar de baixa', 298, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (20, 'Editar', 'sndReq(''equipos:equipo:formulario:editar:fila'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 2);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Editar', 299, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Editar', 299, 2);

--
-- Jueves 30 nov 2006
-- Nuevo menu Proveedores en menu Administracion
--

INSERT INTO menu_nuevo (accion, permisos, padre, orden)
VALUES ('', '{f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 9999, 109);
INSERT INTO menu_idiomas_nuevo (valor, menu, idioma_id) VALUES ('Proveedores', 109, 1);
INSERT INTO menu_idiomas_nuevo (valor, menu, idioma_id) VALUES ('Proveidors', 109, 2);

--
-- Submenus de menu Administracion - Proveedores
--

INSERT INTO menu_nuevo (accion, permisos, padre, orden)
VALUES ('administracion:proveedores:listado:ver', '{f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 109, 110);
INSERT INTO menu_idiomas_nuevo (valor, menu, idioma_id) VALUES ('Listado', 110, 1);
INSERT INTO menu_idiomas_nuevo (valor, menu, idioma_id) VALUES ('Llistat', 110, 2);

INSERT INTO menu_nuevo (accion, permisos, padre, orden)
VALUES ('administracion:proveedores:listado:indicadores', '{f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 109, 111);
INSERT INTO menu_idiomas_nuevo (valor, menu, idioma_id) VALUES ('Incidencias', 111, 1);
INSERT INTO menu_idiomas_nuevo (valor, menu, idioma_id) VALUES ('Incidencies', 111, 2);

INSERT INTO menu_nuevo (accion, permisos, padre, orden)
VALUES ('administracion:proveedores:listado:contacto', '{f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 109, 112);
INSERT INTO menu_idiomas_nuevo (valor, menu, idioma_id) VALUES ('Contactos', 112, 1);
INSERT INTO menu_idiomas_nuevo (valor, menu, idioma_id) VALUES ('Contactes', 112, 2);

INSERT INTO menu_nuevo (accion, permisos, padre, orden)
VALUES ('administracion:proveedores:listado:producto', '{f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 109, 113);
INSERT INTO menu_idiomas_nuevo (valor, menu, idioma_id) VALUES ('Productos', 113, 1);
INSERT INTO menu_idiomas_nuevo (valor, menu, idioma_id) VALUES ('Productes', 113, 2);

-- Viernes 1 dic 2006
-- Añadir columna activo a incidencias.

ALTER TABLE incidencias
  ADD COLUMN activo BOOLEAN DEFAULT TRUE;

--
-- Botones para Submenus Administracion - Proveedores
--

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (110, 'Dar de baja', 'sndReq(''administracion:proveedorlistado:comun:baja:general'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Dar de baja', 300, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Donar de baixa', 300, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (110, 'Dar de alta', 'sndReq(''administracion:proveedorlistado:comun:alta:general'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Dar de alta', 301, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Donar d''alta',301,2);

INSERT INTO botones (menu,texto,accion,permisos,tipo_botones) VALUES (111,'Dar de baja','sndReq(''administracion:proveedorincidencias:comun:baja:general'', '''', 1)','{f,
  t, f, f, f, f, f, f, f, f, f, f, f, f, t, f, f, f, f, f, f, f}',3);
INSERT INTO botones_idiomas (valor,boton,idioma_id) values ('Dar de baja',302,1);
INSERT INTO botones_idiomas (valor,boton,idioma_id) values ('Donar de baixa',302,2);

INSERT INTO botones (menu,texto,accion,permisos,tipo_botones) VALUES (111,'Dar de alta','sndReq(''administracion:proveedorincidencias:comun:alta:general'', '''', 1)','{f,
                                                              t, f, f, f, f, f, f, f, f, f, f, f, f, t, f, f, f, f, f,
                                                              f, f}',3);
INSERT INTO botones_idiomas (valor,boton,idioma_id) values ('Dar de alta',303,1);
INSERT INTO botones_idiomas (valor,boton,idioma_id) values ('Donar D ''alta', 303, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (112, 'Dar de baja', 'sndReq(''administracion:proveedorcontactos:comun:baja:general'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Dar de baja', 304, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Donar de baixa', 304, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (112, 'Dar de alta', 'sndReq(''administracion:proveedorcontactos:comun:alta:general'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Dar de alta', 305, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Donar d''alta',305,2);

INSERT INTO botones (menu,texto,accion,permisos,tipo_botones) VALUES (113,'Dar de baja','sndReq(''administracion:proveedorproductos:comun:baja:general'', '''', 1)','{f,
  t, f, f, f, f, f, f, f, f, f, f, f, f, t, f, f, f, f, f, f, f}',3);
INSERT INTO botones_idiomas (valor,boton,idioma_id) values ('Dar de baja',306,1);
INSERT INTO botones_idiomas (valor,boton,idioma_id) values ('Donar de baixa',306,2);

INSERT INTO botones (menu,texto,accion,permisos,tipo_botones) VALUES (113,'Dar de alta','sndReq(''administracion:proveedorproductos:comun:alta:general'', '''', 1)','{f,
                                                              t, f, f, f, f, f, f, f, f, f, f, f, f, t, f, f, f, f, f,
                                                              f, f}',3);
INSERT INTO botones_idiomas (valor,boton,idioma_id) values ('Dar de alta',307,1);
INSERT INTO botones_idiomas (valor,boton,idioma_id) values ('Donar D ''alta', 307, 2);

--
-- Menu Administracion - Equipos
--

INSERT INTO menu_nuevo (accion, permisos, padre, orden)
VALUES ('', '{f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 9999, 114);
INSERT INTO menu_idiomas_nuevo (valor, menu, idioma_id) VALUES ('Equipos', 114, 1);
INSERT INTO menu_idiomas_nuevo (valor, menu, idioma_id) VALUES ('Equips', 114, 2);

INSERT INTO menu_nuevo (accion, permisos, padre, orden)
VALUES ('administracion:equipos:listado:ver', '{f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}', 114, 115);
INSERT INTO menu_idiomas_nuevo (valor, menu, idioma_id) VALUES ('Listado', 115, 1);
INSERT INTO menu_idiomas_nuevo (valor, menu, idioma_id) VALUES ('Llistat', 115, 2);

--
-- Botones para menu Administracion - Equipos - Listado
--

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (115, 'Dar de baja', 'sndReq(''administracion:equiposlistado:comun:baja:general'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Dar de baja', 308, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Donar de baixa', 308, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (115, 'Dar de alta', 'sndReq(''administracion:equiposlistado:comun:alta:general'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Dar de alta', 309, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Donar d''alta',309,2);

--
-- Menu para Administracion - Auditorias
--

insert into menu_nuevo (accion,permisos,padre,orden) values ('','{f, f, f, f, f, f, f, f, f, f, f, f, f, f, f, f, f, f,
                                                                                                   f, f, f, f}',74,116);
insert into menu_idiomas_nuevo (valor,menu,idioma_id) values ('Auditorias',116,1);
insert into menu_idiomas_nuevo (valor,menu,idioma_id) values ('Auditorias',116,2);

insert into menu_nuevo (accion,permisos,padre,orden) values ('administracion:auditoriaanual:listado:ver','{f, f, f, f,
                                                                                                            f, f, f, f,
                                                                                                            f, f, f, f,
                                                                                                                  f, f,
                                                                                                                  f, f,
                                                                                                                  f, f,
                                                                                                                  f, f,
  f, f}',116,117);
insert into menu_idiomas_nuevo (valor,menu,idioma_id) values ('Auditoria anual',117,1);
insert into menu_idiomas_nuevo (valor,menu,idioma_id) values ('Auditoria anual',117,2);

insert into menu_nuevo (accion,permisos,padre,orden) values ('administracion:auditoriavigor:listado:ver','{f, f, f, f,
  f, f, f, f, f, f, f, f, f, f, f, f, f, f, f, f, f, f}',116,118);
insert into menu_idiomas_nuevo (valor,menu,idioma_id) values ('Auditoria en vigor',118,1);
insert into menu_idiomas_nuevo (valor,menu,idioma_id) values ('Auditoria en vigor',118,2);

--
-- Botones para Administracion - Auditorias
--

INSERT INTO botones (menu,texto,accion,permisos,tipo_botones) VALUES (117,'Dar de baja','sndReq(''administracion:auditoriaanual:comun:baja:general'', '''', 1)','{f,
                                               t, f, f, f, f, f, f, f, f, f, f, f, f, t, f, f, f, f, f, f, f}',3);
INSERT INTO botones_idiomas (valor,boton,idioma_id) values ('Dar de baja',310,1);
INSERT INTO botones_idiomas (valor,boton,idioma_id) values ('Donar de baixa',310,2);

INSERT INTO botones (menu,texto,accion,permisos,tipo_botones) VALUES (117,'Dar de alta','sndReq(''administracion:auditoriaanual:comun:alta:general'', '''', 1)','{f,
                                                                                                  t, f, f, f, f, f, f,
                                                                                                                    f,
                                                                                                                    f,
                                                                                                                    f,
                                                                                                                    f,
                                                                                                                    f,
                                                                                                                    f,
                                                                                                                    t,
                                                                                                                    f,
                                                                                                                    f,
                                                              f, f, f, f, f}',3);
INSERT INTO botones_idiomas (valor,boton,idioma_id) values ('Dar de alta',311,1);
INSERT INTO botones_idiomas (valor,boton,idioma_id) values ('Donar D ''alta', 311, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (118, 'Dar de baja', 'sndReq(''administracion:auditoriavigor:comun:baja:general'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Dar de baja', 312, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Donar de baixa', 312, 2);

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (118, 'Dar de alta', 'sndReq(''administracion:auditoriavigor:comun:alta:general'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 3);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Dar de alta', 313, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Donar d''alta',313,2);

--
--  Menu para Administracion - Indicadores
--

insert into menu_nuevo (accion,permisos,padre,orden) values ('','{f, f, f, f, f, f, f, f, f, f, f, f, f, f, f, f, f, f,
                                                                                                   f, f, f, f}',74,119);
insert into menu_idiomas_nuevo (valor,menu,idioma_id) values ('Indicadores',119,1);
insert into menu_idiomas_nuevo (valor,menu,idioma_id) values ('Indicadors',119,2);

insert into menu_nuevo (accion,permisos,padre,orden) values ('administracion:indicadoresobjetivo:listado:ver','{f, f, f,
                                                                                                            f, f, f, f,
                                                                                                            f, f, f, f,
                                                                                                                     f,
                                                                                                                     f,
                                                                                                                     f,
                                                                                                                     f,
                                                                                                                     f,
                                                                                                                     f,
                                                                                                                     f,
                                                                                                                     f,
                                                                                                                     f,
  f, f}',119,120);
insert into menu_idiomas_nuevo (valor,menu,idioma_id) values ('Objetivos',120,1);
insert into menu_idiomas_nuevo (valor,menu,idioma_id) values ('Objectius',120,2);

--
-- Botones para Administracion - Indicadores - objetivos
--

INSERT INTO botones (menu,texto,accion,permisos,tipo_botones) VALUES (120,'Dar de baja','sndReq(''administracion:indicadoresobjetivo:comun:baja:general'', '''', 1)','{f,
  t, f, f, f, f, f, f, f, f, f, f, f, f, t, f, f, f, f, f, f, f}',3);
INSERT INTO botones_idiomas (valor,boton,idioma_id) values ('Dar de baja',314,1);
INSERT INTO botones_idiomas (valor,boton,idioma_id) values ('Donar de baixa',314,2);

INSERT INTO botones (menu,texto,accion,permisos,tipo_botones) VALUES (120,'Dar de alta','sndReq(''administracion:indicadoresobjetivo:comun:alta:general'', '''', 1)','{f,
                                                        t, f, f, f, f, f, f, f, f, f, f, f, f, t, f, f, f, f, f, f, f}',3);
INSERT INTO botones_idiomas (valor,boton,idioma_id) values ('Dar de alta',315,1);
INSERT INTO botones_idiomas (valor,boton,idioma_id) values ('Donar D ''alta', 315, 2);

--
--    Cambios acciones de proveedores homologados
--

UPDATE botones
SET accion = 'sndReq(''proveedores:proveedor:listado:ver:fila'','''',1)' WHERE id = 22;

-- 4-12-2006
-- Insertar boton de dar alta en Administracion-->Leg. Aplicable 
--

INSERT INTO botones (menu, texto, accion, permisos, tipo_botones) VALUES
  (55, 'Dar de Alta', 'sndReq(''administracion:adminlegaplicable:comun:alta:general'','''',1)',
   '{f,t,f,f,f,f,f,f,f,f,f,f,f,f,t,f,f,f,f,f,f,f}', 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Dar de Alta', 316, 1);
INSERT INTO botones_idiomas (valor, boton, idioma_id) VALUES ('Donar de Alta', 316, 2);

-- 4-12-2006 albatista
-- Cambio de accion de boton debido a que da conflictos
--
UPDATE botones
SET accion = 'parent.sndReq(''catalogo:fichaproceso:comun:ver:radio'','''',1)' WHERE id = 89;