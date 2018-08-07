<?php

namespace Chumper\Zipper\Archives;

use Exception;
use RarArchive;
use RarEntry;

class RarArchiver
{
    private $rar;
    private $files;

    public $numFiles;

    public function open($filePath, $create = false)
    {
        $this->rar = RarArchive::open($filePath);
        $this->files = $this->rar->getEntries();
        $this->numFiles = count($this->files);
    }

    private function getEntry(string $fileName)
    {
        foreach ($this->files as $entry) {
            if ($entry->getName() === $fileName) {
                return $entry;
            }
        }

        throw new Exception('Error: Entry ' . $fileName . ' not found in archive');
    }

    public function isDirectoryIndex($index) {
        $entry = $this->files[$index];

        return $entry->isDirectory();
    }

    public function addFile($pathToFile, $pathInArchive)
    {
        /**
         * @TODO: implement file adding
         */
    }

    public function addEmptyDir($dirName)
    {
        /**
         * @TODO: implement adding empty directories
         */
    }

    public function addFromString($name, $content)
    {
        /**
         * @TODO: implement adding by string
         */
    }

    public function deleteName($pathInArchive)
    {
        $entry = $this->getEntry($pathInArchive);

        /**
         * @TODO: implement file deleting
         */
    }

    public function getFromName($pathInArchive)
    {
        $entry = $this->getEntry($pathInArchive);

        /**
         * @TODO: implement file content reading
         */
    }

    public function getStream($pathInArchive)
    {
        $entry = $this->getEntry($pathInArchive);

        return $entry->getStream();
    }

    public function getNameIndex($index)
    {
        $entry = $this->files[$index];

        return $entry->getName();
    }

    public function statIndex($index)
    {
        $entry = $this->files[$index];

        return [
            'name' => $entry->getName(),
            'index' => $index,
            'crc' => $entry->getCrc(),
            'size' => $entry->getUnpackedSize(),
            'mtime' => $entry->getFileTime(),
            'comp_size' => $entry->getPackedSize(),
            'comp_method' => $entry->getMethod(),
        ];
    }

    public function locateName($fileInArchive)
    {
        try {
            $this->getEntry($fileInArchive);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function setPassword($password)
    {
        /**
         * @TODO: implement rar passwords
         */
    }

    public function getStatusString()
    {
        /**
         * @TODO: implement rar status
         */
    }

    public function close()
    {
        $this->rar->close();
    }






    /*public function __construct($filePath, $password = null, $archive = null)
    {


        if ($this->archive === false) {
            throw new Exception("Error: Failed to open $filePath!");
        }
    }

    public function addFile($pathToFile, $pathInArchive)
    {
        // $this->archive->addFile($pathToFile, $pathInArchive);
    }
    public function addEmptyDir($dirName)
    {
        // $this->archive->addEmptyDir($dirName);
    }
    public function addFromString($name, $content)
    {
        // $this->archive->addFromString($name, $content);
    }
    public function removeFile($pathInArchive)
    {
        // $this->archive->deleteName($pathInArchive);
    }
    public function getFileContent($pathInArchive)
    {
        return $this->archive->getFromName($pathInArchive);
    }
    public function getFileStream($pathInArchive)
    {
        $entry = $this->getEntry($pathInArchive);

        return $entry->getStream($pathInArchive);
    }
    public function each($callback)
    {
        $entries = $this->archive->getEntries();
        $numFiles = \count($entries);

        for ($i = 0; $i < $numFiles; ++$i) {
            $entry = $entries[$i];

            if ($entry->isDirectory()) {
                continue;
            }

            call_user_func_array($callback, [
                'file' => $entry->getName(),
                'stats' => null
            ]);
        }
    }
    public function fileExists($fileInArchive)
    {
        //return $this->archive->locateName($fileInArchive) !== false;
    }
    public function usePassword($password)
    {
        return $this->archive->setPassword($password);
    }
    public function getStatus()
    {
        return $this->archive->getStatusString();
    }
    public function close()
    {
        //
    }*/
}
