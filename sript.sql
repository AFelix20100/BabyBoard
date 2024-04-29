// Pour avoir la liste des équipes auxquels le joueur en fait parti
SELECT DISTINCT T.*
FROM team T
INNER JOIN team_composition TC
    ON TC.team_id = T.id
INNER JOIN player P
    ON P.id = TC.player_id
WHERE (TC.is_host = true OR TC.is_guest = true)
AND P.id = 3;

// Pour avoir la liste des matchs auxquel le joueur a pu participer

SELECT G.*
FROM game G
WHERE G.red_team_id IN (
    SELECT DISTINCT T.id
    FROM team T
             INNER JOIN team_composition TC
                        ON TC.team_id = T.id
    WHERE (TC.is_host = true OR TC.is_guest = true)
      AND TC.player_id = 4
)
   OR G.blue_team_id IN (
    SELECT DISTINCT T.id
    FROM team T
             INNER JOIN team_composition TC
                        ON TC.team_id = T.id
    WHERE (TC.is_host = true OR TC.is_guest = true)
      AND TC.player_id = 4
);

// Pour avoir la liste de défaite et victoires

SELECT
    SUM(CASE WHEN G.winner_team_id = T.id THEN 1 ELSE 0 END) AS nombre_victoires,
    SUM(CASE WHEN G.winner_team_id != T.id THEN 1 ELSE 0 END) AS nombre_defaites
FROM
    team T
        LEFT JOIN
    game G ON T.id = G.red_team_id OR T.id = G.blue_team_id
WHERE
    T.id IN (
        SELECT DISTINCT T.id
        FROM team T
                 INNER JOIN team_composition TC ON TC.team_id = T.id
        WHERE (TC.is_host = true OR TC.is_guest = true)
          AND TC.player_id = 1
    );