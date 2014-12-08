<?php
class RestJSONOutput extends CWidget
{
    const STATUS_200 = 'HTTP/1.1 200 OK';
    const STATUS_201 = 'HTTP/1.1 201 Created';
    const STATUS_204 = 'HTTP/1.1 204 No Content';
    const STATUS_400 = 'HTTP/1.1 400 Bad Request';
    const STATUS_401 = 'HTTP/1.1 401 Unauthorized';
    const STATUS_403 = 'HTTP/1.1 403 Forbidden';
    const STATUS_404 = 'HTTP/1.1 404 Not Found';
    const STATUS_406 = 'HTTP/1.1 406 Not Acceptable';
    const STATUS_422 = 'HTTP/1.1 422 Unprocessable Entity';
    const STATUS_500 = 'HTTP/1.1 500 Internal Server Error';

    public $type;
    public $success;
    public $message;
    public $totalCount;
    public $data;
    public $errorCode;
    public $createdUrl;

    /**
     * run
     *
     * called when widget is to be run
     * will trigger different output based on $type
     */
    public function run()
    {
        switch($this->type) {
            case 'error':
                $this->outputError();
                break;
            case 'data':
                $this->outputData();
                break;
            case 'empty':
                $this->outputEmpty();
                break;
            case 'created':
                $this->outputCreated();
                break;
            case 'json':
                $this->outputJson();
                break;
            default:
                $this->outputRaw();
        }
    }

    /**
     * outputRaw
     *
     * when type is 'raw' this method will simply output $data as JSON
     */
    public function outputRaw()
    {
        header(self::STATUS_200);
        echo CJSON::encode($this->data);
        Yii::app()->end();
    }

    /**
     * outputRaw
     *
     * when type is 'json' this method will simply output $data if it's already json
     */
    public function outputJson()
    {
        header(self::STATUS_200);
        echo $this->data;
        Yii::app()->end();
    }

    /**
     * outputError
     *
     * when the output $type is 'error' $data JSON output will be formatted
     * with a specific error template
     */
    public function outputError()
    {
        $this->setErrorHeader();
        echo CJSON::encode([
            'success'	=> false,
            'message'	=> $this->message,
        ]);
        Yii::app()->end();
    }

    /**
     * outputRest
     *
     * when $type is 'REST' $data JSON output will be formatted
     * with a specific rest template
     */
    public function outputData()
    {
        header(self::STATUS_200);
        echo CJSON::encode([
            'success'	=> true,
            'totalCount' => $this->totalCount,
            'model' => $this->data,
        ]);
        Yii::app()->end();
    }

    /**
     * outputRest
     *
     * when $type is 'REST' $data JSON output will be formatted
     * with a specific rest template
     */
    public function outputEmpty()
    {
        header(self::STATUS_204);
        echo CJSON::encode([
            'success'	=> $this->success,
        ]);
        Yii::app()->end();
    }

    /**
     * outputRest
     *
     * when $type is 'REST' $data JSON output will be formatted
     * with a specific rest template
     */
    public function outputCreated()
    {
        header(self::STATUS_201);
        header('Location: ' . $this->createdUrl);
        echo CJSON::encode([
            'success'	=> $this->success,
        ]);
        Yii::app()->end();
    }

    private function setErrorHeader()
    {
        switch($this->errorCode) {
            case 400:
                header(self::STATUS_400);
                break;
            case 401:
                header(self::STATUS_401);
                break;
            case 403:
                header(self::STATUS_403);
                break;
            case 404:
                header(self::STATUS_404);
                break;
            case 422:
                header(self::STATUS_422);
                break;
            default:
                header(self::STATUS_500);
                break;
        }
    }
}