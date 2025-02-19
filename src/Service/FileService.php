<?php
namespace App\Service;

use Aws\S3\S3Client;
use PHPUnit\Util\Exception;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileService {

    private $slugger;
    private $s3;

    /**
     * @param $slugger
     */
    public function __construct(SluggerInterface $slugger) {
        $s3Params = [ 'region' => $_ENV['AWS_REGION'], 'version' => 'latest', 'credentials' => ['key' => $_ENV['AWS_KEY'], 'secret' => $_ENV['AWS_SECRET']],];
        $this->slugger = $slugger;
        $this->s3 = new S3Client($s3Params);
    }

    public function uploadFile($file) {
        if ($file) {
            $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFileName = $this->slugger->slug($originalFileName);
            $newFileName = $safeFileName . '-' . uniqid() . '.' . $file->guessExtension();
            try {
                $file_contents = file_get_contents($file->getRealPath());
                $this->s3->putObject([
                    'Bucket' => $_ENV['AWS_BUCKET_NAME'],
                    'Key' => $newFileName,
                    'Body' => $file_contents,
                ]);
                return $newFileName;
            } catch (Exception $e) {
                throw new Exception('Algo salió mal al cargar el archivo a S3: ' . $e->getMessage());
            }
        }
        return null;
    }

    public function deleteFile(string $fileName) {
        try {
            $this->s3->deleteObject([
                'Bucket' => $_ENV['AWS_BUCKET_NAME'],
                'Key' => $fileName
            ]);
        } catch (Exception $e) {
            throw new Exception('Algo salió mal: ' . $e->getMessage());
        }
    }
}