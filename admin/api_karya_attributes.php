<?php
require_once 'config.php';
require_once 'functions.php';
header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => 'Invalid request.'
];

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['type'])) {
        $type = $_GET['type'];
        $data = [];

        if ($type == 'jenis') {
            // Mengambil jenis karya dan nama kategori terkait
            $sql = "SELECT j.id_jenis AS id, j.jenis AS name, k.id_kategori, k.nama_kategori AS kategori_name 
                    FROM jenis_karya j
                    LEFT JOIN kategori k ON j.id_kategori = k.id_kategori
                    ORDER BY j.jenis ASC";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $data[] = $row;
                }
                $response = $data;
            } else {
                $response['message'] = 'Failed to fetch data: ' . mysqli_error($conn);
            }
        } elseif ($type == 'kategori') {
            // Mengambil kategori saja
            $sql = "SELECT id_kategori AS id, nama_kategori AS name FROM kategori ORDER BY nama_kategori ASC";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $data[] = $row;
                }
                $response = $data;
            } else {
                $response['message'] = 'Failed to fetch data: ' . mysqli_error($conn);
            }
        }
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';
    $type = $_POST['type'] ?? '';
    $id = $_POST['id'] ?? null;
    
    // Tentukan kolom dan tabel berdasarkan tipe
    $table_name = ($type == 'jenis') ? 'jenis_karya' : 'kategori';
    $id_column = ($type == 'jenis') ? 'id_jenis' : 'id_kategori';
    $name_column = ($type == 'jenis') ? 'jenis' : 'nama_kategori';

    if (!empty($action) && !empty($type)) {
        switch ($action) {
            case 'add':
                if ($type == 'jenis') {
                    $name = trim($_POST['jenis'] ?? '');
                    $id_kategori = $_POST['id_kategori'] ?? null;
                    if (empty($name) || empty($id_kategori)) {
                        $response['message'] = 'Nama jenis karya dan kategori tidak boleh kosong.';
                        break;
                    }
                    $sql = "INSERT INTO {$table_name} ({$name_column}, id_kategori) VALUES (?, ?)";
                    if ($stmt = mysqli_prepare($conn, $sql)) {
                        mysqli_stmt_bind_param($stmt, "si", $param_name, $param_id_kategori);
                        $param_name = $name;
                        $param_id_kategori = $id_kategori;
                        if (mysqli_stmt_execute($stmt)) {
                            $response['success'] = true;
                            $response['message'] = 'Data added successfully.';
                            if (isset($_SESSION['id'])) {
                                log_admin_activity($conn, $_SESSION['id'], 'create', "Menambah jenis karya baru: $name");
                            }
                        } else {
                            $response['message'] = 'Failed to add data: ' . mysqli_error($conn);
                        }
                        mysqli_stmt_close($stmt);
                    }
                } else {
                    $name = trim($_POST['nama_kategori'] ?? '');
                    if (empty($name)) {
                        $response['message'] = 'Nama kategori tidak boleh kosong.';
                        break;
                    }
                    $sql = "INSERT INTO {$table_name} ({$name_column}) VALUES (?)";
                    if ($stmt = mysqli_prepare($conn, $sql)) {
                        mysqli_stmt_bind_param($stmt, "s", $param_name);
                        $param_name = $name;
                        if (mysqli_stmt_execute($stmt)) {
                            $response['success'] = true;
                            $response['message'] = 'Data added successfully.';
                            if (isset($_SESSION['id'])) {
                                log_admin_activity($conn, $_SESSION['id'], 'create', "Menambah kategori baru: $name");
                            }
                        } else {
                            $response['message'] = 'Failed to add data: ' . mysqli_error($conn);
                        }
                        mysqli_stmt_close($stmt);
                    }
                }
                break;

            case 'edit':
                if (!empty($id)) {
                    if ($type == 'jenis') {
                        $name = trim($_POST['jenis'] ?? '');
                        $id_kategori = $_POST['id_kategori'] ?? null;
                        if (empty($name) || empty($id_kategori)) {
                            $response['message'] = 'Jenis karya dan kategori tidak boleh kosong.';
                            break;
                        }
                        $sql = "UPDATE {$table_name} SET {$name_column} = ?, id_kategori = ? WHERE {$id_column} = ?";
                        if ($stmt = mysqli_prepare($conn, $sql)) {
                            mysqli_stmt_bind_param($stmt, "sii", $param_name, $param_id_kategori, $param_id);
                            $param_name = $name;
                            $param_id_kategori = $id_kategori;
                            $param_id = $id;
                            if (mysqli_stmt_execute($stmt)) {
                                $response['success'] = true;
                                $response['message'] = 'Data updated successfully.';
                                if (isset($_SESSION['id'])) {
                                    log_admin_activity($conn, $_SESSION['id'], 'update', "Mengedit jenis karya dengan ID $id menjadi: $name");
                                }
                            } else {
                                $response['message'] = 'Failed to update data: ' . mysqli_error($conn);
                            }
                            mysqli_stmt_close($stmt);
                        }
                    } else {
                        $name = trim($_POST['nama_kategori'] ?? '');
                        if (empty($name)) {
                            $response['message'] = 'Nama kategori tidak boleh kosong.';
                            break;
                        }
                        $sql = "UPDATE {$table_name} SET {$name_column} = ? WHERE {$id_column} = ?";
                        if ($stmt = mysqli_prepare($conn, $sql)) {
                            mysqli_stmt_bind_param($stmt, "si", $param_name, $param_id);
                            $param_name = $name;
                            $param_id = $id;
                            if (mysqli_stmt_execute($stmt)) {
                                $response['success'] = true;
                                $response['message'] = 'Data updated successfully.';
                                if (isset($_SESSION['id'])) {
                                    log_admin_activity($conn, $_SESSION['id'], 'update', "Mengedit kategori dengan ID $id menjadi: $name");
                                }
                            } else {
                                $response['message'] = 'Failed to update data: ' . mysqli_error($conn);
                            }
                            mysqli_stmt_close($stmt);
                        }
                    }
                } else {
                    $response['message'] = 'Invalid ID for edit action.';
                }
                break;

            case 'delete':
                if (!empty($id)) {
                    $sql = "DELETE FROM {$table_name} WHERE {$id_column} = ?";
                    if ($stmt = mysqli_prepare($conn, $sql)) {
                        mysqli_stmt_bind_param($stmt, "i", $param_id);
                        $param_id = $id;
                        if (mysqli_stmt_execute($stmt)) {
                            $response['success'] = true;
                            $response['message'] = 'Data deleted successfully.';
                            if (isset($_SESSION['id'])) {
                                log_admin_activity($conn, $_SESSION['id'], 'delete', "Menghapus $type dengan ID $id");
                            }
                        } else {
                            $response['message'] = 'Failed to delete data: ' . mysqli_error($conn);
                        }
                        mysqli_stmt_close($stmt);
                    }
                } else {
                    $response['message'] = 'Invalid ID for delete action.';
                }
                break;

            default:
                $response['message'] = 'Unknown action.';
                break;
        }
    }
}

mysqli_close($conn);
echo json_encode($response);
?>