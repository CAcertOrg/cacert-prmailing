<?php

class db_function{

    public $db;

    /**
     * db_function::__construct()
     * constructor of the class
     */
    public function __construct(){
        include('dbconfig.php');
        $this -> db = New PDO("mysql:host=$dbhost;dbname=$dbdatabase", "$dbuser", "$dbpw");
    }

// user handling
    /**
     * db_function::get_userid_from_mail()
     * returns the id of a user entry if the email matches
     * @param mixed $email
     * @return
     */
    public function get_userid_from_mail($email){

        $query = "select `user_id` from `user` where  ` `email``='$email'";
        $res = $this -> db -> query($query);
        if($res){
            $result =  $res->fetch();
            return $result[0]['user_id'];
        } else {
            return 0;
        }
    }


    /**
     * db_function::get_userdata()
     * returns the data of a user
     * @param mixed $uid
     * @return
     */
    public function get_userdata($uid){
        $uid = intval($uid);
        $result = array();
        $query = "select `user_id`, `user_name`, `email`, `read_permission`, `write_permission` from `user` where  `user_id`='$uid'";
        $res = $this -> db -> query($query);
        if($res){
            return $res->fetch();
        } else {
            return $result;
        }
    }

    /**
     * db_function::get_user_write_permission()
     * returns the write permissions for a user
     * @param mixed $uid
     * @return
     */
    public function get_user_write_permission($uid){
        $uid = intval($uid);
        $query = "select  `read_permission`, `write_permission` from `user` where  `user_id`='$uid'";
        if($res = $this->db->query(query)){
            return $res[0]['write_permission'];
        } else{
            return 0;
        }
    }


    /**
     * db_function::get_user_read_permission()
     * returns the read permissions for a user
     * @param mixed $uid
     * @return
     */
    public function get_user_read_permission($uid){
        $uid = intval($uid);
        $result = array();
        $query = "select`read_permission` from `user` where  `user_id`='$uid'";
        if($res = $this->db->query(query)){
            return $res[0]['read_permission'];
        } else{
            return 0;
        }
    }

    /**
     * db_function::insert_user()
     * inserts new user data
     * @param mixed $username
     * @param mixed $email
     * @param mixed $readpermission
     * @param mixed $writepermission
     * @param mixed $uid    id of user adding the data
     * @return
     */
    public function insert_user($username, $email, $readpermission, $writepermission, $uid){

        $query = "Insert into `user` (`user_name`, `email`,
            `read_permission`, `write_permission`,
            `created_by`, `last_change`, `last_change_by`)
            VALUES ('$username', '$email',
            '$readpermission', '$writepermission',
            $uid, Now(), $uid)";
        $smt = $this -> db -> prepare($query);
        $smt -> execute();
        $nid = $this -> db -> lastInsertId();
        //write log
        write_log('admin', $nid, "added account for $email");

    }

    /**
     * db_function::update_user()
     *updates the data of a given user
     * @param mixed $username
     * @param mixed $email
     * @param mixed $readpermission
     * @param mixed $writepermission
     * @param mixed $uid    id of user adding the data
     * @param mixed $cid    id of dataset changed
     * @return
     */
    public function update_user($username, $email, $readpermission, $writepermission, $uid, $cid){

        $query = "Update `user` Set `user_name` = '$username',
            `email` = '$email',
            `read_permission` = '$readpermission',
            `write_permission` = '$writepermission',
            `last_change` = Now(),
            `last_change_by` = $uid
            WHERE  `user_id` = $cid";
        $smt = $this -> db -> prepare($query);
        $smt -> execute();
        //write log
        write_log('admin', $cid, "update account of $email");

    }


    /**
     * db_function::get_all_user()
     * returns all users entries
     * @return
     */
    public function get_all_user(){
        $query = "select `user_id`, `user_name`, `email`, `read_permission`, `write_permission` from `user` ORDER BY `user_name`";
        $res = $this -> db -> query($query);
        if($res){
            return $res->fetchAll();
        }
    }


    // view handling
    /**
     * db_function::get_all_view()
     * returns all recorded views, if where is not given all views, if given only the requested view
     * @param string $where name of the requested view
     * @return
     */
    public function get_all_view($where = ''){
        if ($where == '') {
            $where = ' Where 1=1 ';
        }else{
            $where = ' Where view_id='.$where.' ';
        }
        $query = "select `view_id`, `view_name`, `read_permission`, `write_permission`, `active` from `view` " . $where . "ORDER BY `view_name`";
        $res = $this -> db -> query($query);
        if($where == ' Where 1=1 '){
            return $res->fetchAll();
        } else {
            return $res->fetch();
        }
    }

    /**
     * db_function::insert_view()
     * inserts a new view
     * @param mixed $view_name
     * @param mixed $read_permission
     * @param mixed $write_permission
     * @return
     */
    public function insert_view($view_name, $read_permission, $write_permission){
        $query = "Insert into `view` (`view_name`, `read_permission`, `write_permission`, `active`)
            VALUES ('$view_name', '$read_permission', '$write_permission', 1)";
        $smt = $this -> db -> prepare($query);
        $smt -> execute();
        $nid = $this -> db -> lastInsertId();
        //write log
        write_log('admin', $nid, "added view '$view_name'");

    }

    /**
     * db_function::update_view()
     * updates the data of a view
     * @param mixed $view_name
     * @param mixed $read_permission
     * @param mixed $write_permission
     * @param mixed $active
     * @param mixed $vid
     * @return
     */
    public function update_view($view_name, $read_permission, $write_permission, $active, $vid){

        $query = "Update `view` Set `view_name` = '$view_name',
            `read_permission` = '$read_permission',
            `write_permission` = '$write_permission',
            `active` = '$active'
            WHERE `view_id` = $vid";
        $smt = $this -> db -> prepare($query);
        $smt -> execute();
        //write log
        write_log('admin', $vid, "updated view '$view_name'");
    }

    /**
     * db_function::get_view_right()
     *  retruns the permmisons for a given view
     * @param mixed $view name of the view
     * @return
     */
    public function get_view_right($view){
        $query = "SELECT `view_name` , `read_permission` , `write_permission`, `active`
                    FROM `view`
                    WHERE `view_name` = '$view'";
        $res = $this -> db -> query($query);

        $result = $res->fetch();
        return $result;
    }





    // language handling

    public function get_all_lang($where = ''){
        if ($where == '') {
            $where = ' Where 1=1 ';
        }else{
            $where = ' Where language_id='.$where.' ';
        }
        $query = "select `language_id`, `language`, `language_short` from `language` " . $where . "ORDER BY `language`";
        $res = $this -> db -> query($query);
        if($where == ' Where 1=1 '){
            return $res->fetchAll();
        } else {
            return $res->fetch();
        }
    }


    public function insert_lang($lang, $langshort){
        $query = "Insert into `language` (`language`, `language_short`)
                VALUES ('$lang', '$langshort')";
        $smt = $this -> db -> prepare($query);
        $smt -> execute();
        $nid = $this -> db -> lastInsertId();
        //write log
        write_log('admin', $nid, "added language '$lang'");

    }


    public function update_lang($lang, $langshort, $lid){

        $query = "Update `language` Set `language` = '$lang',
                `language_short` = '$langshort'
                WHERE `language_id` = $lid";
        $smt = $this -> db -> prepare($query);
        $smt -> execute();
        //write log
        write_log('admin', $lid, "updated language '$lang'");
    }



}
?>