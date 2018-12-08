<?php
require_once __DIR__ . "/../model/Model.class.php";

class Users extends Model
{

    protected $create_string = "CREATE TABLE IF NOT EXISTS users (
                                    user_id serial NOT NULL PRIMARY KEY,
                                    user_name varchar(32) NOT NULL UNIQUE,
                                    user_password text NOT NULL,
                                    user_is_admin boolean NOT NULL DEFAULT FALSE,
                                    user_gender varchar(16),
                                    user_birth date,
                                    user_email varchar(64),
                                    user_tel varchar(64),
                                    user_intro text
                                );";

    public function register($register)
    {
        /**
         * @var string $user_name
         * @var string $user_password
         * @var string $user_email
         * @var string $user_tel
         */
        extract($register);
        if (pg_fetch_assoc(pg_query($this->connection, "SELECT * FROM users WHERE user_name='$user_name'")))
            return false;
        else {
            $user_password = md5($user_password);
            $user_is_admin = ($user_name == "admin") ? "true" : "false";
            $register_query = "INSERT INTO users (user_name, user_password, user_is_admin, user_email, user_tel)
                               VALUES('$user_name', '$user_password', '$user_is_admin','$user_email', '$user_tel')";
            if (pg_query($this->connection, $register_query)) {
                $result = pg_fetch_assoc(pg_query($this->connection, "SELECT * FROM users WHERE user_name='$user_name'"));
                $this->remember($result["user_id"], $result["user_name"], $result["user_is_admin"]);
                return true;
            } else
                return false;
        }
    }

    public function login($login)
    {
        /**
         * @var string $user_name
         * @var string $user_password
         */
        extract($login);
        if (pg_num_rows(pg_query($this->connection, "SELECT * FROM users WHERE user_name='$user_name'")) == 0)
            return false;
        else {
            $result = pg_fetch_assoc(pg_query($this->connection, "SELECT * FROM users WHERE user_name='$user_name'"));
            if ($result && $user_name === $result["user_name"] && md5($user_password) === $result["user_password"]) {
                $this->remember($result["user_id"], $result["user_name"], $result["user_is_admin"]);
                return true;
            } else
                return false;
        }
    }

    public function update($update)
    {
        /**
         * @var string $user_name
         * @var string $user_password
         * @var string $new_password
         * @var string $user_gender
         * @var string $user_birth
         * @var string $user_email
         * @var string $user_tel
         * @var string $user_intro
         */
        extract($update);
        if (pg_num_rows(pg_query($this->connection, "SELECT * FROM users WHERE user_name='$user_name'")) == 0)
            return false;
        else {
            $result = pg_fetch_assoc(pg_query($this->connection, "SELECT * FROM users WHERE user_name='$user_name'"));
            $old_password = md5($user_password);
            $new_password = (isset($new_password) && $new_password != "" && $new_password != null) ? md5($new_password) : $old_password;
            $update_query =  "UPDATE users
                              SET user_name = '$user_name', user_password = '$new_password', 
                                  user_gender = NULLIF('$user_gender', ''), user_birth = TO_DATE(NULLIF('$user_birth', ''), 'YYYYMMDD'), 
                                  user_email = NULLIF('$user_email', ''), user_tel = NULLIF('$user_tel', ''), user_intro = NULLIF('$user_intro', '')
                              WHERE user_name = '$user_name'";
            if ($old_password === $result["user_password"]) {
                if (pg_query($this->connection, $update_query)) {
                    $result = pg_fetch_assoc(pg_query($this->connection, "SELECT * FROM users WHERE user_name='$user_name'"));
                    if ($result) {
                        $this->remember($result["user_id"], $result["user_name"], $result["user_is_admin"]);
                        return true;
                    } else
                        return false;
                } else
                    return false;
            } else {
                return false;
            }
        }
    }

    public function remember($user_id, $user_name, $user_is_admin)
    {
        $_SESSION["user_id"] = $user_id;
        $_SESSION["user_name"] = $user_name;
        $_SESSION["user_is_admin"] = $user_is_admin;
    }

    public function getUserName($user_id)
    {
        return pg_fetch_assoc(pg_query($this->connection,"SELECT user_name FROM users WHERE user_id='$user_id'"));
    }

    public function selectAll($user_id)
    {
        if (isset($user_id)) {
            return pg_fetch_assoc(pg_query($this->connection, "SELECT * FROM users WHERE user_id='$user_id'"));
        } else
            return NULL;
    }

}
