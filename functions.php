<?php
/**
 * Created by PhpStorm.
 * User: vis
 * Date: 26/11/18
 * Time: 12:49
 */
//Функцията връща цялото меню
function get_menu () {
    $r = mysqli_query("select * from cats");
    //Елементите на менюто по подразбиране. Може да добавите свои връзки в менюто ви
    $result = "<a href='index.php'>Main Page</a><br>";

    while ($row=mysqli_fetch_array($r))
        $result .= "<a href='index.php?p=filter&cat=$row[id]'>$row[cname]</a><br>";
        return $result;


}

//Фунцкията връща списък с коментарите към статията, зададена с аргумента id
function get_comments($id) {
    $id = strip_tags($id);
    $result = " ";
    if (is_numeric($id)) {
        $r = mysqli_query("select * from comments where rid=$id order by id desc");

        while ($row=mysqli_fetch_array($r)) {
            $dt = date('d-m-Y',$row[dt]);
            $result .= "<hr><a href=mailto:$row[email]>$row[uname]></a>($dt) <p>$row[txt]</p>";

        }
    }
    else $result = "wrong argument in get_comment";
    return $result;
}

//Функцията връща запис с номер $id и извиква фунцкяита get_comments за показване на коментарите
function get_blog_records($id){
    $id= strip_tags($id);
    if (is_numeric($id)) {
        $r = mysqli_query("select * from blog where id=$id limit 1 ");
        $dt = date('d-m-Y',$row[dt]);
        //Получаваме коментарите за статията id
        $comments = get_comments($id);
        $result = "<h1>$row[header]</h1><p>$dt</p><b>$row[anonce]</b><p>$row[txt]</p><p><h2>Comments</h2></p><a href='add_comment.php?id=$id'>Add Coments</a>$comments";

    }
    else $result = "Wrong entry Id";
    return $result;
}
//Връща името на категорията по нейното $id
function get_cat_name($id) {
    $id = strip_tags($id) ;
    if (is_numeric($id)) {
        $r = mysqli_query("select * from cats where id=$id limit 1 ") ;
        $row = mysqli_fetch_array($r);
        return $row[cname];
    }
    else return "wrong category";
}
//Връща списък със записите от категория $id
function get_cat($id) {
    $cat  = $_GET['cat'];
    if (is_numeric($cat)) {
        $q = "select * from blog where cid=$cat order by id desc";

        $r = mysqli_query($q);

        $list = " ";
        while ($row=mysqli_fetch_array($r)) {
            $dt = date('d-m-Y H:i' , $row[dt]);
            $list .= "<p><table width='100%'><tr><td bgcolor='#808080' width='80%'><font color='white'>$row[header]</td><td bgcolor='#808080' width='20%'><font color='white'>$dt</td></tr></table>";

            //Показваме анонса
            $list .= "<p>$row[anonce]</p>";

            $cat = get_cat_name($row[cid]);
            $list .= "
                    <p>
                    <table width='100%'>
                    <tr>
                    <td width='60%'>
                    <a href='index.php?p=show&id=$row[id]'>Read more
                    </a>                      
                    </td>
                    <td width='20%'>
                    $cat
                    </td>
                    <td width='20$'>Comments
                    </td>
                    </tr>
                    </table>
                    </p> 
                    ";
        }
        return $list;
    }
    else return "Wrong category ID" ;
}