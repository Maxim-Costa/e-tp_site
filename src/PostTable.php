<?php

namespace App;

use PDO;

class PostTable
{

    private $pdo;

    public function __construct(PDO $pdo)
    {

    }

    public static function GetTpUserById(PDO $pdo, int $id)
    { //TODO : a refaire
        $query = $pdo->query("SELECT id_user, speudo_user, email_user, password_user, discord_user ,score_user, group_concat(b.tp_projet) as all_tp_name, group_concat(a.note) as all_tp_note ,group_concat(b.id_projet) as all_tp_id, group_concat(a.corrected) as all_tp_correct, group_concat(a.link SEPARATOR '$1447$') as all_tp_link, count(distinct b.tp_projet) as tp_count, (select count(distinct d.id_user) from user_etphoste d) as count_user_all, (select count(distinct c.id_projet) from projet_etphoste c where date_start_projet<now()) as count_tp_all, find_in_set(score_user,(select group_concat(score_user ORDER BY score_user DESC) from user_etphoste)) as rank, find_in_set(score_user,(select group_concat(score_user ORDER BY score_user DESC) from user_etphoste)) as rank_global from user_etphoste left join user_etphoste_has_projet_etphoste a on a.user_id_user = user_etphoste.id_user left join projet_etphoste b on b.id_projet = a.projet_id_projet where id_user = " . $id . " group by id_user");
        $tp = $query->fetchAll(PDO::FETCH_OBJ);
        return $tp;
    }

    public static function GetUserHistory(PDO $pdo, int $id)
    {
        $query = $pdo->query("SELECT DATE_FORMAT(date,'%m/%Y') AS _date,score FROM history_score WHERE user_etphoste_id_user = " . $id);
        $history = $query->fetchAll(PDO::FETCH_OBJ);
        return $history;
    }

    public static function GetTpUserById_AllTp(PDO $pdo, int $id)
    { //TODO : a refaire
        $query = $pdo->query("SELECT id_user, speudo_user, email_user, discord_user, git_user, score_user, score_global_user, GROUP_CONCAT(b.tp_projet) AS all_tp_name, GROUP_CONCAT(a.note) AS all_tp_note, GROUP_CONCAT(b.id_projet) AS all_tp_id, GROUP_CONCAT(a.corrected) AS all_tp_correct, GROUP_CONCAT(a.link SEPARATOR '$1447$') AS all_tp_link, COUNT(DISTINCT b.tp_projet) AS tp_count, (SELECT COUNT(DISTINCT d.id_user) FROM user_etphoste d ) AS count_user_all, (SELECT COUNT(DISTINCT c.id_projet) FROM projet_etphoste c WHERE date_start_projet < NOW()) AS count_tp_all, FIND_IN_SET( score_user,(SELECT GROUP_CONCAT(score_user ORDER BY score_user DESC ) FROM user_etphoste ) ) AS rank, FIND_IN_SET( score_global_user, ( SELECT GROUP_CONCAT( score_global_user ORDER BY score_global_user DESC ) FROM user_etphoste ) ) AS rank_global, (SELECT GROUP_CONCAT(DISTINCT e.id_projet ORDER BY e.id_projet DESC) FROM projet_etphoste e WHERE date_start_projet < NOW() ORDER BY id_projet DESC) AS AllTp_id, (SELECT GROUP_CONCAT(DISTINCT f.tp_projet ORDER BY f.id_projet DESC) FROM projet_etphoste f WHERE date_start_projet < NOW() ORDER BY id_projet DESC) AS AllTp_name FROM user_etphoste LEFT JOIN user_etphoste_has_projet_etphoste a ON a.user_id_user = user_etphoste.id_user LEFT JOIN projet_etphoste b ON b.id_projet = a.projet_id_projet WHERE id_user = " . $id . " GROUP BY id_user");
        $tp = $query->fetchAll(PDO::FETCH_OBJ);
        return $tp;
    }

    public static function GetLastDate(PDO $pdo)
    {
        $query = $pdo->query("SELECT id_projet,tp_projet,date_final_projet FROM etphoste_client.projet_etphoste where date_start_projet<now() and date_final_projet>now() order by projet_etphoste.date_start_projet");
        $timeStop = $query->fetchAll(PDO::FETCH_OBJ);
        return $timeStop;
    }

    public static function GetLastDateAll(PDO $pdo)
    {
        $query = $pdo->query("SELECT date_final_projet FROM projet_etphoste WHERE id_projet = (SELECT MAX(id_projet) FROM projet_etphoste)");
        $timeStop = $query->fetchAll(PDO::FETCH_OBJ);
        return $timeStop;
    }

    public static function UpdateUser(PDO $pdo, int $id, string $email, string $pseudo, int $discord, string $password)
    {
        $query = $pdo->prepare("UPDATE user_etphoste set email_user = :email, speudo_user = :pseudo, discord_user = :discord, password_user = :password where id_user = :id;");
        $ok = $query->execute(array('id' => $id, 'email' => trim(strtolower($email)), 'pseudo' => $pseudo, 'discord' => $discord, 'password' => $password));
        if ($ok === false) {
            throw new \Exception("Impossible d'éditer la valeur 1");
        }
    }

    public static function Get(PDO $pdo, string $q): array
    { //TODO : a refaire
        $q = base64_encode('%' . trim($q) . '%');
        $query = $pdo->query('SELECT id_user, speudo_user, score_user, score_global_user, group_concat(b.tp_projet) as all_tp_name, group_concat(a.note) as all_tp_note ,group_concat(b.id_projet) as all_tp_id, group_concat(a.corrected) as all_tp_correct, group_concat(a.link SEPARATOR "$1447$") as all_tp_link, count(distinct b.tp_projet) as tp_count, (select count(distinct d.id_user) from user_etphoste d) as count_user_all, (select count(distinct c.id_projet) from projet_etphoste c where date_start_projet<now()) as count_tp_all, find_in_set(score_user,(select group_concat(score_user ORDER BY score_user DESC) from user_etphoste)) as rank, find_in_set(score_user,(select group_concat(score_user ORDER BY score_user DESC) from user_etphoste)) as rank_global from user_etphoste left join user_etphoste_has_projet_etphoste a on a.user_id_user = user_etphoste.id_user left join projet_etphoste b on b.id_projet = a.projet_id_projet where speudo_user like FROM_BASE64("' . $q . '") group by id_user order by score_user DESC');
        $posts = $query->fetchAll(PDO::FETCH_OBJ);
        return $posts;
    }

    public static function GetSortGlobal(PDO $pdo, string $q): array
    { //TODO : a refaire
        $q = base64_encode('%' . trim($q) . '%');
        $query = $pdo->query("SELECT id_user, speudo_user, score_user, score_global_user, group_concat(b.tp_projet) as all_tp_name, group_concat(a.note) as all_tp_note, group_concat(b.id_projet) as all_tp_id, group_concat(a.corrected) as all_tp_correct, group_concat(a.link SEPARATOR '$1447$') as all_tp_link, count(distinct b.tp_projet) as tp_count, (select count(distinct d.id_user) from user_etphoste d) as count_user_all, (select count(distinct c.id_projet) from projet_etphoste c where date_start_projet<now()) as count_tp_all, find_in_set(score_user,(select group_concat(score_user ORDER BY score_user DESC) from user_etphoste)) as rank, find_in_set(score_global_user,(select group_concat(score_global_user ORDER BY score_global_user DESC) from user_etphoste)) as rank_global from user_etphoste left join user_etphoste_has_projet_etphoste a on a.user_id_user = user_etphoste.id_user left join projet_etphoste b on b.id_projet = a.projet_id_projet where speudo_user like FROM_BASE64('" . $q . "') group by id_user order by score_global_user DESC");
        $posts = $query->fetchAll(PDO::FETCH_OBJ);
        return $posts;
    }

    public static function GetSortGlobalLimit(PDO $pdo, string $q, int $limit): array
    { //TODO : a refaire
        $query = $pdo->query("SELECT id_user, speudo_user, score_user, score_global_user, group_concat(b.tp_projet) as all_tp_name, group_concat(a.note) as all_tp_note, group_concat(b.id_projet) as all_tp_id, group_concat(a.corrected) as all_tp_correct, group_concat(a.link SEPARATOR '$1447$') as all_tp_link, count(distinct b.tp_projet) as tp_count, (select count(distinct d.id_user) from user_etphoste d) as count_user_all, (select count(distinct c.id_projet) from projet_etphoste c where date_start_projet<now()) as count_tp_all, find_in_set(score_user,(select group_concat(score_user ORDER BY score_user DESC) from user_etphoste)) as rank, find_in_set(score_global_user,(select group_concat(score_global_user ORDER BY score_global_user DESC) from user_etphoste)) as rank_global from user_etphoste left join user_etphoste_has_projet_etphoste a on a.user_id_user = user_etphoste.id_user left join projet_etphoste b on b.id_projet = a.projet_id_projet group by id_user order by score_global_user DESC LIMIT " . $limit);
        $posts = $query->fetchAll(PDO::FETCH_OBJ);
        return $posts;
    }

    public static function GetLimit(PDO $pdo, string $q, int $limit): array
    { //TODO : a refaire
        $query = $pdo->query('SELECT id_user, speudo_user, score_user, score_global_user, group_concat(b.tp_projet) as all_tp_name, group_concat(a.note) as all_tp_note ,group_concat(b.id_projet) as all_tp_id, group_concat(a.corrected) as all_tp_correct, group_concat(a.link SEPARATOR "$1447$") as all_tp_link, count(distinct b.tp_projet) as tp_count, (select count(distinct d.id_user) from user_etphoste d) as count_user_all, (select count(distinct c.id_projet) from projet_etphoste c where date_start_projet<now()) as count_tp_all, find_in_set(score_user,(select group_concat(score_user ORDER BY score_user DESC) from user_etphoste)) as rank, find_in_set(score_user,(select group_concat(score_user ORDER BY score_user DESC) from user_etphoste)) as rank_global from user_etphoste left join user_etphoste_has_projet_etphoste a on a.user_id_user = user_etphoste.id_user left join projet_etphoste b on b.id_projet = a.projet_id_projet group by id_user order by score_user DESC LIMIT ' . $limit);
        $posts = $query->fetchAll(PDO::FETCH_OBJ);
        return $posts;
    }

    public static function SetScoreIsOk(PDO $pdo, int $id, int $idTp)
    {
        $query = $pdo->prepare("SELECT date_final_projet<uploaded AS ok FROM user_etphoste_has_projet_etphoste, projet_etphoste WHERE user_id_user = :id AND projet_id_projet = :idTP AND id_projet = :idTP;");
        $query->execute(array('id' => $id, 'idTP' => $idTp));
        $query->setFetchMode(PDO::FETCH_OBJ);
        $posts = $query->fetch();
        return (int)$posts->ok;
    }

    public static function Correction(PDO $pdo, int $id, int $idTp, float $score): void
    {
        $query = $pdo->prepare("UPDATE user_etphoste_has_projet_etphoste SET note = :score, corrected = 0 WHERE user_id_user = :id and projet_id_projet = :idTP;");
        $ok = $query->execute(array('id' => $id, 'idTP' => $idTp, 'score' => $score));
        if ($ok === false) {
            throw new \Exception("Impossible d'éditer la valeur 1");
        }
        $query = $pdo->prepare("UPDATE user_etphoste SET score_user = score_user + :score, score_global_user = score_global_user + :score WHERE id_user = :id;");
        $ok = $query->execute(array('id' => $id, 'score' => $score));
        if ($ok === false) {
            throw new \Exception("Impossible d'éditer la valeur 2");
        }
    }


    public static function CorrectionGlobal(PDO $pdo, int $id, int $idTp, float $score): void
    {
        $query = $pdo->prepare("UPDATE user_etphoste_has_projet_etphoste SET note = :score, corrected = 0 WHERE user_id_user = :id and projet_id_projet = :idTP;");
        $ok = $query->execute(array('id' => $id, 'idTP' => $idTp, 'score' => $score));
        if ($ok === false) {
            throw new \Exception("Impossible d'éditer la valeur 1");
        }
        $query = $pdo->prepare("UPDATE user_etphoste SET score_global_user = score_global_user + :score WHERE id_user = :id;");
        $ok = $query->execute(array('id' => $id, 'score' => $score));
        if ($ok === false) {
            throw new \Exception("Impossible d'éditer la valeur 2");
        }
    }


    public static function Update(PDO $pdo, int $id, int $idTP, string $linkTP): void
    { //TODO : a refaire
        $query = $pdo->prepare("INSERT INTO user_etphoste_has_projet_etphoste (user_id_user,projet_id_projet,corrected,link,note) VALUES (:id, :idTP, 1, :linkTP, 0);");
        $ok = $query->execute(array('id' => $id, 'idTP' => $idTP, 'linkTP' => $linkTP));
        if ($ok === false) {
            throw new \Exception("Impossible d'ajouter le TP");
        }
    }

    public static function findByPseudo(PDO $pdo, string $pseudo)
    {
        $query = $pdo->prepare("SELECT id_user,email_user,password_user,role_user FROM etphoste_client.user_etphoste where speudo_user = :pseudo");
        $query->execute(array('pseudo' => $pseudo));
        $query->setFetchMode(PDO::FETCH_OBJ);
        $posts = $query->fetch();

        return $posts;
    }

    public static function findByDiscord(PDO $pdo, int $id_discord)
    {
        $query = $pdo->prepare("SELECT id_user FROM etphoste_client.user_etphoste where discord_user = :id_discord");
        $query->execute(array('id_discord' => $id_discord));
        $query->setFetchMode(PDO::FETCH_OBJ);
        $posts = $query->fetch();

        return $posts;
    }

    public static function Add(PDO $pdo, array $data): void
    {
        $query = $pdo->prepare("INSERT INTO projet_etphoste (tp_projet,date_final_projet,date_start_projet,desc_projet,type_projet) VALUES (:title,:time_stop,:time_start,:containt,:type_projet)");
        $ok = $query->execute($data);
        if ($ok === false) {
            throw new \Exception("Impossible d'ajouté les valeurs");
        }
    }

    public static function findByEmail(PDO $pdo, string $email)
    {
        $query = $pdo->prepare("SELECT id_user,email_user,speudo_user,password_user,role_user FROM etphoste_client.user_etphoste where email_user = :email");
        $query->execute(array('email' => trim(strtolower($email))));
        $query->setFetchMode(PDO::FETCH_OBJ);
        $posts = $query->fetch();

        return $posts;
    }

    public static function GetCurrentTp(PDO $pdo)
    {
        $query = $pdo->query("SELECT * from etphoste_client.projet_etphoste where date_start_projet<now() order by projet_etphoste.date_start_projet");
        $tp_querys = $query->fetchAll(PDO::FETCH_OBJ);
        return $tp_querys;
    }

    public static function GetTp(PDO $pdo)
    {
        $query = $pdo->query("SELECT * from etphoste_client.projet_etphoste order by projet_etphoste.date_start_projet");
        $tp_querys = $query->fetchAll(PDO::FETCH_OBJ);
        return $tp_querys;
    }

    public static function NewUser(PDO $pdo, string $email, string $password, string $pseudo)
    {
        $query = $pdo->prepare("INSERT INTO `user_etphoste`( `email_user`, `password_user`, `speudo_user` ) VALUES( :email, :password, :pseudo )");
        $ok = $query->execute(array('email' => trim(strtolower($email)), 'password' => $password, 'pseudo' => $pseudo));
        return $ok;
    }

}   