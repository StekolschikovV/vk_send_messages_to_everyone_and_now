<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1>Рассылка по всем пользователям группы вк</h1>
            <br>
            <br>
        </div>
        <div class="col-md-4">
            <h3>Шаблоны заданий</h3>
            <?php
                $file = './save_data.json';
                $json = file_get_contents($file);
                if(strlen($json)> 2){
                    $arr = array_values(json_decode($json, true));
                    echo '<ul>';
                    for ($i = 0; $i < count($arr); $i++) {
                        $a = $arr[$i][0][array_keys($arr[$i][0])[0]];
                        $t = array_keys($arr[$i][0])[0];
                        echo "<li>
                            <a href='http://bot.worldwideshop.ru/vk_send_messages_to_everyone_and_now/?id=" . $a['id'] . "&token=" . $a['token'] . "&text=" . $a['text'] . "'>" . $t . "</a>
                            <a href='http://bot.worldwideshop.ru/vk_send_messages_to_everyone_and_now/send.php?i=" . $i . "&action=remove_save'>Удалить</a>
                    </li>";
                    }
                    echo '</ul>';
                } else {
                    echo 'Шаблонов нет';
                }
            ?>
            <h3>Запланированы отправки</h3>
            <?php
            $file = './save_task.json';
            $json = file_get_contents($file);
            if(strlen($json) > 2){
                $arr = array_values(json_decode($json, true));
                echo '<ul>';
                for ($i = 0; $i < count($arr); $i++) {
                    $a = $arr[$i];
                    echo "<li>
                            <a href='http://bot.worldwideshop.ru/vk_send_messages_to_everyone_and_now/?id=" . $a['id'] . "&date=" . $a['date'] . "&token=" . $a['token'] . "&text=" . $a['text'] . "'>" . $arr[$i]['date'] . "</a>
                            <a href='http://bot.worldwideshop.ru/vk_send_messages_to_everyone_and_now/send.php?i=" . $i . "&action=remove_task'>Удалить</a>
                    </li>";
                }
                echo '</ul>';
            } else {
                echo 'Шаблонов нет';
            }
            ?>
        </div>
        <div class="col-md-8">
            <h3>Форма создания заданий</h3>
            <form action="./send.php" method="get">
                <div class="form-group">
                    <div class="col-md-12">
                        <label for="id">Укажите название группы(id):</label>
                    </div>
                    <input type="text" name="id" class="form-control" placeholder="id group" id="id" aria-describedby="id" value="<?=$_GET['id']?>" required>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <label for="token">Укажите токен группы:</label>
                    </div>
                    <input type="text" name="token" class="form-control" placeholder="token group" id="token" aria-describedby="token" value="<?=$_GET['token']?>" required>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <label for="action_type">Что нужно сделать:</label>
                    </div>
                    <select name="action" class="form-control" id="action_type" required>
                        <option value="send">Отправить сейчас</option>
                        <option value="add_task">Запланировать отправку</option>
                        <option value="save">Сохранить шаблон</option>
                    </select>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <label for="action_type">Когда нужно сделать:</label>
                    </div>
                    <div class="form-inline">
                        <input class="form-control" type="text" name="Y" placeholder="Год" value="<?=date(Y)?>" style="width: 4em" /> /
                        <input class="form-control" type="text" name="m" placeholder="Месяц" value="<?=date(m)?>" style="width: 3em" /> /
                        <input class="form-control" type="text" name="d" placeholder="День" value="<?=date(d)?>" style="width: 3em" /> &nbsp; &nbsp; &nbsp;
                        <input class="form-control" type="text" name="h" placeholder="Час" value="<?=date(h)?>" style="width: 3em" /> :
                        <input class="form-control" type="text" name="ii" placeholder="Минута" value="<?=date(i)?>" style="width: 3em" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <label for="message_text ">Текст сообщения отпраки:</label>
                    </div>
                    <textarea style="width: 100%" class="form-control" name="text" rows="10" id="message_text" placeholder="massage text" required><?=$_GET['text']?></textarea>
                </div>
                <input  class="btn btn-primary btn-block" type="submit" value="Выполнить"/>
            </form>
        </div>
    </div>
</div>
<script>
    <?php
        if($_GET['task'] == 'completed'){
            ?>
            alert('task completed');
            <?php
        }
    ?>
</script>
</body>
</html>

<?php

 ?>