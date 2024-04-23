
<?php
class BaseFunctions
{
    public $version = "f1s";
    protected $db_connection = NULL;

    public $ID = NULL;
    protected $password = "";
    public $email_user = "";
    public $status = NULL;

    protected $errflag = false;

    public $user_is_logged_in = false;
    public $redirect = "";
    public $view = "f_index";
    public $lang = "ro";

    public $now = "";

    public $rep = array();

    public $tinyMce = false;
    public $lightbox = false;
    public $imageLoader = false;
    public $slimCrop = false;
    public $pageSel2 = false;
    public $dataTable = false;
    public $fileUploader = false;

    public $page_title = "MyCompany - CMS";
    public $page_description = "Atinge întregul potențial al organzației tale";
    public $page_url = BASE_URL;
    public $page_image = "";

    public function __construct(){

        $this->now = date("Y-m-d H:i:s");

        if (isset($_GET['view'])) {
            $this->view=$_GET['view'];
        }

        $this->checkUserLoggedIn();

        if ($this->view=='b_acc_logout') {

            if ($this->user_is_logged_in) {
                // code...
                $args = array();
                $args['id_user'] = $this->ID;
                $args['target_table'] = "users";
                $args['id_target'] = $this->ID;
                $args['note'] = "User logged out. (base)";
                $this->logAction($args);
            }


            $this->doLogout();
            $this->redirect = $this->buildUrl(array('view'=>"f_index"));
        }

        if (isset($_POST['registerUser'])) {

            $args = array();

            
            
            if(isset($_POST['register-email'])){
                $args['email'] = trim(htmlspecialchars(strtolower($_POST['register-email'])));
            }else{
                $args['email'] = "";
            }
            if(isset($_POST['register-password'])){
                $args['password'] = trim(htmlspecialchars($_POST['register-password']));
            }else{
                $args['password'] = "";
            }
            if(isset($_POST['register-acc_tc'])){
                $args['acc_tc'] = trim(htmlspecialchars($_POST['register-acc_tc']));
            }else{
                $args['acc_tc'] = "";
            }

            if ($this->registerUser($args)) {
                // can do something here
            } else {
                // or something else here
            }
        }elseif (isset($_POST['loginUser'])) {

            $args = array();

            if (isset($_POST['login-username'])) {
                $args['username'] = trim(htmlspecialchars($_POST['login-username']));
            }else{
                $args['username'] = "";
            }
            if (isset($_POST['login-password'])) {
                $args['password'] = trim(htmlspecialchars($_POST['login-password']));
            }else{
                $args['password'] = "";
            }

            if ($this->loginWithPostData($args)) {
                $this->redirect = $this->buildUrl(array('view'=>'b_acc_dashboard'));
            }else{
                $this->rep['username'] = $args['username'];
            }
        }elseif (isset($_POST['addDepartment'])) {
            

            $args = array();
            if (isset($_POST['name'])) {
                $args['name'] = trim(htmlspecialchars($_POST['name']));
            }else{
                $args['name'] = "";
            }
            if (isset($_POST['id_parent'])) {
                $args['id_parent'] = trim(htmlspecialchars($_POST['id_parent']));
            }else{
                $args['id_parent'] = "";
            }


            if ($id_department = $this->departmentsAdd($args)) {
                $this->redirect = $this->buildUrl(array('view'=>"a_departments_edit", 'id_department'=>$id_department));
            }else{
                $this->rep['name'] = $args['name'];
            }
        }elseif (isset($_POST['editDepartment'])) {
            

            $args = array();
            if (isset($_POST['name'])) {
                $args['name'] = trim(htmlspecialchars($_POST['name']));
            }else{
                $args['name'] = "";
            }
            if (isset($_POST['id_parent'])) {
                $args['id_parent'] = trim(htmlspecialchars($_POST['id_parent']));
            }else{
                $args['id_parent'] = "";
            }
            if (isset($_POST['id_department'])) {
                $args['id_department'] = trim(htmlspecialchars($_POST['id_department']));
            }else{
                $args['id_department'] = "";
            }
            if (isset($_POST['status'])) {
                $args['status'] = trim(htmlspecialchars($_POST['status']));
            }else{
                $args['status'] = "";
            }


            if ($this->departmentsEdit($args)) {
                //
            }

            $this->redirect = $this->buildUrl(array('view'=>"a_departments_edit", 'id_department'=>$args['id_department']));
        }elseif (isset($_POST['deleteDepartment'])) {
            

            $args = array();
            if (isset($_POST['id_department'])) {
                $args['id_department'] = trim(htmlspecialchars($_POST['id_department']));
            }else{
                $args['id_department'] = "";
            }


            if ($this->departmentsDelete($args)) {
                //
            }
            $this->redirect = $this->buildUrl(array('view'=>"a_departments_list"));
        }




        if ($this->view=="b_acc_login") {
            if ($this->user_is_logged_in) {
                $this->redirect = $this->buildUrl(array('view'=>"b_acc_dashboard"));
            }
        }elseif ($this->view=="a_departments_list") {
            
            #code ...
            $this->rep['departments_list'] = $this->departmentsGetList(false);
        }elseif ($this->view=="a_departments_add") {

            $this->pageSel2 = true;
            $this->rep['departments_list'] = $this->departmentsGetList();
        }elseif ($this->view=="a_departments_edit") {

            if (isset($_GET['id_department'])) {
                $id_department = trim(htmlspecialchars($_GET['id_department']));
                $this->rep['dept'] = $this->departmentsGetById($id_department);
                if (isset($this->rep['dept']->ID)) {
                    // code...
                    $this->pageSel2 = true;
                    $this->rep['departments_list'] = $this->departmentsGetList();
                }else{
                    $this->redirect = $this->buildUrl(array('view'=>"a_departments_list"));
                }
            }
        }elseif ($this->view=="f_404") {

            $this->page_title = "404";
            $this->page_description = "Vai! Cum de ai ajuns aici?";
        }elseif ($this->view=="f_departments_list") {

            $this->rep['departments_list'] = $this->departmentsGetChildrenAll();
            $this->rep['tree_view'] = $this->departmentsGenerateTreeView($this->rep['departments_list']);
        }
    }









    protected function checkUserLoggedIn(){
        if (isset($_COOKIE['rememberme'])) {

            $this->loginWithCookieData();
        }elseif (isset($_SESSION['id_user'])&&!empty($_SESSION['id_user'])&&$_SESSION['loggedin']==true) {

            $this->loginWithSessionData();
        }
    }
    public function doLogout(){
        if (isset($_COOKIE['rememberme'])){
            $this->deleteRememberMeCookie();
        }
        $_SESSION = array();
        session_destroy();
        $this->user_is_logged_in = false;
    }
    protected function deleteRememberMeCookie() {
        list($ID, $token, $hash) = explode('_', $_COOKIE['rememberme']);
        if ($this->databaseConnection()&&!empty($ID)) {
            $q = $this->db_connection->prepare("
                UPDATE `users` 
                SET 
                    `token_rememberme`=NULL 
                WHERE 
                    `ID`=:ID
                ");
            $q->bindValue(":ID", $ID, PDO::PARAM_INT);
            $q->execute();
            $r = $q->rowCount();
            
            if ($r>0) {
                setcookie('rememberme', false, (time()-(3600*3650)), '/', COOKIE_DOMAIN);
                return true;
            }
        }
        return false;
    }
    protected function newRememberMeCookie() {
        $random_token_string = hash('sha256', mt_rand());
        $cookie_string_first_part = $this->ID.'_'.$random_token_string;
        $cookie_string_hash = hash('sha256', $cookie_string_first_part.COOKIE_SECRET_KEY);
        $cookie_string = $cookie_string_first_part.'_'.$cookie_string_hash;
        
        if ($this->databaseConnection()) {
            $q = $this->db_connection->prepare("
                UPDATE `users` 
                SET 
                    `token_rememberme`=:token_rememberme 
                WHERE 
                    `ID`=:ID
                ");
            $q->bindValue(":token_rememberme", $random_token_string, PDO::PARAM_STR);
            $q->bindValue(":ID", $this->ID, PDO::PARAM_INT);
            $q->execute();
            $r = $q->rowCount();
            
            if ($r>0) {
                setcookie('rememberme', $cookie_string, (time()+COOKIE_RUNTIME), '/', COOKIE_DOMAIN);
                return true;
            }
        }
        return false;
    }
    protected function generateTokenValidareEmail() {
        $token_validare_email = sha1(uniqid(mt_rand(), true));
        $checkToken = $this->getUserByTokenValidareEmail($token_validare_email);
        if (isset($checkToken->ID)) {
            $token_validare_email = $this->generateTokenValidareEmail();
        }

        return $token_validare_email;
    }
    protected function generateTokenResetPassword() {
        $token_resetare_parola = sha1(uniqid(mt_rand(), true));
        $checkToken = $this->getUserByTokenResetareParola($token_resetare_parola);
        if (isset($checkToken->ID)) {
            $token_resetare_parola = $this->generateTokenResetPassword();
        }

        return $token_resetare_parola;
    }









    protected function setValues( $user ) {

        if ( isset($user->ID) ) {


            $this->ID = $user->ID;
            $this->password = $user->password;
            $this->email_user = $user->email_user;
            $this->status = $user->status;
        }
    }
    protected function refreshUser() {
        $refresh = $this->getUserById($this->ID);
        $this->setValues($refresh);
        return true;
    }
    protected function loginWithCookieData() {
        list($ID, $token, $hash) = explode('_', $_COOKIE['rememberme']);
        if ($hash == hash('sha256', $ID.'_'.$token.COOKIE_SECRET_KEY) && !empty($token)) {
            $user = $this->getUserByTokenRememberme($ID, $token);

            if (isset($user->ID)) {
                $this->setValues($user);
                $_SESSION['loggedin'] = true;
                $_SESSION['id_user'] = $user->ID;
                $this->user_is_logged_in = true;
                return true;
            }
        }
        $this->deleteRememberMeCookie();
        return false;
    }
    protected function loginWithSessionData() {
        $id_user = $_SESSION['id_user'];
        $user = $this->getUserById($id_user);

        if (isset($user->ID)) {
            $this->setValues($user);
            $this->user_is_logged_in = true;
        }else {
            $this->doLogout();
        }
    }
    protected function loginWithPostData( $params=array() ) {

        $username = isset($params['username'])?$params['username']:"";
        $password = isset($params['password'])?$params['password']:"";

        if (empty($username) || strlen($username) > 256 ){
            $this->errflag = true;
            $this->rep['errors']['username'] = "is-invalid";
            $this->rep['ajxrsp']['errors']['username'] = "is-invalid";
        }
        if (empty($password)) {
            $this->errflag = true;
            $this->rep['errors']['password'] = "is-invalid";
            $this->rep['ajxrsp']['errors']['password'] = "is-invalid";
        }
        if ($this->errflag) {
            return false;
        }

        $username=strtolower($username);
        $user = $this->getUserByEmail($username);

        if (!isset($user->ID)) {
            $_SESSION['msg_errors'][] = "Emailul sau parola sunt greșite.";
        }elseif (!password_verify($password, $user->password)) {
            $_SESSION['msg_errors'][] = "Emailul sau parola sunt greșite...";
        }elseif ($user->status==-1) {
            $_SESSION['msg_warning'][] = 'Contul este șters. Îl poți recupera <a href="'.$this->buildUrl(array('view'=>'b_acc_recover')).'">aici</a> contactându-ne.';
        }elseif ($user->status==1) {
            $_SESSION['msg_warning'][] = 'Pentru a continua trebuie sa confirmati adresa de email accesând linkul din mesajul primit. Dacă nu ați primit mesajul îl puteți cere încă o data de <a href="'.$this->buildUrl(array('view'=>'b_acc_register_confirm_send')).'">aici</a>';
        }elseif ($user->status==2) {
            $_SESSION['msg_warning'][] = 'Adresa de email a fost confirmată, un moderator va activa contul în curând.';
        }elseif ($user->status==3) {
            $this->user_is_logged_in = true;
            $this->setValues($user);
            $_SESSION['loggedin'] = true;
            $_SESSION['id_user'] = $user->ID;
            $this->newRememberMeCookie();

            return true;
        }else{
            $_SESSION['msg_warning'][] = 'Eroare necunoscută';
        }
        return false;
    }









    protected function getUserById( $id_user ) {


            /*
                DELIMITER //
                CREATE PROCEDURE get_user_by_id(
                    IN in_id_user int, 
                    IN in_hash varchar(16)
                )
                BEGIN
                    SELECT *, AES_DECRYPT(email, in_hash) AS email_user 
                FROM users 
                WHERE 
                    ID=in_id_user;
                END //
                DELIMITER ;
            */


        if ($this->databaseConnection()) {
            $q = $this->db_connection->prepare("CALL get_user_by_id(:id_user, :hash)");
            $db_secret = DB_SECRET;
            $q->bindParam(":id_user", $id_user, PDO::PARAM_INT);
            $q->bindParam(":hash", $db_secret, PDO::PARAM_STR);
            $q->execute();
            $r = $q->fetchObject();
            
            return $r;
        }
        return false;
    }
    protected function getUserByEmail( $email ) {


            /*
                DELIMITER //
                CREATE PROCEDURE get_user_by_email(
                    IN in_email varchar(256), 
                    IN in_hash varchar(16)
                )
                BEGIN
                    SELECT *, AES_DECRYPT(email, in_hash) AS email_user 
                FROM users 
                WHERE 
                    AES_DECRYPT(email, in_hash)=in_email;
                END //
                DELIMITER ;
            */


        if ($this->databaseConnection()) {
            $q = $this->db_connection->prepare("CALL get_user_by_email(:email, :hash)");
            $db_secret = DB_SECRET;
            $q->bindParam(":email", $email, PDO::PARAM_STR);
            $q->bindParam(":hash", $db_secret, PDO::PARAM_STR);
            $q->execute();
            $r = $q->fetchObject();
            
            return $r;
        }
        return false;
    }
    protected function getUserByTokenValidareEmail( $token_validare_email ) {


            /*
                DELIMITER //
                CREATE PROCEDURE get_user_by_tve(
                    IN in_tve varchar(64), 
                    IN in_hash varchar(16)
                )
                BEGIN
                    SELECT *, AES_DECRYPT(email, in_hash) AS email_user 
                FROM users 
                WHERE 
                    token_validare_email=in_tve;
                END //
                DELIMITER ;
            */

        if ($this->databaseConnection()) {
            $q = $this->db_connection->prepare("CALL get_user_by_tve(:token_validare_email, :hash)");
            $db_secret = DB_SECRET;
            $q->bindParam(":token_validare_email", $token_validare_email, PDO::PARAM_STR);
            $q->bindParam(":hash", $db_secret, PDO::PARAM_STR);
            $q->execute();
            $r = $q->fetchObject();
            
            return $r;
        }
        return false;
    }
    protected function getUserByTokenResetareParola( $token_resetare_parola ) {


            /*
                DELIMITER //
                CREATE PROCEDURE get_user_by_trp(
                    IN in_trp varchar(64), 
                    IN in_hash varchar(16)
                )
                BEGIN
                    SELECT *, AES_DECRYPT(email, in_hash) AS email_user 
                FROM users 
                WHERE 
                    token_resetare_parola=in_trp;
                END //
                DELIMITER ;
            */

        if ($this->databaseConnection()) {
            $q = $this->db_connection->prepare("CALL get_user_by_trp(:token_resetare_parola, :hash)");
            $db_secret = DB_SECRET;
            $q->bindParam(":token_resetare_parola", $token_resetare_parola, PDO::PARAM_STR);
            $q->bindParam(":hash", $db_secret, PDO::PARAM_STR);
            $q->execute();
            $r = $q->fetchObject();
            
            return $r;
        }
        return false;
    }
    protected function getUserByTokenRememberme( $id_user, $token_rememberme ) {


            /*
                DELIMITER //
                CREATE PROCEDURE get_user_by_trm(
                    IN in_id_user int, 
                    IN in_trm varchar(64), 
                    IN in_hash varchar(16)
                )
                BEGIN
                    SELECT *, AES_DECRYPT(email, in_hash) AS email_user 
                FROM users 
                WHERE 
                    token_rememberme=in_trm
                    AND ID=in_id_user;
                END //
                DELIMITER ;
            */

        if ($this->databaseConnection()) {
            $q = $this->db_connection->prepare("CALL get_user_by_trm(:id_user, :token_rememberme, :hash)");
            $db_secret = DB_SECRET;
            $q->bindParam(":id_user", $id_user, PDO::PARAM_INT);
            $q->bindParam(":token_rememberme", $token_rememberme, PDO::PARAM_STR);
            $q->bindParam(":hash", $db_secret, PDO::PARAM_STR);
            $q->execute();
            $r = $q->fetchObject();
            
            return $r;
        }
        return false;
    }









    protected function registerUser( $params ) {

        $email = isset($params['email'])?$params['email']:"";
        $password = isset($params['password'])?$params['password']:"";
        $acc_tc = isset($params['acc_tc'])?$params['acc_tc']:"";

        /* password required */
        if (empty($password)||strlen($password)<8) {
            $_SESSION['msg_errors'][] = "&ldquo;Parola&rdquo; trebuie să conțină cel puțin 8 caractere.";
            $this->rep['errors']['password'] = "is-invalid";
            $this->errflag = true;
        }else {
            $user_password_hash = password_hash($password, PASSWORD_DEFAULT);
        }

        /* email required */
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['msg_errors'][] = "Vă rugăm să completați corect câmpul email";
            $this->rep['errors']['email'] = "is-invalid";
            $this->errflag = true;
        }else {
            $checkEmail = $this->getUserByEmail($email);
            if (isset($checkEmail->ID)) {
                $_SESSION['msg_errors'][] = "Adresa e-mail ".$email." este deja folosită";
                $this->rep['errors']['email'] = "is-invalid";
                $this->errflag = true;
            }
        }

        /* accord terms & conditions required */
        if ($acc_tc!="da") {
            $_SESSION['msg_errors'][] = "Vă rugăm să acceptați termenii și condițiile site-ului.";
            $this->rep['errors']['acc_tc'] = "is-invalid";
            $this->errflag = true;
        }

        /* stop if field validation has any error */
        if ($this->errflag) {
            return false;
        }

        if ($this->databaseConnection()) {
            /* generate unique token for email account validation */
            $token_validare_email = $this->generateTokenValidareEmail();

            /*
                DELIMITER //
                CREATE PROCEDURE insert_user(
                    IN in_password varchar(64),
                    IN in_email varchar(256),
                    IN in_hash varchar(16),
                    IN in_token_validare_email varchar(64)
                )
                BEGIN
                    INSERT INTO users(password,status,token_validare_email,email,created_time) VALUES(in_password,1,in_token_validare_email,AES_ENCRYPT(in_email,in_hash),NOW());
                END //
                DELIMITER ;
            */

            /*echo '<br>user_password_hash: ';
            echo $user_password_hash;
            echo '<br>email: ';
            echo $email;
            echo '<br>DB_SECRET: ';
            echo DB_SECRET;
            echo '<br>token_validare_email: ';
            echo $token_validare_email;
            user_password_hash: $2y$10$lOZx8zijszfXpPkVyYkMhO.7lmrqYIJISCyZ2cURhcUdivojDvlei
            : emailsorinionut.dan@gmail.com
            DB_SECRET: h4Rm0n!a
            token_validare_email: 6a96b2898c69b9729929864e2e322c7eb7292018

            exit();*/


            /*
                CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_user`(IN `in_password` VARCHAR(64), IN `in_email` VARCHAR(256), IN `in_hash` VARCHAR(16), IN `in_token_validare_email` VARCHAR(64)) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN INSERT INTO users(password,status,token_validare_email,email,created_time) VALUES(in_password,1,in_token_validare_email,AES_ENCRYPT(in_email,in_hash),NOW()); END
            */

            $q = $this->db_connection->prepare("CALL insert_user(:password, :email, :hash, :token_validare_email)");
            $db_secret = DB_SECRET;
            $q->bindParam(':password', $user_password_hash, PDO::PARAM_STR);
            $q->bindParam(':email', $email, PDO::PARAM_STR);
            $q->bindParam(':hash', $db_secret, PDO::PARAM_STR);
            $q->bindParam(':token_validare_email', $token_validare_email, PDO::PARAM_STR);

            $q->execute();
            $r = $q->rowCount();
            
            if ($r>0) {
                $link_activare_cont = $this->buildUrl(array('view'=>'b_acc_register_confirm', 'tve'=>$token_validare_email));

                // $this->registerSendConfirmationEmail( $userObj );

                $_SESSION['msg_success'][] = '<h1>Contul a fost creat cu succes.</h1><p>Un email pentru confirmare adresei de email a fost trimis la: "'.$email.'".</p><p><a href="'.$link_activare_cont.'">click</a></p>';
                
                return true;
            }
        }
        return false;
    }
    protected function registerSendConfirmationEmail( $userObj ) {
        

        // let's consider that mail() works on localhost
    }
    protected function registerConfirmUser( $token_validare_email ) {

        // user can be logged in without email confirmed (from older version)

        $checkUser = $this->getUserByTokenValidareEmail( $token_validare_email );

        if (!isset($checkUser->ID)) {
            // token_validare_email invalid
            if ($this->ID) {
                // message with redirect to acc where he can request a new link
                $_SESSION['msg_warning'][] = 'Linkul este nu mai este valabil. În cazul în care ai validat deja emailul <a href="'.$this->buildUrl(array('view'=>'b_acc_dashboard')).'">intră în cont</a>';
            }else{
                // message with redirect to acc where he can request a new link
                $_SESSION['msg_warning'][] = 'Linkul este nu mai este valabil. Cere <a href="'.$this->buildUrl(array('view'=>'b_acc_register_confirm_send')).'">aici</a> să fie retrimis emailul de confirmare. În cazul în care ai validat deja emailul <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">intră în cont</a>';

                // message with login popup in case user cand be logged in without email confirmation
                // $_SESSION['msg_warning'][] = 'Linkul este nu mai este valabil. În cazul în care ai validat deja emailul <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">intră în cont</a>';
                $this->errflag = true;
            }
        }elseif ($checkUser->status==-1) {
            $_SESSION['msg_errors'][] = "Contul este șters.";
            $this->errflag = true;
        }elseif ($checkUser->status==0) {
            $_SESSION['msg_errors'][] = "Contul este blocat.";
            $this->errflag = true;
        }elseif ($checkUser->status==2) {

            // peding admin action
            $_SESSION['msg_warning'][] = 'Adresa de email a fost confirmată. Un moderator va activa contul în curând.';
            $this->errflag = true;
        }else{
            // at this point $checkUser->status should be either 1 and token_validare_email should be ok or 3 and token_validare_email should be null and would get the message that the link is not ok anymore
        }

        if ($this->errflag) {
            return false;
        }

        $status = 3;

        if ($this->databaseConnection()) {


            /*
                DELIMITER //
                CREATE PROCEDURE update_user_status(
                    IN in_id_user int, 
                    IN in_status int
                )
                BEGIN
                    UPDATE
                        `users`
                    SET
                        `status`=in_status
                    WHERE
                        ID=in_id_user;
                END //
                DELIMITER ;
            */


            /*$q = $this->db_connection->prepare("
                UPDATE `users` 
                SET 
                    `status`=:status
                WHERE 
                    `ID`=:ID
            ");*/

            $q = $this->db_connection->prepare("CALL update_user_status(:id_user, :status)");
            $q->bindValue(":id_user", $checkUser->ID, PDO::PARAM_INT);
            $q->bindValue(":status", $status, PDO::PARAM_INT);

            $r = $q->execute();
            
            if ($r) {

                $_SESSION['msg_success'][] = 'Adresa de email a fost confirmată. Acum te poți autentifica. <a href="'.$this->buildUrl(array('view'=>'b_acc_login')).'" class="">login</a>.';
                return true;
            }
        }
        return false;
    }









    protected function buildPager( $view, $nr_of_pages, $params ) {
        
        $nr_of_buttons = isset($params['nr_of_buttons'])?$params['nr_of_buttons']:5;
        $current_page = $this->page;
        $urlParams = $params;

        if ($nr_of_pages>$nr_of_buttons) {
            if ($current_page<=3) {
                $i_start = 1;
                $i_end = 5;
            }elseif ($current_page>=($nr_of_pages-3)) {
                $i_start = $nr_of_pages-4;
                $i_end = $nr_of_pages;
            }else{
                $i_start = $current_page-2;
                $i_end = $current_page+2;
            }
        }else{
            $i_start = 1;
            $i_end = $nr_of_pages;
        }


        $pager = "";
        if ($nr_of_pages>1) {
            $pager = '
                <div class="pager">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">';

            if ($current_page>1) {
                $urlParams['page'] = $current_page-1;
                $pager .= '
                            <li class="page-item prev">
                                <a class="page-link" href="'.$this->buildUrl($urlParams).'" aria-label="Previous">
                                    <span aria-hidden="true" class="icon-prev">&laquo;</span>
                                    <span class="sr-only">Previous</span>
                                </a>
                            </li>';
            }else {
                $urlParams['page'] = 1;
                $pager .= '
                            <li class="page-item prev disabled">
                                <a class="page-link" href="'.$this->buildUrl($urlParams).'" aria-label="Previous">
                                    <span aria-hidden="true" class="icon-prev">&laquo;</span>
                                    <span class="sr-only">Previous</span>
                                </a>
                            </li>';
            }
            for ($i=$i_start; $i<=$i_end ; $i++) {
                if ($i==$current_page) {
                    $active = "active";
                }else{
                    $active = "";
                }
                $urlParams['page'] = $i;
                $pager .= ' <li class="page-item '.$active.'"><a class="page-link" href="'.$this->buildUrl($urlParams).'">'.$i.'</a></li>';
            }
            if ($current_page<$nr_of_pages) {
                $urlParams['page'] = $current_page+1;
                $pager .= '        <li class="page-item next">
                                        <a class="page-link" href="'.$this->buildUrl($urlParams).'" aria-label="Next">
                                            <span aria-hidden="true" class="icon-next">&raquo;</span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </li>';
            }else {
                $urlParams['page'] = $nr_of_pages;
                $pager .= '        <li class="page-item next disabled">
                                        <a class="page-link" href="'.$this->buildUrl($urlParams).'" aria-label="Next">
                                            <span aria-hidden="true" class="icon-next">&raquo;</span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </li>';
            }
            $pager .= '
                    </ul>
                </nav>
            </div>
            ';
        }
        return $pager;
    }
    protected function buildPagerAsTabs( $view, $nr_of_pages, $params ) {
        
        $nr_of_buttons = isset($params['nr_of_buttons'])?$params['nr_of_buttons']:5;
        $current_page = $this->page;
        $urlParams = $params;

        if ($nr_of_pages>$nr_of_buttons) {
            if ($current_page<=3) {
                $i_start = 1;
                $i_end = 5;
            }elseif ($current_page>=($nr_of_pages-3)) {
                $i_start = $nr_of_pages-4;
                $i_end = $nr_of_pages;
            }else{
                $i_start = $current_page-2;
                $i_end = $current_page+2;
            }
        }else{
            $i_start = 1;
            $i_end = $nr_of_pages;
        }


        $pager = "";
        if ($nr_of_pages>1) {
            $pager = '
                <div class="pager">
                    <nav aria-label="Page navigation">
                        <ul class="nav nav-pills justify-content-center">';

            if ($current_page>1) {
                $urlParams['page'] = $current_page-1;
                $pager .= '
                            <li class="nav-item prev">
                                <a class="page-link" href="'.$this->buildUrl($urlParams).'" aria-label="Previous">
                                    <span aria-hidden="true" class="icon-prev">&laquo;</span>
                                    <span class="sr-only">Previous</span>
                                </a>
                            </li>';
            }else {
                $urlParams['page'] = 1;
                $pager .= '
                            <li class="nav-item prev disabled">
                                <a class="nav-link" href="'.$this->buildUrl($urlParams).'" aria-label="Previous">
                                    <span aria-hidden="true" class="icon-prev">&laquo;</span>
                                    <span class="sr-only">Previous</span>
                                </a>
                            </li>';
            }
            for ($i=$i_start; $i<=$i_end ; $i++) {
                if ($i==$current_page) {
                    $active = "active";
                }else{
                    $active = "";
                }
                $urlParams['page'] = $i;
                $pager .= ' <li class="nav-item"><a class="nav-link '.$active.'" href="'.$this->buildUrl($urlParams).'">'.$i.'</a></li>';
            }
            if ($current_page<$nr_of_pages) {
                $urlParams['page'] = $current_page+1;
                $pager .= '        <li class="nav-item next">
                                        <a class="nav-link" href="'.$this->buildUrl($urlParams).'" aria-label="Next">
                                            <span aria-hidden="true" class="icon-next">&raquo;</span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </li>';
            }else {
                $urlParams['page'] = $nr_of_pages;
                $pager .= '        <li class="nav-item next disabled">
                                        <a class="nav-link" href="'.$this->buildUrl($urlParams).'" aria-label="Next">
                                            <span aria-hidden="true" class="icon-next">&raquo;</span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </li>';
            }
            $pager .= '
                    </ul>
                </nav>
            </div>
            ';
        }
        return $pager;
    }
    public function buildUrl( $params ) {
        $view = isset($params['view'])?$params['view']:"";
        $lang = isset($params['lang'])?$params['lang']:"ro";

        switch ($view) {
            case 'a_departments_edit':
                $result = "/index.php?view=".$view."&id_department=".$params['id_department'];
                break;
            
            case 'b_acc_password_set':
                $result = "/contul-meu/seteaza-parola/".$params['trp'];
                $result = "/index.php?view=".$view."&trp=".$params['trp'];
                break;
            case 'b_acc_register_confirm':
                $result = "/inregistrare-confirmare/".$params['tve'];
                $result = "/index.php?view=".$view."&tve=".$params['tve'];
                break;
            
            default:

                // during dev avoid redirecting to index
                $result = "/index.php?view=".$view;
                break;
        }
        return BASE_URL.$result;
    }
    public function encodeEmail( $text ) {
        $output = "";
        for ($i = 0; $i < strlen($text); $i++) {
            $output .= '&#'.ord($text[$i]).';';
        }
        return $output;
    }
    public function slugify( $text ) {
        $diacritice = array(
            'à', 'á', 'å', 'ä', 'â', 'ă', 'ā', 'ą', 'ã', 'ə', 
            'æ', 
            'ß', 
            'ç', 'ć', 'č', '¢', 'ĉ', 
            'œ', 
            'đ', 'ď', 
            'ë', 'é', 'è', 'ě', 'ê', 'ē', 'ę', 'ė', 
            'ğ', 'ĝ', 'ģ', 
            'ĥ', 
            'i̇', 'í', 'ï', 'î', 'ī', 
            'ĵ', 
            'ķ', 
            'ļ', 'ł', 'ĺ', 'ľ',  
            'ņ', 'ń', 'ň', 'ñ', 
            'ö', 'ó', 'ò', 'ø', 'õ', 'ô', 'ő', 'ð', 'ơ', 
            'ř', 'ŗ', 'ŕ', 
            'ş', 'š', 'ŝ', 'ś', 'ș', 
            'ť', 'ț', 'ţ', 
            'þ', 
            'ü', 'ú', 'ů', 'ŭ', 'ù', 'û', 'ű', 'ū', 'ų', 'ư', 
            'ŵ', 
            'ý', 'ÿ', 'ŷ', 
            'ž', 'ż', 'ź', 
            'À', 'Á', 'Å', 'Ä', 'Â', 'Ă', 'Ā', 'Ą', 'Ã', 'Ə', 
            'Æ', 
            'SS', 
            'Ç', 'Ć', 'Č', '¢', 'Ĉ', 
            'Œ', 
            'Đ', 'Ď', 
            'Ë', 'É', 'È', 'Ě', 'Ê', 'Ē', 'Ę', 'Ė', 
            'Ğ', 'Ĝ', 'Ģ', 
            'Ĥ', 
            'İ', 'Í', 'Ï', 'Î', 'Ī', 
            'Ĵ', 
            'Ķ', 
            'Ļ', 'Ł', 'Ĺ', 'Ľ',  
            'Ņ', 'Ń', 'Ň', 'Ñ', 
            'Ö', 'Ó', 'Ò', 'Ø', 'Õ', 'Ô', 'Ő', 'Ð', 'Ơ', 
            'Ř', 'Ŗ', 'Ŕ', 
            'Ş', 'Š', 'Ŝ', 'Ś', 'Ș', 
            'Ť', 'Ț', 'Ţ', 
            'Þ', 
            'Ü', 'Ú', 'Ů', 'Ŭ', 'Ù', 'Û', 'Ű', 'Ū', 'Ų', 'Ư', 
            'Ŵ', 
            'Ý', 'Ÿ', 'Ŷ', 
            'Ž', 'Ż', 'Ź'
        );
        $transformat = array(
            'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 
            'ae', 
            'b', 
            'c', 'c', 'c', 'c', 'c', 
            'ce', 
            'd', 'd', 
            'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 
            'g', 'g', 'g', 
            'h', 
            'i', 'i', 'i', 'i', 'i', 
            'j', 
            'k', 
            'l', 'l', 'l', 'l',  
            'n', 'n', 'n', 'n', 
            'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 
            'r', 'r', 'r', 
            's', 's', 's', 's', 's', 
            't', 't', 't', 
            'th', 
            'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 
            'w', 
            'y', 'y', 'y', 
            'z', 'z', 'z', 
            'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 
            'AE', 
            'B', 
            'C', 'C', 'C', 'C', 'C', 
            'CE', 
            'D', 'D', 
            'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 
            'G', 'G', 'G', 
            'H', 
            'I', 'I', 'I', 'I', 'I', 
            'J', 
            'K', 
            'L', 'L', 'L', 'L',  
            'N', 'N', 'N', 'N', 
            'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 
            'R', 'R', 'R', 
            'S', 'S', 'S', 'S', 'S', 
            'T', 'T', 'T', 
            'TH', 
            'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 
            'W', 
            'Y', 'Y', 'Y', 
            'Z', 'Z', 'Z'
        );

        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');

        $text = trim($text);

        $text = str_replace($diacritice, $transformat, $text);

        $text = strtolower($text);

        $text = preg_replace('~[^\pL\d.]+~u', '-', $text);

        $text = preg_replace('~[^-\w.]+~', '', $text);

        $text = str_replace(".", "-", $text);

        $text = preg_replace('/(-)+/', '-', $text);

        $text = trim($text, '-');

        if (empty($text)) {
            return '';
        }
        return $text;
    }
    public function replaceDia( $text ) {
        $diacritice = array(
            'à', 'á', 'å', 'ä', 'â', 'ă', 'ā', 'ą', 'ã', 'ə', 
            'æ', 
            'ß', 
            'ç', 'ć', 'č', '¢', 'ĉ', 
            'œ', 
            'đ', 'ď', 
            'ë', 'é', 'è', 'ě', 'ê', 'ē', 'ę', 'ė', 
            'ğ', 'ĝ', 'ģ', 
            'ĥ', 
            'i̇', 'í', 'ï', 'î', 'ī', 
            'ĵ', 
            'ķ', 
            'ļ', 'ł', 'ĺ', 'ľ',  
            'ņ', 'ń', 'ň', 'ñ', 
            'ö', 'ó', 'ò', 'ø', 'õ', 'ô', 'ő', 'ð', 'ơ', 
            'ř', 'ŗ', 'ŕ', 
            'ş', 'š', 'ŝ', 'ś', 'ș', 
            'ť', 'ț', 'ţ', 
            'þ', 
            'ü', 'ú', 'ů', 'ŭ', 'ù', 'û', 'ű', 'ū', 'ų', 'ư', 
            'ŵ', 
            'ý', 'ÿ', 'ŷ', 
            'ž', 'ż', 'ź', 
            'À', 'Á', 'Å', 'Ä', 'Â', 'Ă', 'Ā', 'Ą', 'Ã', 'Ə', 
            'Æ', 
            'SS', 
            'Ç', 'Ć', 'Č', '¢', 'Ĉ', 
            'Œ', 
            'Đ', 'Ď', 
            'Ë', 'É', 'È', 'Ě', 'Ê', 'Ē', 'Ę', 'Ė', 
            'Ğ', 'Ĝ', 'Ģ', 
            'Ĥ', 
            'İ', 'Í', 'Ï', 'Î', 'Ī', 
            'Ĵ', 
            'Ķ', 
            'Ļ', 'Ł', 'Ĺ', 'Ľ',  
            'Ņ', 'Ń', 'Ň', 'Ñ', 
            'Ö', 'Ó', 'Ò', 'Ø', 'Õ', 'Ô', 'Ő', 'Ð', 'Ơ', 
            'Ř', 'Ŗ', 'Ŕ', 
            'Ş', 'Š', 'Ŝ', 'Ś', 'Ș', 
            'Ť', 'Ț', 'Ţ', 
            'Þ', 
            'Ü', 'Ú', 'Ů', 'Ŭ', 'Ù', 'Û', 'Ű', 'Ū', 'Ų', 'Ư', 
            'Ŵ', 
            'Ý', 'Ÿ', 'Ŷ', 
            'Ž', 'Ż', 'Ź'
        );
        $transformat = array(
            'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 
            'ae', 
            'b', 
            'c', 'c', 'c', 'c', 'c', 
            'ce', 
            'd', 'd', 
            'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 
            'g', 'g', 'g', 
            'h', 
            'i', 'i', 'i', 'i', 'i', 
            'j', 
            'k', 
            'l', 'l', 'l', 'l',  
            'n', 'n', 'n', 'n', 
            'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 
            'r', 'r', 'r', 
            's', 's', 's', 's', 's', 
            't', 't', 't', 
            'th', 
            'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 
            'w', 
            'y', 'y', 'y', 
            'z', 'z', 'z', 
            'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 
            'AE', 
            'B', 
            'C', 'C', 'C', 'C', 'C', 
            'CE', 
            'D', 'D', 
            'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 
            'G', 'G', 'G', 
            'H', 
            'I', 'I', 'I', 'I', 'I', 
            'J', 
            'K', 
            'L', 'L', 'L', 'L',  
            'N', 'N', 'N', 'N', 
            'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 
            'R', 'R', 'R', 
            'S', 'S', 'S', 'S', 'S', 
            'T', 'T', 'T', 
            'TH', 
            'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 
            'W', 
            'Y', 'Y', 'Y', 
            'Z', 'Z', 'Z'
        );

        #$text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');

        #$text = trim($text);

        $text = str_replace($diacritice, $transformat, $text);

        $text = strtolower($text);

        if (empty($text)) {
            return '';
        }
        return $text;
    }









    protected function departmentsGetList($public=true) {

        if ($this->databaseConnection()) {

            if ($public) {
                $qry = "CALL get_departments_public();";
            }else{
                $qry = "CALL get_departments_all();";
            }

            $q = $this->db_connection->prepare($qry);
            $q->execute();
            $r = $q->fetchAll(PDO::FETCH_ASSOC);
            
            return $r;
        }
        return false;
    }
    protected function departmentsGetById($id_department) {

        if ($this->databaseConnection()) {

            $q = $this->db_connection->prepare("CALL departmentsGetById(:id_departament);");
            $q->bindParam(":id_departament", $id_department, PDO::PARAM_INT);
            $q->execute();
            $r = $q->fetchObject();
            
            return $r;
        }
        return false;
    }
    protected function departmentsAdd($params) {
        $id_parent = isset($params['id_parent'])&&!empty($params['id_parent'])?$params['id_parent']:null;
        $name = isset($params['name'])?$params['name']:null;

        if (empty($name)||strlen($name)>100) {
            $_SESSION['msg_errors'][] = "&ldquo;Numele&rdquo; trebuie să nu fie gol și să conțină cel maxim 100 caractere.";
            $this->rep['errors']['name'] = "is-invalid";
            $this->errflag = true;
        }

        if ($this->errflag) {
            return false;
        }

        if ($this->databaseConnection()) {

            $id_department = null;

            $q = $this->db_connection->prepare("CALL insert_department(:name, :id_parent, @id_department); SLECT @id_department;");
            $q->bindParam(":name", $name, PDO::PARAM_STR);
            $q->bindParam(":id_parent", $id_parent, PDO::PARAM_INT);
            $q->execute();


            $q = $this->db_connection->prepare("SELECT @id_department AS id_department;");
            $q->execute();

            $r = $q->fetchObject();
            
            return $r->id_department;
        }
        return false;
    }
    protected function departmentsEdit($params) {
        $id_department = (isset($params['id_department'])&&!empty($params['id_department']))?$params['id_department']:null;
        $id_parent = isset($params['id_parent'])&&!empty($params['id_parent'])?$params['id_parent']:null;
        $name = isset($params['name'])?$params['name']:null;

        $statusArr = ['Public', 'Privat'];        

        $status = (isset($params['status'])&&in_array($params['status'], $statusArr))?$params['status']:"Privat";

        if (empty($id_department)) {
            $_SESSION['msg_errors'][] = "ID Departament lipsa";
            $this->redirect = $this->buildUrl(array('view'=>"a_departments_list"));
            $this->errflag = true;
        }else{
            $checkDept = $this->departmentsGetById($id_department);
            if (!isset($checkDept->ID)) {
                $_SESSION['msg_errors'][] = "Departament lipsa";
                $this->redirect = $this->buildUrl(array('view'=>"a_departments_list"));
                $this->errflag = true;
            }
        }


        if (empty($name)||strlen($name)>100) {
            $_SESSION['msg_errors'][] = "&ldquo;Numele&rdquo; trebuie să nu fie gol și să conțină cel maxim 100 caractere.";
            $this->rep['errors']['name'] = "is-invalid";
            $this->errflag = true;
        }

        if ($this->errflag) {
            return false;
        }

        if ($this->databaseConnection()) {

            if ($status=="Privat") {
                $q = $this->db_connection->prepare("CALL edit_department_prv(:id_department, :name, :id_parent);");
            }elseif ($status=="Public") {
                $q = $this->db_connection->prepare("CALL edit_department_pub(:id_department, :name, :id_parent);");
            }else{
                // nothing
                // already made sure that status has a valid value
            }
            
            $q->bindParam(":id_department", $id_department, PDO::PARAM_INT);
            $q->bindParam(":name", $name, PDO::PARAM_STR);
            $q->bindParam(":id_parent", $id_parent, PDO::PARAM_INT);
            $r = $q->execute();

            if ($r) {
                $_SESSION['msg_success'][] = "Departamentul a fost modificat.";
                return true;
            }
        }
        return false;
    }
    protected function departmentsDelete($params) {
        $id_department = (isset($params['id_department'])&&!empty($params['id_department']))?$params['id_department']:null;

        if (empty($id_department)) {
            $_SESSION['msg_errors'][] = "ID Departament lipsa";
            $this->redirect = $this->buildUrl(array('view'=>"a_departments_list"));
            $this->errflag = true;
        }else{
            $checkDept = $this->departmentsGetById($id_department);
            if (!isset($checkDept->ID)) {
                $_SESSION['msg_errors'][] = "Departament lipsa";
                $this->redirect = $this->buildUrl(array('view'=>"a_departments_list"));
                $this->errflag = true;
            }
        }

        if ($this->errflag) {
            return false;
        }

        if ($this->databaseConnection()) {
            try {
                
                $this->db_connection->beginTransaction();

                $q = $this->db_connection->prepare("CALL del_department(:id_department); CALL edit_department_parent_by_parent_id(:old_id_parent, :new_id_parent);");
                $q->bindParam(":id_department", $id_department, PDO::PARAM_INT);
                $q->bindParam(":old_id_parent", $id_department, PDO::PARAM_INT);
                $q->bindParam(":new_id_parent", $checkDept->id_parent, PDO::PARAM_INT);
                $q->execute();
                $q->closeCursor();

                // commit the transaction
                $this->db_connection->commit();

                $_SESSION['msg_success'][] = "Departamentul a fost sters";
                
                return true;
            } catch (\PDOException $e) {
                // rollback the transaction
                $this->db_connection->rollBack();

                // show the error message
                $_SESSION['msg_errors'][] = "Eroare la stergerea departamentului";
            }
        }
        return false;
    }









    
    
    protected function departmentsGenerateTreeView( $children ) {
        $result = '<ul class="wtree">';
        $tempResult = '';
        foreach ($children as $key => $value) {
            $hasChildren = false;
            $hasChildrenCls = "";
            if (isset($value['children'])&&!empty($value['children'])) {
                $hasChildren = true;
                $hasChildrenCls = ' tree-toggler noshow';
            }
            $userCls = ($value['type']=="dept")?'':' user';
            $type = ($value['type']=="dept")?'icon-building':'icon-buildinguser';
            $tempResult .= '<li>';


            $tempResult .= '<span class="'.$hasChildrenCls.$userCls.'"><svg height="24" width="24"><use xlink:href="#'.$type.'"></use></svg> '.$value['name'].'</span>';
            if ($hasChildren) {
                $tempResult .= $this->departmentsGenerateTreeView( $value['children'] );
            }
            $tempResult .= '</li>';
        }
        if (empty($tempResult)) {
            $result = 'Nu sint rezultate';
        }else{
            $result .= $tempResult;
            $result .= '</ul>';
        }
        return $result;
    }
    protected function departmentsGetChildrenDirect( $id_parent=null ){

        if ($this->databaseConnection()) {
            if (empty($id_parent)) {
                $q = $this->db_connection->prepare("CALL get_departments_root();");
            } else {
                $q = $this->db_connection->prepare("CALL get_departments_byparent(:id_parent);");
                $q->bindValue(":id_parent", $id_parent, PDO::PARAM_INT);
            }
            $q->execute();
            $r = $q->fetchAll();

            return $r;
        }
        return false;
    }
    protected function departmentsGetChildrenAll( $id_parent=null ){
        $results = array();

        $temp = $this->departmentsGetChildrenDirect($id_parent);
        foreach ($temp as $child) {
            if ($child['type']=="dept") {
                $child['children'] = $this->departmentsGetChildrenAll($child['ID']);
            }
            $results[] = $child;
        }
        return $results;
    }









    
    
    protected function databaseConnection(){
        if ($this->db_connection != null) {
            return true;
        } else {
            try {
                $this->db_connection = new PDO('mysql:host='. DB_HOST .';dbname='. DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
                return true;
            } catch (PDOException $e) {
                $_SESSION['msg_errors'][] = MESSAGE_DATABASE_ERROR . $e->getMessage();
            }
        }
        return false;
    }
}