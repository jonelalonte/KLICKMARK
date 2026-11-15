<?php

class firebaseRDB {

    private $url;

    public function __construct($url) {
        $this->url = rtrim($url, "/") . "/";
    }

    public function retrieve($path) {
        $path = trim($path, "/");
        $url = $this->url . $path . ".json";

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            curl_close($curl);
            return false;
        }

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode !== 200) {
            return false;
        }

        return json_decode($response, true);
    }

    // IDINAGDAG NATIN ITO PARA MAKAPAG-INSERT / POST NG DATA (TULAD NG REGISTER)
    public function insert($path, $data) {
        $path = trim($path, "/");
        $url = $this->url . $path . ".json";

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_TIMEOUT => 15
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            curl_close($curl);
            return false;
        }

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode !== 200 && $httpCode !== 201) {
            return false;
        }

        return $response;
    }
}