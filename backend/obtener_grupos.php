<?php
include '../conexion.php';

$sql = "SELECT * FROM grupos_instrucciones_reglas";

$result = $conn->query($sql);

$grupos = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $grupos[] = [
            "id" => $row["id"],
            "SAP" => $row["SAP"],
            "INSTRUCCION_DE_LAVADO_1" => $row["INSTRUCCION_DE_LAVADO_1"],
            "INSTRUCCION_DE_LAVADO_2" => $row["INSTRUCCION_DE_LAVADO_2"],
            "INSTRUCCION_DE_LAVADO_3" => $row["INSTRUCCION_DE_LAVADO_3"],
            "INSTRUCCION_DE_LAVADO_4" => $row["INSTRUCCION_DE_LAVADO_4"],
            "INSTRUCCION_DE_LAVADO_5" => $row["INSTRUCCION_DE_LAVADO_5"],
            "INSTRUCCION_DE_BLANQUEADO_1" => $row["INSTRUCCION_DE_BLANQUEADO_1"],
            "INSTRUCCION_DE_BLANQUEADO_2" => $row["INSTRUCCION_DE_BLANQUEADO_2"],
            "INSTRUCCION_DE_BLANQUEADO_3" => $row["INSTRUCCION_DE_BLANQUEADO_3"],
            "INSTRUCCION_DE_BLANQUEADO_4" => $row["INSTRUCCION_DE_BLANQUEADO_4"],
            "INSTRUCCION_DE_BLANQUEADO_5" => $row["INSTRUCCION_DE_BLANQUEADO_5"],
            "INSTRUCCION_DE_SECADO_1" => $row["INSTRUCCION_DE_SECADO_1"],
            "INSTRUCCION_DE_SECADO_2" => $row["INSTRUCCION_DE_SECADO_2"],
            "INSTRUCCION_DE_SECADO_3" => $row["INSTRUCCION_DE_SECADO_3"],
            "INSTRUCCION_DE_SECADO_4" => $row["INSTRUCCION_DE_SECADO_4"],
            "INSTRUCCION_DE_SECADO_5" => $row["INSTRUCCION_DE_SECADO_5"],
            "INSTRUCCION_DE_PLANCHADO_1" => $row["INSTRUCCION_DE_PLANCHADO_1"],
            "INSTRUCCION_DE_PLANCHADO_2" => $row["INSTRUCCION_DE_PLANCHADO_2"],
            "INSTRUCCION_DE_PLANCHADO_3" => $row["INSTRUCCION_DE_PLANCHADO_3"],
            "INSTRUCCION_DE_PLANCHADO_4" => $row["INSTRUCCION_DE_PLANCHADO_4"],
            "INSTRUCCION_DE_PLANCHADO_5" => $row["INSTRUCCION_DE_PLANCHADO_5"],
            "INSTRUCC_CUIDADO_TEXTIL_PROF_1" => $row["INSTRUCC_CUIDADO_TEXTIL_PROF_1"],
            "INSTRUCC_CUIDADO_TEXTIL_PROF_2" => $row["INSTRUCC_CUIDADO_TEXTIL_PROF_2"],
            "INSTRUCC_CUIDADO_TEXTIL_PROF_3" => $row["INSTRUCC_CUIDADO_TEXTIL_PROF_3"],
            "INSTRUCC_CUIDADO_TEXTIL_PROF_4" => $row["INSTRUCC_CUIDADO_TEXTIL_PROF_4"],
            "INSTRUCC_CUIDADO_TEXTIL_PROF_5" => $row["INSTRUCC_CUIDADO_TEXTIL_PROF_5"],
            
        ];
    }
}

echo json_encode($grupos);

$conn->close();
?>
