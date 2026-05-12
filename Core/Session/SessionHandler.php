<?php

namespace WiseTrap\Core\Session;

use SessionHandlerInterface;

class SessionHandler implements SessionHandlerInterface
{
    public function __construct(public string $save_path, public $prefix)
    {
    }
    public function close(): bool
    {
        return true;
    }
    public function destroy(string $id): bool
    {
        $file = $this->save_path . DS . $this->prefix . '_' . $id;

        if (file_exists($file)) {
            unlink($file);
        }

        return true;
    }
    public function gc(int $max_lifetime): int|false
    {
        foreach (glob($this->save_path . DS . $this->prefix . '_*') as $file) {
            if (filemtime($file) + $max_lifetime < time() && file_exists($file)) {
                unlink($file);
            }
        }
        return true;
    }
    public function open(string $path, string $name): bool
    {
        if (!is_dir($this->save_path)) {
            mkdir($this->save_path, 0755);
        }
        return true;
    }
    public function read(string $id): string|false
    {
        if (preg_match('/^(http|https|ftp|php):\/\//i', $id)) {
            return false;
        }

        $safeId = basename($id);
        $file = realpath($this->save_path) . DS . $this->prefix . '_' . $safeId;

        if (!str_starts_with(realpath(dirname($file)), realpath($this->save_path))) {
            return false;
        }

        return file_exists($file) ? file_get_contents($file) : '';
    }
    public function write(string $id, string $data): bool
    {
        if (preg_match('/^(http|https|ftp|php):\/\//i', $id)) {
            return false;
        }
        $safeId = basename($id);
        $file = realpath($this->save_path) . DS . $this->prefix . '_' . $safeId;
        if (!str_starts_with(realpath(dirname($file)), realpath($this->save_path))) {
            return false;
        }
        return file_put_contents($file, $data) !== false;
    }
}