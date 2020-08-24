<?php

namespace App\POST;

use App\Connection;
use App\PostTable;

class verify
{
    private $error;
    private $feeback;
    private $post_info;
    private $post;
    private $files;
    private $userInfo;
    private $pdo;

    public function __construct(object $params_post_info, array $post, object $userInfo, array $FILES)
    {
        $this->error = (object)[];
        $this->feeback = '';
        $this->post_info = $params_post_info;
        $this->files = $FILES;
        $this->post = $post;
        $this->userInfo = $userInfo;
        $this->pdo = Connection::getPDO();
    }

    public function verifyLoop(array $who)
    {
        foreach ($who as $func) {
            $this->$func($func);
        }
    }

    public function email($type)
    {
        if ($this->post[$type]) {
            $this->post_info->email = $this->post[$type];
            if (filter_var($this->post[$type], FILTER_VALIDATE_EMAIL)) {
                $answer = PostTable::findByEmail($this->pdo, $this->post[$type]);
                if ($this->post[$type] === $this->userInfo->email_user) {

                } elseif ($answer === false) {
                    $this->error->email = 'is-valid';
                } else {
                    $this->feeback = 'Le Mail est déjà utilisé';
                    $this->error->email = 'is-invalid';
                }
            } else {
                $this->feeback = 'Le Mail n\'est pas conforme';
                $this->error->email = 'is-invalid';
            }
        } else {
            $this->feeback = 'Le Mail n\'a pas été renseigné';
            $this->error->email = 'is-invalid';
        }
    }

    public function pseudo($type)
    {
        if ($this->post[$type]) {
            $this->post_info->pseudo = $this->post[$type];
            if (strlen($this->post[$type]) < 20) {
                $answer = PostTable::findByPseudo($this->pdo, $this->post['pseudo']);
                if ($this->post['pseudo'] === $this->userInfo->speudo_user) {

                } elseif ($answer === false) {
                    $this->error->pseudo = 'is-valid';
                } else {
                    $this->feeback = 'Pseudo déjà utilisé';
                    $this->error->pseudo = 'is-invalid';
                }
            } else {
                $this->feeback = 'Le Pseudo est trop long (max: 20 chr)';
                $this->error->pseudo = 'is-invalid';
            }
        } else {
            $this->feeback = 'Le Pseudo n\'a pas été renseigné';
            $this->error->pseudo = 'is-invalid';
        }
    }

    public function discord($type)
    {

        if ($this->post[$type]) {
            $this->post_info->discord = $this->post[$type];
            if (ctype_digit($this->post[$type]) && (int)$this->post[$type] < 9223372036854775807) {
                $answer = PostTable::findByDiscord($this->pdo, (int)$this->post[$type]);


                if ($this->post['discord'] === $this->userInfo->discord_user) {

                } elseif ($answer === false) {
                    $response = file_get_contents('http://103.best:5005/api/v4/dicsord/getUser?id=' . $this->post[$type]);
                    $response = json_decode($response);
                    if ($response->code !== 10013 && $response->error !== "you need id params") {
                        if (!$response->bot) {
                            $this->error->discord = 'is-valid';
                        } else {
                            $this->feeback = 'Tu n\'es pas un bot :)';
                            $this->error->discord = 'is-invalid';
                        }
                    } else {
                        $this->feeback = 'Utilisateur non trouvé';
                        $this->error->discord = 'is-invalid';
                    }
                } else {
                    $this->feeback = 'Discord déjà utilisé';
                    $this->error->discord = 'is-invalid';
                }
            } else {
                $this->feeback = 'Un id discord n\'est composé que de nombre entier et il est égual ou inférieur à 9223372036854775807';
                $this->error->discord = 'is-invalid';
            }
        }
    }

    public function img_logo($type)
    {
        if (!empty($this->files[$type]["tmp_name"])) {

            $target_dir = "avatar/";
            $target_file = $target_dir . basename("avatar-" . $this->userInfo->id_user . ".png");
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($this->files[$type]["name"])['extension']);
            $check = getimagesize($this->files[$type]["tmp_name"]);

            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $this->feeback = "Le fichier n'est pas une image conforme";
                $this->error->img_logo = "is-invalid";
                $uploadOk = 0;
            }

            if (file_exists($target_file) && $uploadOk !== 0) {
                unlink($target_file);
            }

            if ($this->files[$type]["size"] > 2097152 && $uploadOk !== 0) {
                $this->feeback = "L'image est trop lourd (img<2M)";
                $this->error->img_logo = "is-invalid";
                $uploadOk = 0;
            }

            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $uploadOk !== 0) {
                $this->feeback = "Seul les images aux format JPG, JPEG & PNG sont autorisé";
                $this->error->img_logo = "is-invalid";
                $uploadOk = 0;
            }

            if ($uploadOk !== 0) {
                if (move_uploaded_file($this->files[$type]["tmp_name"], $target_file)) {
                } else {
                    $this->feeback = "Il y a eu une erreur leur de l'upload";
                    $this->error->img_logo = "is-invalid";
                }
            }
        }
    }

    public function password($type)
    {
        if ($this->post[$type]) {
            if (password_verify($this->post[$type], $this->userInfo->password_user) === true) {
                $this->error->password = "is-valid";
            } else {
                $this->feeback = "c'est pas le bon mot de passe";
                $this->error->password = "is-invalid";
            }
        } else {
            $this->feeback = "Entrer le MDP actuel";
            $this->error->password = "is-invalid";
        }
    }

    public function password_new($type)
    {
        if ($this->post[$type]) {
            if ($this->error->password === "is-valid") {
                if (password_verify($this->post[$type], $this->userInfo->password_user) === false) {
                    if (strlen($this->post[$type]) < 255) {
                        $this->error->password_new = "is-valid";
                        $this->post_info->password = password_hash($this->post[$type], PASSWORD_DEFAULT);
                    } else {
                        $this->feeback = "Le MDP est trop long (MDP>255) ";
                        $this->error->password_new = "is-invalid";
                    }
                } else {
                    $this->feeback = "le nouveau MDP doit être different de l'encien";
                    $this->error->password_new = "is-invalid";
                }
            }
        } else {
            $this->feeback = "entrer un nouveau MDP";
            $this->error->password_new = "is-invalid";
        }
    }

    public function get($property)
    {
        return $this->$property;
    }

}