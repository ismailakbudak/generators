<?php
/**
 * Created by PhpStorm.
 * User: Kemal Kanok
 * Date: 07/06/15
 * Time: 02:00
 */

namespace Kanok\Generators\Command;

use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use Kanok\Generators\Libs\FileHandler;

class GenerateRequestCommand extends Command implements SelfHandling {
    /**
     * @var FileHandler
     */
    private $general;
    /**
     * @var
     */
    private $keyword;
    /**
     * @var
     */
    private $conf;

    /**
     *
     * General Constructor Method
     * @param $keyword
     * @param $conf
     */
    function __construct( $keyword , $conf)
    {
        $this->general  = app('Kanok\Generators\Libs\FileHandler');
        $this->name     = app('Kanok\Generators\Libs\NameHelper');
        $this->keyword  = $keyword;
        $this->conf     = $conf;
    }

    /**
     * Fire Method
     */
    function fire()
    {
        return $this->generateModel($this->keyword);
    }


    function generateModel($keyword = "Default")
    {
        //get the model stub
        $modelStubPath  = 'Stubs/Request/Default.stub';
        $modelStub      = $this->general->getFile($modelStubPath);

        //bind the model
        $fillables      = "";
        foreach ($this->conf->fields as $key => $value) {
            $fillables .= "'" . $key . "' => 'Required',
            ";
        }
        $fillables = substr($fillables, 0, -1);

        $modelStub = $this->general->quickStubDataBinding($modelStub, [
            'request'   => $this->name->getRequestName($this->conf->resource),
            'fillable'  => $fillables
        ]);
        // write the file
        $modelPath = 'Http/Requests/'. $this->name->getRequestName($this->conf->resource) .'.php';
        $this->general->writeAppFile($modelPath, $modelStub);
        //give a nice good news screen
        //give a nice good news screen
        return true;
    }
}