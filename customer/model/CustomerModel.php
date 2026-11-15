<?php
require_once __DIR__ . '/../config/koneksi.php';

class CustomerModel
{
    public static function query(
        string $sql
    ) {
        global $conn;
        return mysqli_query($conn, $sql);
    }

    public static function fetchOne(string $sql): ?array
    {
        $result = self::query($sql);
        return $result ? mysqli_fetch_assoc($result) : null;
    }

    public static function fetchAll(string $sql): array
    {
        $result = self::query($sql);
        $rows = [];

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
        }

        return $rows;
    }
}
