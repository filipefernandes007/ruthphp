
SELECT
	pl.*, GROUP_CONCAT(
		DISTINCT CONCAT(
			sat.id,
			'#',
			sat.name,
			'#',
			sat.img
		)
		ORDER BY
			sat.id ASC SEPARATOR '|'
	) AS "satellites",
	GROUP_CONCAT(
		DISTINCT CONCAT_WS('#'
                 , img.id
                 , img.img
                 , img.text)
		SEPARATOR '|'
	) AS "images"
FROM
	planet AS pl
LEFT JOIN satellite AS sat ON pl.id = sat.id_planet
LEFT JOIN images AS img ON img.id_planet = pl.id