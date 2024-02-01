<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Log\Logger;

use Monolog\Handler\RotatingFileHandler as MonologRotatingFileHandler;

class RotatingFileHandler extends MonologRotatingFileHandler
{
    public function getTimedFilename(): string
    {
        $fileInfo = pathinfo($this->filename);
        $timedFilename = str_replace(
            ['{filename}', '{date}'],
            [$fileInfo['filename'], date($this->dateFormat)],
            ($fileInfo['dirname'] ?? '') . '/' . $this->filenameFormat
        );

        if (isset($fileInfo['extension'])) {
            $timedFilename .= '.' . $fileInfo['extension'];
        }

        return $timedFilename;
    }
}
