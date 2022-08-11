<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MongoDB\Driver\Session;


class DecryptController extends Controller
{
    // 32 byte key used to encrypt and decrypt
    private $key = 'w5wd0rdHR9yLlM6wt2vteuiniQBqE70n';


    private $FILE_ENCRYPTION_BLOCKS = 10000;

    public function decryptFile(Request $request){
        $roles=array(
            'encrypt_file' => 'required',
            'saving_path' => 'required|string',
            'file_name' => 'required|string',

        );
        $request->validate($roles);
        $encrypted_file=$request->file_name.'.txt';
       // dd($encrypted_file);
        $fileName = time().'.'.$request->encrypt_file->extension();
        $request->encrypt_file->move(public_path('/'.$request->saving_path.'/'), $fileName);
        $this->doDecryption($request->saving_path.'/'.$fileName,$request->saving_path.'/'.$encrypted_file);
        $file= public_path().'/'.$request->saving_path.'/'. $encrypted_file;
        \Session::flash('flash_file_decryption', $file);
        return redirect()->route('home');

    }



    public function doDecryption($pathToSource,$pathToSave){

        if (($fpOut = fopen($pathToSave, 'w')) === false) {
            throw new Exception('Cannot open file for writing');
        }
        if (($fpIn = fopen($pathToSource, 'r', false, stream_context_create([]))) === false) {
            throw new Exception('Cannot open file for reading');
        }

        // Get the initialzation vector from the beginning of the file
        $iv = fread($fpIn, 16);

        $numberOfChunks = ceil((filesize($pathToSource) - 16) / (16 * ($this->FILE_ENCRYPTION_BLOCKS + 1)));

        $i = 0;
        while (! feof($fpIn)) {
            // We have to read one block more for decrypting than for encrypting because of the initialization vector
            $ciphertext = fread($fpIn, 16 * ($this->FILE_ENCRYPTION_BLOCKS + 1));
            $plaintext = openssl_decrypt($ciphertext, 'AES-256-CBC', $this->key, OPENSSL_RAW_DATA, $iv);

            // Check if the size read from the stream is different than the requested chunk size
            // In this scenario, request the chunk again, unless this is the last chunk
            if (strlen($ciphertext) !== 16 * ($this->FILE_ENCRYPTION_BLOCKS + 1)
                && $i + 1 < $numberOfChunks
            ) {
                fseek($fpIn, 16 + 16 * ($this->FILE_ENCRYPTION_BLOCKS + 1) * $i);
                continue;
            }

            if ($plaintext === false) {
                throw new Exception('Decryption failed');
            }

            // Get the the first 16 bytes of the ciphertext as the next initialization vector
            $iv = substr($ciphertext, 0, 16);
            fwrite($fpOut, $plaintext);

            $i++;
        }

        fclose($fpIn);
        fclose($fpOut);

        return true;
    }
}
