<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "library_db";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

function fetchMembers($conn)
{
    $sql = "SELECT Member_id, Name, `phone No.`, email, Address FROM member ORDER BY Member_id ASC";
    $result = mysqli_query($conn, $sql);
    $rows = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

function fetchBooks($conn)
{
    $sql = "SELECT book_id, title, author, publisher, year, price FROM book ORDER BY book_id ASC";
    $result = mysqli_query($conn, $sql);
    $rows = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

function fetchLibrarians($conn)
{
    $sql = "SELECT Librarian_ID, Name, Phone_No, Email, Salary FROM librarian ORDER BY Librarian_ID ASC";
    $result = mysqli_query($conn, $sql);
    $rows = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

function fetchTransactions($conn)
{
    $sql = "SELECT
            bi.Issue_ID,
            bi.Member_ID,
            bi.Book_ID,
            bi.Issue_Date,
            bi.Return_Date,
            bi.Librarian_ID,
            br.Return_Date AS Return_Date_Actual,
            IFNULL(br.Fine_Amount, 0) AS Fine_Amount,
            IF(br.Return_ID IS NOT NULL, 'Returned', 'Issued') AS status
        FROM book_issue bi
        LEFT JOIN book_return br ON br.Issue_ID = bi.Issue_ID
        ORDER BY bi.Issue_ID DESC";

    $result = mysqli_query($conn, $sql);
    $rows = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

function fetchDashboardState($conn)
{
    return [
        'members' => fetchMembers($conn),
        'books' => fetchBooks($conn),
        'librarians' => fetchLibrarians($conn),
        'transactions' => fetchTransactions($conn),
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');

    $action = $_POST['action'];

    if ($action === 'add_member') {
        $name = mysqli_real_escape_string($conn, trim($_POST['Name'] ?? ''));
        $phone = mysqli_real_escape_string($conn, trim($_POST['phone'] ?? ''));
        $email = mysqli_real_escape_string($conn, trim($_POST['email'] ?? ''));
        $address = mysqli_real_escape_string($conn, trim($_POST['Address'] ?? ''));

        $sql = "INSERT INTO member (Name, `phone No.`, email, Address)
                VALUES ('$name', '$phone', '$email', '$address')";
    } elseif ($action === 'add_book') {
        $title = mysqli_real_escape_string($conn, trim($_POST['title'] ?? ''));
        $author = mysqli_real_escape_string($conn, trim($_POST['author'] ?? ''));
        $publisher = mysqli_real_escape_string($conn, trim($_POST['publisher'] ?? ''));
        $year = (int) ($_POST['year'] ?? 0);
        $price = (int) ($_POST['price'] ?? 0);

        $sql = "INSERT INTO book (title, author, publisher, year, price)
                VALUES ('$title', '$author', '$publisher', $year, $price)";
    } elseif ($action === 'issue_book') {
        $memberId = (int) ($_POST['Member_ID'] ?? 0);
        $bookId = (int) ($_POST['Book_ID'] ?? 0);
        $issueDate = mysqli_real_escape_string($conn, $_POST['Issue_Date'] ?? '');
        $returnDate = mysqli_real_escape_string($conn, $_POST['Return_Date'] ?? '');
        $librarianId = (int) ($_POST['Librarian_ID'] ?? 0);

        $sql = "INSERT INTO book_issue (Member_ID, Book_ID, Issue_Date, Return_Date, Librarian_ID)
                VALUES ($memberId, $bookId, '$issueDate', '$returnDate', $librarianId)";
    } elseif ($action === 'return_book') {
        $issueId = (int) ($_POST['Issue_ID'] ?? 0);
        $returnDate = mysqli_real_escape_string($conn, $_POST['Return_Date_Actual'] ?? '');
        $fine = (float) ($_POST['Fine_Amount'] ?? 0);

        $checkSql = "SELECT Return_ID FROM book_return WHERE Issue_ID = $issueId LIMIT 1";
        $checkResult = mysqli_query($conn, $checkSql);

        if (mysqli_num_rows($checkResult) > 0) {
            echo json_encode(['success' => false, 'message' => 'Error']);
            exit;
        }

        $sql = "INSERT INTO book_return (Issue_ID, Return_Date, Fine_Amount)
                VALUES ($issueId, '$returnDate', $fine)";
    } else {
        echo json_encode(['success' => false, 'message' => 'Error']);
        exit;
    }

    if (mysqli_query($conn, $sql)) {
        echo json_encode([
            'success' => true,
            'message' => 'Add successfully',
            'data' => fetchDashboardState($conn),
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error',
        ]);
    }

    exit;
}
