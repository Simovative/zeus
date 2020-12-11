<?php

namespace Simovative\Zeus\Session\Storage\Handler;

use Simovative\Zeus\Exception\FilesystemException;
use Simovative\Zeus\Filesystem\Directory;
use Simovative\Zeus\Filesystem\File;

/**
 * Required PHP >= 5.4
 *
 * @author mnoerenberg
 * @deprecated Please do not use this class any more. Garbage collection does not work any more.
 */
class SessionFileHandler extends SessionHandler
{
    
    /**
     * @var Directory
     */
    private $sessionDirectory;
    
    /**
     * @var string|null
     */
    private $sessionChecksum;
    
    /**
     * @author mnoerenberg
     * @param Directory|null $sessionDirectory - default: null
     * @throws FilesystemException
     */
    public function __construct(Directory $sessionDirectory = null)
    {
        $this->sessionDirectory = $sessionDirectory;
        if (! $this->sessionDirectory instanceof Directory) {
            $this->sessionDirectory = new Directory(ini_get('session.save_path'));
        }
        
        if (! $this->sessionDirectory->exists()) {
            throw new \RuntimeException('Session directory is not writeable.');
        }
        
        if (session_status() == PHP_SESSION_ACTIVE) {
            return;
        }
        ini_set('session.save_handler', 'files');
        ini_set('session.save_path', realpath($this->sessionDirectory->getPath()));
        $this->sessionChecksum = null;
    }
    
    /**
     * Returns the session file.
     *
     * @author mnoerenberg
     * @param string $id
     * @return File
     */
    private function getFile($id)
    {
        return new File(realpath($this->sessionDirectory->getPath()) . '/sess_' . $id, true);
    }
    
    /**
     * @author Benedikt Schaller
     * @inheritdoc
     */
    public function read($session_id)
    {
        $sessionData = $this->getFile($session_id)->read();
        $this->sessionChecksum = hash("crc32b", $sessionData);
        return $sessionData;
    }
    
    /**
     * @author Benedikt Schaller
     * @inheritdoc
     */
    public function write($session_id, $session_data)
    {
        $newSessionChecksum = hash("crc32b", $session_data);
        if ($this->sessionChecksum == $newSessionChecksum) {
            $this->getFile($session_id)->touch();
            return true;
        }
        return $this->getFile($session_id)->write($session_data);
    }
    
    /**
     * @author Benedikt Schaller
     * @inheritdoc
     */
    public function close()
    {
        return true;
    }
    
    /**
     * @author Benedikt Schaller
     * @inheritdoc
     */
    public function create_sid()
    {
        return session_create_id();
    }
    
    /**
     * @author Benedikt Schaller
     * @inheritdoc
     */
    public function destroy($session_id)
    {
        $file = $this->getFile($session_id);
        if (! $file->exists()) {
            return false;
        }
        
        try {
            return $file->delete();
        } catch (FilesystemException $exception) {
            return false;
        }
    }
    
    /**
     * @author Benedikt Schaller
     * @inheritdoc
     */
    public function gc($maxlifetime)
    {
        return true;
    }
    
    /**
     * @author Benedikt Schaller
     * @inheritdoc
     */
    public function open($save_path, $session_name)
    {
        return true;
    }
}
