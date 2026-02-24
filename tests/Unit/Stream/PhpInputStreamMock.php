<?php

namespace Simovative\Zeus\Tests\Unit\Stream;

class PhpInputStreamMock
{
    public $context;
    public static string $content = '';
    private int $position = 0;

    public function stream_open($path, $mode, $options, &$opened_path): bool
    {
        return true;
    }

    public function stream_read(int $count): string
    {
        $ret = substr(self::$content, $this->position, $count);
        $this->position += strlen($ret);
        return $ret;
    }

    public function stream_eof(): bool
    {
        return $this->position >= strlen(self::$content);
    }

    public function stream_stat(): array
    {
        return [];
    }

    public function stream_set_option(): bool
    {
        return true;
    }
}