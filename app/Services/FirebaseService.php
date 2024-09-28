<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;

class FirebaseService
{
    protected $database;
    protected $storage;

    public function __construct()
    {
        $firebase = (new Factory)->withServiceAccount(config('firebase.credentials'))
            ->withDatabaseUri('https://bossupgrade-101-default-rtdb.firebaseio.com');
        $this->database = $firebase->createDatabase();

        $this->storage = $firebase->createStorage();
    }

    public function getDatabase(): Database
    {
        return $this->database;
    }
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * 
     *
     * @param string $path
     * @param int $perPage
     * @param string|null $startAfter
     * @return array
     */
    public function getPaginatedData(string $path, int $perPage, string $startAfter = null): array
    {
        $query = $this->database->getReference($path)
            ->orderByKey()
            ->limitToFirst($perPage + 1);

        if ($startAfter) {
            $query = $query->startAfter($startAfter);
        }

        $snapshot = $query->getSnapshot();
        $data = $snapshot->getValue();

        $nextPageToken = null;
        if ($data && count($data) > $perPage) {

            $data = array_slice($data, 0, $perPage, true);
            $nextPageToken = array_key_last($data);
        }

        return [
            'data' => $data,
            'next_page_token' => $nextPageToken,
        ];
    }
    public function usernameExists($username)
    {
        $staffRef = $this->database->getReference('staff')
            ->orderByChild('username')
            ->equalTo($username)
            ->getSnapshot();

        return !empty($staffRef);
    }
    public function saveData($node, $data)
    {
        return $this->database->getReference($node)->push($data);
    }
}
