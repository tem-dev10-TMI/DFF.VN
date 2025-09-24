<?php

if (!function_exists('get_cache')) {
    /**
     * Lấy dữ liệu từ file cache nếu nó hợp lệ.
     * @param string $key Tên định danh cho cache.
     * @param int $ttl Thời gian sống của cache (tính bằng giây).
     * @return mixed Dữ liệu từ cache hoặc false nếu cache không tồn tại/hết hạn.
     */
    function get_cache($key, $ttl = 3600) {
        $cache_dir = __DIR__ . '/../cache';
        if (!is_dir($cache_dir)) {
            mkdir($cache_dir, 0755, true);
        }
        $cache_file = $cache_dir . '/' . md5($key) . '.cache';

        if (file_exists($cache_file)) {
            // Kiểm tra xem cache có hết hạn hay không
            if (time() - filemtime($cache_file) < $ttl) {
                $data = file_get_contents($cache_file);
                return unserialize($data);
            }
            // Xóa file cache nếu đã hết hạn
            unlink($cache_file);
        }
        return false;
    }
}

if (!function_exists('set_cache')) {
    /**
     * Lưu dữ liệu vào file cache.
     * @param string $key Tên định danh cho cache.
     * @param mixed $data Dữ liệu cần lưu.
     */
    function set_cache($key, $data) {
        $cache_dir = __DIR__ . '/../cache';
        if (!is_dir($cache_dir)) {
            mkdir($cache_dir, 0755, true);
        }
        $cache_file = $cache_dir . '/' . md5($key) . '.cache';
        $data_to_save = serialize($data);
        file_put_contents($cache_file, $data_to_save, LOCK_EX);
    }
}

if (!function_exists('asset_url')) {
    /**
     * Tạo URL cho file tĩnh với phiên bản tự động (dựa vào thời gian sửa file) để phá cache.
     * @param string $path Đường dẫn tương đối đến file từ thư mục gốc của dự án.
     * @return string URL đầy đủ đến file với tham số version.
     */
    function asset_url($path) {
        $projectRoot = $_SERVER['DOCUMENT_ROOT'] . '/DFF.VN';
        $filePath = $projectRoot . '/' . ltrim($path, '/');

        $version = '1.0'; // Fallback version
        if (file_exists($filePath)) {
            $version = filemtime($filePath);
        }

        // Giả định BASE_URL đã được định nghĩa và có dạng http://localhost/DFF.VN
        return BASE_URL . '/' . ltrim($path, '/') . '?v=' . $version;
    }
}
