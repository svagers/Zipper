<?php

namespace Chumper\Zipper\Repositories;

use Exception;
use Chumper\Zipper\Archives\RarArchiver;

class RarRepository implements RepositoryInterface
{
    private $archive;

    /**
     * Construct with a given path
     *
     * @param $filePath
     * @param bool $create
     * @param $archive
     *
     * @throws \Exception
     *
     * @return RarRepository
     */
    public function __construct($filePath, $create = false, $archive = null)
    {
        $this->archive = $archive ? $archive : new RarArchiver();

        $this->archive->open($filePath, ($create ? true : false));
    }

    /**
     * Add a file to the opened Archive
     *
     * @param $pathToFile
     * @param $pathInArchive
     */
    public function addFile($pathToFile, $pathInArchive)
    {
        $this->archive->addFile($pathToFile, $pathInArchive);
    }

    /**
     * Add an empty directory
     *
     * @param $dirName
     */
    public function addEmptyDir($dirName)
    {
        $this->archive->addEmptyDir($dirName);
    }

    /**
     * Add a file to the opened Archive using its contents
     *
     * @param $name
     * @param $content
     */
    public function addFromString($name, $content)
    {
        $this->archive->addFromString($name, $content);
    }

    /**
     * Remove a file permanently from the Archive
     *
     * @param $pathInArchive
     */
    public function removeFile($pathInArchive)
    {
        $this->archive->deleteName($pathInArchive);
    }

    /**
     * Get the content of a file
     *
     * @param $pathInArchive
     *
     * @return string
     */
    public function getFileContent($pathInArchive)
    {
        return $this->archive->getFromName($pathInArchive);
    }

    /**
     * Get the stream of a file
     *
     * @param $pathInArchive
     *
     * @return mixed
     */
    public function getFileStream($pathInArchive)
    {
        return $this->archive->getStream($pathInArchive);
    }

    /**
     * Will loop over every item in the archive and will execute the callback on them
     * Will provide the filename for every item
     *
     * @param $callback
     */
    public function each($callback)
    {
        for ($i = 0; $i < $this->archive->numFiles; ++$i) {
            //skip if folder
            if ($this->archive->isDirectoryIndex($i)) {
                continue;
            }

            call_user_func_array($callback, [
                'file' => $this->archive->getNameIndex($i),
                'stats' => $this->archive->statIndex($i)
            ]);
        }
    }

    /**
     * Checks whether the file is in the archive
     *
     * @param $fileInArchive
     *
     * @return bool
     */
    public function fileExists($fileInArchive)
    {
        return $this->archive->locateName($fileInArchive) !== false;
    }

    /**
     * Sets the password to be used for decompressing
     * function named usePassword for clarity
     *
     * @param $password
     *
     * @return bool
     */
    public function usePassword($password)
    {
        return $this->archive->setPassword($password);
    }

    /**
     * Returns the status of the archive as a string
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->archive->getStatusString();
    }

    /**
     * Closes the archive and saves it
     */
    public function close()
    {
        @$this->archive->close();
    }

    private function getErrorMessage($resultCode)
    {
        switch ($resultCode) {
            case RarArchiver::ER_EXISTS:
                return 'RarArchiver::ER_EXISTS - File already exists.';
            case RarArchiver::ER_INCONS:
                return 'RarArchiver::ER_INCONS - Zip archive inconsistent.';
            case RarArchiver::ER_MEMORY:
                return 'RarArchiver::ER_MEMORY - Malloc failure.';
            case RarArchiver::ER_NOENT:
                return 'RarArchiver::ER_NOENT - No such file.';
            case RarArchiver::ER_NOZIP:
                return 'RarArchiver::ER_NOZIP - Not a zip archive.';
            case RarArchiver::ER_OPEN:
                return 'RarArchiver::ER_OPEN - Can\'t open file.';
            case RarArchiver::ER_READ:
                return 'RarArchiver::ER_READ - Read error.';
            case RarArchiver::ER_SEEK:
                return 'RarArchiver::ER_SEEK - Seek error.';
            default:
                return "An unknown error [$resultCode] has occurred.";
        }
    }
}
