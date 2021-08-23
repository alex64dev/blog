<?php


namespace App;


use \Exception;

class Upload
{
    const DIR =  "/public/uploads/";
    const SIZE = 500000;
    const TYPE = ["jpg", "png", "jpeg", "gif"];

    private array $errors = [];

    private bool $uploadOk = true;

    public function upload($data, $name){
        if(!empty($data) && ($data[$name]['error'] === 0 && $data[$name]['size'] > 0)) {
            $target_file = $this->getTargetFile($data, $name);

            $this->checkImage($data, $name);

            // Check if $uploadOk is set to false by an error
            if ($this->uploadOk) {
                if (!move_uploaded_file($data[$name]["tmp_name"], $target_file)) {
                    throw new Exception("Sorry, there was an error uploading your file.");
                }
            }
        }else{
            $this->uploadOk = false;
        }

        return $this->uploadOk;
    }

    private function existImage( string $target_file)
    {
        // Check if file already exists
        if (file_exists($target_file)) {
            $this->uploadOk = false;
            $this->errors[] = "Cette image existe déjà";
        }
    }

    private function sizeImage($data, $name)
    {
        // Check file size
        if ($data[$name]["size"] > self::SIZE) {
            $this->uploadOk = false;
            $this->errors[] = "La taille de l'image est trop grande (max: " . self::SIZE . ")";
        }
    }

    private function typeImage($imageFileType)
    {
        // Allow certain file formats
        if(!in_array($imageFileType, self::TYPE)) {
            $this->uploadOk = false;
            $this->errors[] = "L'image n'est pas au bon format (" . implode(" ,",self::TYPE) . ")";
        }
    }

    public function checkImage($data, $name)
    {
        if(!empty($data) ) {

            $target = $this->getTargetFile($data, $name);
            $this->existImage($target);

            $this->sizeImage($data, $name);

            $imageFileType = $this->getImageType($target);
            $this->typeImage($imageFileType);

            return [
                "errors"   => $this->errors,
                "isUpload" => $this->uploadOk
            ];
        }
    }

    private function getTargetFile($data, $name)
    {
        return dirname(__DIR__) . self::DIR . basename($data[$name]["name"]);
    }

    private function getImageType($target_file)
    {
        return strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    }

}