<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MongoDB\Driver\Session;


class EncryptController extends Controller
{
    // 32 byte key used to encrypt and decrypt
    private $key = 'w5wd0rdHR9yLlM6wt2vteuiniQBqE70n';


    private $FILE_ENCRYPTION_BLOCKS = 10000;

    public function encryptFile(Request $request){
        $roles=array(
            'encrypt_file' => 'required',
            'saving_path' => 'required|string',
            'file_name' => 'required|string',

        );
        $request->validate($roles);
        $encrypted_file=$request->file_name.'.'.$request->encrypt_file->extension().'.encrypted';
        $fileName = time().'.'.$request->encrypt_file->extension();
        $request->encrypt_file->move(public_path('/'.$request->saving_path.'/'), $fileName);
        $this->doEncryption($request->saving_path.'/'.$fileName,$request->saving_path.'/'.$encrypted_file);
        $file= public_path().'/'.$request->saving_path.'/'. $encrypted_file;
        \Session::flash('flash_file_encryption', $file);
        return redirect()->route('home');



    }


    public function doEncryption($pathToSource,$pathToSave){
        if (($fpOut = fopen($pathToSave, 'w')) === false) {
            throw new Exception('Cannot open file for writing');
        }
        if (($fpIn = fopen($pathToSource, 'r', false, stream_context_create([]))) === false) {
            throw new Exception('Cannot open file for reading');
        }

        // Put the initialzation vector to the beginning of the file
        $iv = openssl_random_pseudo_bytes(16);
        fwrite($fpOut, $iv);

        $numberOfChunks = ceil(filesize($pathToSource) / (16 * $this->FILE_ENCRYPTION_BLOCKS));

        $i = 0;
        while (! feof($fpIn)) {
            $plaintext = fread($fpIn, 16 * $this->FILE_ENCRYPTION_BLOCKS);
                $ciphertext = openssl_encrypt($plaintext, 'AES-256-CBC', $this->key, OPENSSL_RAW_DATA, $iv);

            // Check if the size read from the stream is different than the requested chunk size
            // In this scenario, request the chunk again, unless this is the last chunk
            if (strlen($plaintext) !== 16 * $this->FILE_ENCRYPTION_BLOCKS
                && $i + 1 < $numberOfChunks
            ) {
                fseek($fpIn, 16 * $this->FILE_ENCRYPTION_BLOCKS * $i);
                continue;
            }

            // Use the first 16 bytes of the ciphertext as the next initialization vector
            $iv = substr($ciphertext, 0, 16);
            fwrite($fpOut, $ciphertext);

            $i++;
        }

        fclose($fpIn);
        fclose($fpOut);

        return true;

    }

}
