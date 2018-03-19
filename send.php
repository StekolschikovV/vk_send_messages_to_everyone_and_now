<?php


class Sender {

    public $actoin_type = '';
    public $group_id = '';
    public $group_token = '';
    public $message_text = '';
    public $user_list = [];
    public $send_log = '';
    public $i = '';
    public $ii = '';
    public $Y = '';
    public $m = '';
    public $d = '';
    public $h = '';
    public $url = 'http://bot.worldwideshop.ru/vk_send_messages_to_everyone_and_now';


    public function show_dev_info(){
        echo 'actoin_type: ' . $this->actoin_type . '<br>';
        echo 'group_id: ' . $this->group_id . '<br>';
        echo 'group_token: ' . $this->group_token . '<br>';
        echo 'user_list: ';
        print_r($this->user_list);
        echo '<br>' . 'message_text: ' . $this->message_text . '<br>';
        echo  'send_log: ' . '<br>' . $this->send_log . '<br>';
    }

    function __construct() {
        $this->actoin_type = $_GET['action'];
        $this->group_id = $_GET['id'];
        $this->i = $_GET['i'];
        $this->group_token = $_GET['token'];
        $this->message_text = $_GET['text'];

        $this->Y = $_GET['Y'];
        $this->m = $_GET['m'];
        $this->d = $_GET['d'];
        $this->h = $_GET['h'];
        $this->ii = $_GET['ii'];

        $this->get_user_from_id($this->group_id);

        if($this->actoin_type == 'send')
            $this->send();
        elseif($this->actoin_type == 'save')
            $this->save();
        elseif($this->actoin_type == 'remove_save')
            $this->remove_save();
        elseif($this->actoin_type == 'add_task')
            $this->add_task();
        elseif($this->actoin_type == 'remove_task')
            $this->remove_task();
        elseif($this->actoin_type == 'cron')
            $this->cron();
    }

    public function cron(){
        $date = date("Y/m/d/h/i");
        echo $date;
        echo '<br>';
        echo '<br>';
        echo '<br>';
        echo '<br>';
        print_r($this->group_id);

        $file = './save_task.json';
        $json = file_get_contents($file);
        $arr = array_values(json_decode($json, true));

        for($i = 0; $i < count($arr); $i++){
            echo $arr[$i]['date'] . '<br>';
            if($arr[$i]['date'] == $date){

                $this->get_user_from_id($arr[$i]['id']);



                for($j = 0; $j < count($this->user_list); $j++){
                    $url = 'https://api.vk.com/method/messages.send';
                    $params = array(
                        'user_id' => $this->user_list[$j],
                        'message' => $arr[$i]['text'],
                        'access_token' => $arr[$i]['token'],
                        'v' => '5.37',
                    );
                    $result = file_get_contents($url, false, stream_context_create(array(
                        'http' => array(
                            'method'  => 'POST',
                            'header'  => 'Content-type: application/x-www-form-urlencoded',
                            'content' => http_build_query($params)
                        )
                    )));
                    echo '<pre>';
                    echo $result;
                    echo '</pre>';
                }

            }
        }

    }

    public function remove_task(){
        $file = './save_task.json';
        $json = file_get_contents($file);
        $arr = array_values(json_decode($json, true));
        unset($arr[$this->i]);
        $current = json_encode($arr);
        file_put_contents($file, $current);
        header("Location: $this->url/?task=completed");
        exit();
    }

    public function add_task(){

        $file = './save_task.json';

        $new['date'] = $this->Y . '/' . $this->m . '/' . $this->d . '/' . $this->h . '/' . $this->ii;
        $new['id'] = $this->group_id;
        $new['token'] = $this->group_token;
        $new['text'] = $this->message_text;

        $old = file_get_contents($file);
        $old = array_values(json_decode($old, true));

        array_push($old,$new);

        print_r($old);

        $current = json_encode($old);
        file_put_contents($file, $current);

        header("Location: $this->url/?task=completed");
        exit();
    }

    public function remove_save(){
        $file = './save_data.json';
        $json = file_get_contents($file);
        $arr = array_values(json_decode($json, true));
        unset($arr[$this->i]);
        $current = json_encode($arr);
        file_put_contents($file, $current);
        header("Location: $this->url/?task=completed");
        exit();
    }

    public function send(){
        for($i = 0; $i < count($this->user_list); $i++){
            $url = 'https://api.vk.com/method/messages.send';
            $params = array(
                'user_id' => $this->user_list[$i],    // Кому отправляем
                'message' => $this->message_text,   // Что отправляем
                'access_token' => $this->group_token,  // access_token можно вбить хардкодом, если работа будет идти из под одного юзера
                'v' => '5.37',
            );
            $result = file_get_contents($url, false, stream_context_create(array(
                'http' => array(
                    'method'  => 'POST',
                    'header'  => 'Content-type: application/x-www-form-urlencoded',
                    'content' => http_build_query($params)
                )
            )));
            $this->send_log .= $this->user_list[$i] . ' - ' . $result . '<br>';
        }
        header("Location: $this->url/?task=completed");
        exit();
    }

    public function save(){

        $date = date("Y/m/d/h/i/s");
        $data_now[$date]['id'] = $this->group_id;
        $data_now[$date]['token'] = $this->group_token;
        $data_now[$date]['text'] = $this->message_text;

        $data = [];
        array_push($data,$data_now);

        $file = './save_data.json';
        $json = file_get_contents($file);
        $arr = array_values(json_decode($json, true));
        $arr[] = $data;

        echo '<pre>';
        print_r($arr);
        echo '</pre>';
        $current = json_encode($arr);
        file_put_contents($file, $current);

        header("Location: $this->url/?task=completed");
        exit();
    }

    public function get_user_from_id($id) {
        if(!empty($id)){
            $res = json_decode(file_get_contents("https://api.vk.com/method/groups.getMembers?group_id=" . $id . "&v=5.73"), true);
            $this->user_list = $res['response']['items'];
        }
    }

}

$Sender = new Sender();