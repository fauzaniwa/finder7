<?php
session_start();
require_once 'admin/config.php';
header('Content-Type: application/json');

// Pastikan id_karya ada di URL
if (!isset($_GET['id_karya']) || empty($_GET['id_karya'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'ID Karya tidak valid.']);
    exit;
}

$id_karya = $_GET['id_karya'];
$id_user = $_SESSION['user_id'] ?? null;

try {
    // Query untuk mengambil komentar utama (parent_id IS NULL)
    $sql_parents = "SELECT 
                        c.id_comment, 
                        c.id_karya, 
                        c.comment_text, 
                        c.created_at,
                        u.nama AS username,
                        (SELECT COUNT(*) FROM likes_comment WHERE id_comment = c.id_comment) AS likes_count,
                        (SELECT COUNT(*) FROM likes_comment WHERE id_comment = c.id_comment AND id_user = ?) AS user_liked
                    FROM comments c
                    JOIN user u ON c.id_user = u.id_user
                    WHERE c.id_karya = ? AND c.parent_id IS NULL
                    ORDER BY c.created_at DESC";

    $stmt_parents = mysqli_prepare($conn, $sql_parents);
    if ($stmt_parents === false) {
        throw new Exception(mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt_parents, "ii", $id_user, $id_karya);
    mysqli_stmt_execute($stmt_parents);
    $result_parents = mysqli_stmt_get_result($stmt_parents);

    $comments = [];
    while ($row = mysqli_fetch_assoc($result_parents)) {
        // Ambil balasan (replies) untuk setiap komentar utama
        $sql_replies = "SELECT
                            c.id_comment,
                            c.id_karya,
                            c.comment_text,
                            c.created_at,
                            u.nama AS username,
                            (SELECT COUNT(*) FROM likes_comment WHERE id_comment = c.id_comment) AS likes_count,
                            (SELECT COUNT(*) FROM likes_comment WHERE id_comment = c.id_comment AND id_user = ?) AS user_liked
                        FROM comments c
                        JOIN user u ON c.id_user = u.id_user
                        WHERE c.parent_id = ?
                        ORDER BY c.created_at ASC";

        $stmt_replies = mysqli_prepare($conn, $sql_replies);
        if ($stmt_replies === false) {
            throw new Exception(mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt_replies, "ii", $id_user, $row['id_comment']);
        mysqli_stmt_execute($stmt_replies);
        $result_replies = mysqli_stmt_get_result($stmt_replies);

        $replies = [];
        while ($reply_row = mysqli_fetch_assoc($result_replies)) {
            $replies[] = $reply_row;
        }
        mysqli_stmt_close($stmt_replies);

        $row['replies'] = $replies;
        $comments[] = $row;
    }
    mysqli_stmt_close($stmt_parents);

    // Jika tidak ada komentar, kirim pesan yang sesuai
    if (empty($comments)) {
        echo json_encode([
            'success' => true, 
            'message' => 'Belum ada komentar.', 
            'comments' => [],
            'display_message' => 'Belum ada pendapat tentang karya ini. Jadilah orang pertama yang memberi pendapat pada karya ini.'
        ]);
    } else {
        echo json_encode(['success' => true, 'comments' => $comments]);
    }

} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    exit;
}
?>