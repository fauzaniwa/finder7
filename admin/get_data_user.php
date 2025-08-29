<?php
if (!isset($conn)) {
    if (file_exists('config.php')) {
        require_once 'config.php';
    } else {
        http_response_code(500);
        die("Fatal Error: File 'config.php' not found. Please check your file path.");
    }
}

/**
 * Mengambil data pengguna dari tabel 'user' dengan batasan, offset, dan pencarian.
 *
 * @param int $limit Batas jumlah data yang akan diambil.
 * @param int $offset Offset (mulai) data.
 * @param string $search_query Kueri pencarian.
 * @return array Array asosiatif yang berisi data pengguna dan jumlah total data.
 */
function getUsersData($limit, $offset, $search_query = '') {
    global $conn;

    if (!$conn) {
        error_log("Database connection is not established.");
        return ['data' => [], 'total' => 0];
    }

    $users = [];
    $total_users = 0;
    
    // Siapkan klausa WHERE untuk pencarian
    $where_clause = '';
    $bind_types = '';
    $bind_params = [];

    if (!empty($search_query)) {
        $where_clause = " WHERE nama LIKE ? OR instansi LIKE ?";
        $bind_types = "ss";
        $like_query = "%" . $search_query . "%";
        $bind_params = [$like_query, $like_query];
    }

    // Ambil jumlah total data
    $sql_total = "SELECT COUNT(id_user) as total FROM user" . $where_clause;
    if ($stmt_total = mysqli_prepare($conn, $sql_total)) {
        if (!empty($bind_params)) {
            mysqli_stmt_bind_param($stmt_total, $bind_types, ...$bind_params);
        }
        mysqli_stmt_execute($stmt_total);
        $result_total = mysqli_stmt_get_result($stmt_total);
        $row_total = mysqli_fetch_assoc($result_total);
        $total_users = $row_total['total'];
        mysqli_stmt_close($stmt_total);
    }

    // Ambil data dengan batasan (limit), offset, dan pencarian
    $sql_data = "SELECT id_user, nama, tgl_lahir, no_hp, instansi, email, created, kode_account FROM user" . $where_clause . " ORDER BY created DESC LIMIT ? OFFSET ?";
    
    if ($stmt = mysqli_prepare($conn, $sql_data)) {
        if (!empty($bind_params)) {
            $bind_types .= "ii";
            array_push($bind_params, $limit, $offset);
            mysqli_stmt_bind_param($stmt, $bind_types, ...$bind_params);
        } else {
            mysqli_stmt_bind_param($stmt, "ii", $limit, $offset);
        }
        
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            while ($row = mysqli_fetch_assoc($result)) {
                $users[] = $row;
            }
            mysqli_free_result($result);
        } else {
            error_log("SQL Data Error: " . mysqli_error($conn) . " in query: " . $sql_data);
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("SQL Prepare Error: " . mysqli_error($conn));
    }
    
    return ['data' => $users, 'total' => $total_users];
}
?>