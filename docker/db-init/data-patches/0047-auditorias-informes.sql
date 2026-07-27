-- 0047-auditorias-informes.sql
-- Stage 9.37: Informe fields on auditorias (legacy informeauditoria form)
-- 0031 had lugar_informe + fecha_informe; conclusions/recommendations were missing.

ALTER TABLE auditorias ADD COLUMN IF NOT EXISTS recomendaciones_informe TEXT;
ALTER TABLE auditorias ADD COLUMN IF NOT EXISTS conclusiones_informe TEXT;

UPDATE auditorias SET
    lugar_informe = COALESCE(NULLIF(TRIM(lugar_informe), ''), 'Sala de reuniones'),
    fecha_informe = COALESCE(fecha_informe, CURRENT_DATE),
    conclusiones_informe = COALESCE(
        NULLIF(TRIM(conclusiones_informe), ''),
        'La auditoría se ha realizado según el programa. Se identifican oportunidades de mejora documentadas en hallazgos y acciones de mejora vinculadas.'
    ),
    recomendaciones_informe = COALESCE(
        NULLIF(TRIM(recomendaciones_informe), ''),
        'Cerrar hallazgos abiertos en plazo. Revisar eficacia de acciones de mejora en la siguiente auditoría de seguimiento.'
    )
WHERE id = 1
  AND EXISTS (SELECT 1 FROM auditorias WHERE id = 1);

INSERT INTO data_patches (filename, applied_at)
VALUES ('0047-auditorias-informes.sql', NOW())
ON CONFLICT (filename) DO NOTHING;
