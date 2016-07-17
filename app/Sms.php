<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{
    protected $fillable = [
        'text', 'recipient_id', 'phone', 'sender_id', 'status', 'error_code'
    ];

    protected $table = 'sms';

    public function sender()
    {
        return $this->belongsTo('App\User');
    }

    public function recipient()
    {
        return $this->belongsTo('App\User');
    }

    public function task()
    {
        return $this->belongsTo('App\Task');
    }

    public function getErrorText() {

        $code = $this->error_code;

        switch ($code) {
            case '1':
                return 'Ошибка в параметрах.';
                break;
            case '2':
                return 'Неверный логин или пароль.';
                break;
            case '3':
                return 'Недостаточно средств на счете Клиента.';
                break;
            case '4':
                return 'IP-адрес временно заблокирован из-за частых ошибок в запросах. Подробнее';
                break;
            case '5':
                return 'Неверный формат даты.';
                break;
            case '6':
                return 'Сообщение запрещено (по тексту или по имени отправителя).';
                break;
            case '7':
                return 'Неверный формат номера телефона.';
                break;
            case '8':
                return 'Сообщение на указанный номер не может быть доставлено.';
                break;
            case '9':
                return 'Отправка более одного одинакового запроса на передачу SMS-сообщения либо более пяти одинаковых запросов на получение стоимости сообщения в течение минуты.';
                break;
            default: return '';
        }
    }
}
